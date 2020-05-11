<?php

namespace FormaPagamentoCieloApi2\Controller;

use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Carrinho\Controller\EcmCarrinhoController;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmVendaStatus;
use Firebase\JWT\JWT;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use Repasse\Model\Entity\EcmRepasse;
use Cake\Network\Http\Client;

class FormaPagamentoCieloApi2Controller extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;
    const LINK = 'https://cieloecommerce.cielo.com.br/api/public/v1/orders';

    private $venda = null;
    public function initialize()
    {
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Vendas.EcmVenda');
        $this->loadModel('FormaPagamento.EcmOperadoraPagamento');
        $this->loadModel('MdlUser');

        parent::initialize();
        $this->configuracao();
    }

    public function beforeFilter(Event $event)
    {
        $carrinho = $this->request->session()->read('carrinho');

        if(is_null($carrinho)){
            $this->Flash->error(__('Usuário não selecionado!'));
            return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
        }

        if($this->request->params['action'] == "retorno" && array_key_exists('numeroTransacao', $this->request->data)){

            $this->venda = $this->EcmVenda->find()->contain([
                'EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']], 'EcmOperadoraPagamento',  'EcmTransacao'
            ]);

            if(array_key_exists('numeroTransacao', $this->request->data)){
                $numeroTransacao = $this->request->data['numeroTransacao'];
                $this->venda = $this->venda->matching('EcmTransacao', function($q)use($numeroTransacao){
                    return $q->where(['EcmTransacao.id' => $numeroTransacao]);
                })->first();
            }
        }else{
            $this->venda = $this->EcmVenda->find()->contain([
                'EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']],'EcmOperadoraPagamento'
            ])->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();
        }

        if(is_null($this->venda)){
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        $carrinho = $this->request->session()->read('carrinho');

        $ecmAlternativeHost = $this->EcmCarrinho->MdlUser->MdlUserEcmAlternativeHost
                                    ->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id);

        $this->environment = ($ambienteProducao->valor == 1) ? 'prodution' : 'sandbox';
        
        if($ecmAlternativeHost->shortname == 'AltoQi'){
            $this->headers = ['merchantid' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_id_checkout_altoqi'])->first()->valor ];
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_altoqi_checkout_cielo'])->first()->valor;
        }else{
            $this->headers = ['merchantid' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_id_checkout_qisat'])->first()->valor ];
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_checkout_cielo'])->first()->valor;        
        }

        $this->host = self::LINK;
    }


    public function requisicao()
    {
        $retorno = [ 'sucesso' => false, 'mensagem' => __('Falha na Requisição!') ];
        $http = new Client();

        if($transacao = $this->criarTransacao()){
            $dados = $this->getDados($transacao);

            try {
                $response = $http->post($this->host, json_encode($dados), ['type' => 'json', 'headers' => $this->headers]);
            } catch (\Exception $e) {
                $retorno['mensagem'] = __($e->getMessage());
            }

            if(!$this->request->session()->check('url_requisicao'))
                $this->request->session()->write('url_requisicao', $this->referer());

            if (isset($response) && $response->isOk()) {
                $result = $response->json;

                if(array_key_exists('settings', $result)){
                    if($this->request->is('post')){
                        return [ 'sucesso' => true, 'mensagem' => __('Requisição realizada com sucesso'),
                            'url' => $result['settings']['checkoutUrl'] ];
                    }
                    return $this->redirect($result['settings']['checkoutUrl']);
                }

                if(array_key_exists('settings', $result)){
                    $mensagem = array_pop($result['modelState'])[0];
                    $retorno['mensagem'] = __($mensagem);
                }
            }
        }

        if($this->request->is('post'))
            return $retorno;

        $this->Flash->error(__($retorno['mensagem']));
        return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
    }

    public function retorno($numeroTransacao = null){
        if(array_key_exists('id', $_GET) && is_numeric($_GET['id']))
            $numeroTransacao = $_GET['id'];

        $retorno = [ 'sucesso' => false ];
        $http = new Client();

        $referer = $this->request->session()->read('url_requisicao');
        if(strpos($referer, "/"))
            $referer = substr($referer, 0, strpos($referer, "/", 8)+1);

        $link_site = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_site'])->first()->valor;

        $this->host .= '/' . $this->headers['merchantid'] . '/';

        try {
            $transacao = $this->EcmTransacao->get($numeroTransacao, ['contain' => [
                'EcmVenda' => ['EcmCarrinho' => ['MdlUser']]
            ]]);
        } catch (RecordNotFoundException $e) {
            //if(!is_null($numero)){
                if (strpos($referer, $link_site) == false)
                    return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
                else
                    $retorno['mensagem'] = __('Transação não encontrada');
            //}
        }

        if(isset($transacao)){
            try {
                $response = $http->get($this->host.$transacao->id, [], ['type' => 'json', 'headers' => $this->headers]);
            } catch (\Exception $e) {
                if (strpos($referer, $link_site) == false)
                    return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
                else
                    $retorno['mensagem'] = __('Falha na Requisição!');
            }

            if (isset($response) && $response->isOk()) {
                $result = $response->json;
    
                if($result){
                    $this->criarTransacao($result);
        
                    if ($result['payment_status'] == 2 || $result['payment_status'] == 7) {
                        $retorno = [
                            'sucesso' => true,
                            'mensagem' => __('Pagamento efetuado com sucesso'),
                            'venda' => $this->venda->id
                        ];

                        if($transacao->ecm_venda->ecm_carrinho->status == "Em Aberto"){
                            $carrinho = $this->request->session()->read('carrinho');

                            $vendaStatus = $this->EcmVenda->EcmVendaStatus
                                ->find()->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])->first();
                            $this->venda->set('ecm_venda_status', $vendaStatus);

                            $this->EcmCarrinhoController = new EcmCarrinhoController();
                            $parcelasArray = $this->EcmCarrinhoController->calcularValorParcelas($this->venda->ecm_tipo_pagamento->ecm_forma_pagamento_id);

                            $this->venda->set('valor_parcelas', $parcelasArray[$result['payment_installments']]);
                            $this->venda->set('numero_parcelas', $result['payment_installments']);

                            $this->EcmVenda->save($this->venda);

                            if ($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)) {
                                $carrinhoNovo = $carrinho->novaEntidadeComValores();
                                $this->EcmCarrinho->save($carrinhoNovo);

                                $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                                $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                                $this->EcmCarrinho->save($carrinhoNovo);

                                $this->request->session()->write('carrinho', $carrinhoNovo);
                            }

                            $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);
                            $this->EcmCarrinho->save($carrinho);

                            $this->enviarEmailCompra($carrinho);

                            $this->Flash->success($retorno['mensagem']);
                        }

                    } else if (array_key_exists('Payment', $result) && $result['Payment']['ReturnMessage']){
                        $this->request->session()->write('error_transacao', $result['payment_status']);
                        $retorno['mensagem'] = $result['Payment']['ReturnMessage'];
                    }else{
                        $this->request->session()->write('error_transacao', $result['payment_status']);
                        $retorno['mensagem'] = $this->getMensagemTransacao($result['payment_status']);
                    }
                }
            }
        }

        if (strpos($referer, $link_site) === false){
            if($retorno['sucesso']) {
                $this->request->session()->write('url_requisicao', Router::url([
                    'plugin' => false, 'controller' => 'MdlUser', 'action' =>'listar-usuario'], true));
            } else {
                $linkEmail = "<a href='mailto:central@qisat.com.br'>central@qisat.com.br </a>";
                $mensagem = 'Ocorreu um erro ao finalizar uma transação com o pedido {0}, informe o suporte do site através do e-mail
                         '.$linkEmail.' ou entre em contato com a nossa central de vendas (48) 3332-5000.';

                $this->Flash->error(__($mensagem, [$this->venda->id]), ['params' => ['hiddenClick' => false]]);
                $this->Flash->error($retorno['mensagem']);

                //return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
            }
        }

        return $retorno;
    }

    private function getDados($transacao){
        $carrinho = $this->request->session()->read('carrinho');

        $items = array();
        foreach($carrinho->ecm_carrinho_item as $item){
            if($item->status == "Adicionado"){
                array_push($items, (object)[
                    "Name" => $item->ecm_produto->nome,
                    "Description" => $item->ecm_produto->nome,
                    "UnitPrice" => preg_replace("/[^0-9]/", "", number_format($item->valor_produto_desconto, 2)),
                    "Quantity" => $item->quantidade,
                    "Type" => "Digital"
                ]);
            }
        }

        $user = $this->MdlUser->find()->select(['firstname', 'lastname', 'email', 'phone1', 'phone2'])
            ->contain(['MdlUserDados' => function($q){
                return $q->select(['numero' => 'numero']);
            }])->where(['mdl_user_id' => $carrinho->get('mdl_user')->get('id')])->first();

        $customer = new \stdClass();
        $customer->Identity = preg_replace("/[^0-9]/", "", $user->get('numero'));

        if(strlen($customer->Identity) != 11 && strlen($customer->Identity) != 14)
            unset($customer->Identity);

        $customer->FullName = $user->get('firstname') . ' ' . $user->get('lastname');
        if(!strlen(trim($customer->FullName)) || strlen($customer->FullName) > 287)
            unset($customer->FullName);

        $customer->Email = $user->get('email');
        if(!preg_match('/[\w.]+@\w+[.]\w+/', $customer->Email) || strlen($customer->Email) > 63)
            unset($customer->Email);

        $customer->Phone = preg_replace("/[^0-9]/", "", $user->get('phone1'));
        if(strlen($customer->Phone) != 10 && strlen($customer->Phone) != 11){
            $customer->Phone = preg_replace("/[^0-9]/", "", $user->get('phone2'));
            if(strlen($customer->Phone) != 10 && strlen($customer->Phone) != 11){
                unset($customer->Phone);
            }
        }

        $token_tempo = $this->EcmConfig->find()->where(['nome' => 'login_token_tempo_expiracao'])->first()->valor;

        $valorParcelas = $this->request->data('valorParcelas');
        if(is_null($valorParcelas)){
            $info = JWT::decode(
                $this->request->session()->read('info')
                , Security::salt(), array('HS256'));
            $info = get_object_vars($info);
            $valorParcelas = $info['valorparcelas'];
        }

        $orderNumber = $transacao->get('id');

        $this->EcmCarrinhoController = new EcmCarrinhoController();
        $parcelasArray = $this->EcmCarrinhoController->calcularValorParcelas($this->venda->ecm_tipo_pagamento->ecm_forma_pagamento_id);
        end($parcelasArray);
        $payment = (object)[
            "MaxNumberOfInstallments" => key($parcelasArray)
        ];

        if(!isset($token))
            $token = JWT::encode([
                'sub' => $carrinho->get('mdl_user')->get('id'),
                'cookieTime' => $this->CookieOverride->read('QiSat')['time'],
                'exp' => time() + $token_tempo,
                'numeroTransacao' => $transacao->get('id')
            ], Security::salt());

        $returnUrl = Router::url([
            'plugin' => false,
            'controller' => '',
            'action' => 'pagamento',
            '?' => ['token' => $token, 'id' => $transacao->get('id')]
        ], true);

        $dados = (object)[
            "OrderNumber" => $orderNumber,
            "SoftDescriptor" => "AltoQi_QiSat",
            "Cart" => (object)[
                "Discount" => (object)[
                    "Type" => "Percent",
                    "Value" => "00"
                ],
                "Items" => $items
            ],
            "Shipping" => (object)[
                "type" => "WithoutShipping"
            ],
            "Payment" => $payment,
            "Customer" => $customer,
            "Options" => (object)[
                "ReturnUrl" => $returnUrl
            ]
        ];

        return $dados;
    }

    private function criarTransacao($result = null){

        $carrinho = $this->request->session()->read('carrinho');
        $ecmOperadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');

        if(is_null($result)) {
            $transacao = $this->EcmTransacao->newEntity();

            $transacao->set('estabelecimento', $this->estabelecimento);
            $transacao->set('valor', $carrinho->calcularTotal());
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_venda_id', $this->venda->id);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('ecm_transacao_status_id', 1);
            $transacao->set('descricao', 'Cartão Cielo Checkout');

            if(is_null($ecmOperadoraPagamento)){
                $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->find()
                    ->where(['ecm_forma_pagamento_id' => $this->venda->ecm_tipo_pagamento->ecm_forma_pagamento->id])
                    ->first();
                $this->venda->set('ecm_operadora_pagamento', $ecmOperadoraPagamento);
                $this->EcmVenda->save($this->venda);
            }
            $transacao->set('ecm_operadora_pagamento', $ecmOperadoraPagamento);

        }else{
            $transacao = $this->EcmTransacao->get($result['order_number']);

            $transacao->set('id_integracao', $result['checkout_cielo_order_number']);
            $transacao->set('ecm_transacao_status_id', $result['payment_status']);
            $transacao->set('data_retorno', new \DateTime());
            $transacao->set('data_cobranca', new \DateTime());
            $transacao->set('tid', $result['tid']);
            $transacao->set('url', $this->host);
            $transacao->set('descricao', 'Cartão Cielo Checkout '.$this->getBandeiraTransacao($result['payment_method_brand']));
    
        }

        return $this->EcmTransacao->save($transacao);
    }

    private function getOperadoraPagamento($descricao){
        return $this->EcmOperadoraPagamento->find()->where(['dataname' => $descricao,
            'ecm_forma_pagamento_id' => $this->venda->ecm_tipo_pagamento->ecm_forma_pagamento_id
        ])->first();
    }

    private function getStatusTransacao($descricao){
        switch($descricao){
            case 1://Pendente
            case 4://Expirado
            case 6://Não Finalizado
                return 'aguardando_pagamento';
            case 2://Pago
                return 'paga';
            case 3://Negado
                return 'negada';
            case 5://Cancelado
                return 'cancelada';
            case 7://Autorizado
                return 'aguardando_capturar';
            case 8://Chargeback(estorno)
                return 'estorno';//estorno
        }
        return 'erro';//erro
    }

    private function getMensagemTransacao($status){
        switch($status){
            case 1://Pendente
            case 4://Expirado
            case 6://Não Finalizado
                return 'Pagamento não finalizado!';
            case 2://Não Finalizado
                return 'Pagamento realizado com Sucesso!';
            case 3://Negado
                return 'Transação não autorizada pelo responsável do meio de pagamento';
            case 5://Cancelado
                return 'Transação foi cancelada';
            case 7://Autorizado
                return 'Pagamento realizado com Sucesso!';
            case 8://Chargeback(estorno)
                return 'Transação cancelada pelo consumidor junto ao emissor do cartão.';//estorno
        }
        return 'Falha no Pagamento!';//erro
    }

    private function getBandeiraTransacao($id_method_brand){
        switch($id_method_brand){
            case 1:
                $retorno = 'Visa';
                break;
            case 2:
                $retorno = 'Mastercad';
                break;
            case 3:
                $retorno = 'AmericanExpress';
                break;
            case 4:
                $retorno = 'Diners';
                break;
            case 5:
                $retorno = 'Elo';
                break;
            case 6:
                $retorno = 'Aura';
                break;
            case 7:
                $retorno = 'JCB';
                break;
            case 8:
                $retorno = 'Discover';
                break;
            case 9:
                $retorno = 'Hipercard';
                break;
            default: 
                $retorno = false;
        }
        return $retorno;
    }

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');

        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));

        $this->inserirRepasse($usuario, $carrinho);

        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;

        $aesHash = new AESPasswordHasher();
        $senha = $aesHash->decrypt($usuario->get('password'));

        $paramsEmail = [
            'usuario' => $carrinho->get('mdl_user'),
            'senha' => $senha,
            'produtos' => $carrinho->get('ecm_carrinho_item'),
            'pedido' => $this->venda->id,
            'valor' => number_format($this->venda->valor_parcelas, 2, ',', ''),
            'parcelas' => $this->venda->numero_parcelas
        ];

        $params = [$fromEmail, $carrinho->get('mdl_user')->get('email'), $paramsEmail];

        $this->getMailer('FormaPagamentoCieloApi2.FormaPagamentoCieloApi2')->send('compraEfetuada', $params);

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $params = [$fromEmail, $adminEmail, $paramsEmail];
        $this->getMailer('FormaPagamentoCieloApi2.FormaPagamentoCieloApi2')->send('compraEfetuada', $params);

        $this->request->session()->delete('compraConfirmada');
    }

    private function inserirRepasse($usuario, $carrinho){
        $this->loadModel('Repasse.EcmRepasse');

        $textoEmail = 'Prezado(a) <b> '.$usuario->firstname.' '.$usuario->lastname.'</b>,<br/>
                        <br/>
                        Seu pedido <b>'.$this->venda->id.'</b> foi efetuado com sucesso no site <a href="www.qisat.com.br">www.qisat.com.br </a><br/>
                        <br/>
                        Os cursos adquiridos foram:<br/>';

        foreach($carrinho->get('ecm_carrinho_item') as $produto){
            if($produto->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO) {
                if (($produto->get('ecm_produto')->get('mdl_course')) > 1) {
                    foreach ($produto->get('ecm_produto')->get('mdl_course') as $curso) {
                        $textoEmail .= '<b>' . $curso->get('fullname') . '</b><br/>';
                    }
                } else {
                    $textoEmail .= '<b>' . $produto->get('ecm_produto')->get('nome') . '</b><br/>';
                }
            }
        }

        $textoEmail .= '<br/>
                        Valor Total do Pedido: <b>'.$this->venda->numero_parcelas.'X de '.number_format($this->venda->valor_parcelas, 2, ',', '').'</b> <br/>
                        <br/>';

        $textoEmail .= 'A forma de pagamento escolhida foi:<br/>
                        '.$this->venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->get('nome').' <br/>
                        '.$this->venda->numero_parcelas;

        if($this->venda->numero_parcelas > 1)
            $textoEmail .= ' parcelas';
        else
            $textoEmail .= ' parcela';

        $textoEmail .= ' de '.number_format($this->venda->valor_parcelas, 2, ',', '').'<br/><br/>';

        $textoEmail .= 'Seus dados para acesso ao Ambiente Pessoal são:<br/>
                        Chave AltoQi/QiSat: <b>'.$usuario->username.'</b><br/>';

        $textoEmail .= '<br/>
                        - A equipe QiSat entrará em contato com você por telefone e/ou e-mail em até 48 horas para confirmar
                        seus dados de compra e garantir que a sua escolha foi a melhor solução.<br>
                        - Durante o contato você poderá tirar possíveis dúvidas e agendar a data de início do curso.<br>
                        - Para acessar o curso faça login em <a href="www.qisat.com.br">www.qisat.com.br</a> e acesse a
                        área do aluno. No ambiente pessoal clique na opção “Meus Cursos”.<br><br/><br/>
                        Se deseja falar diretamente com a empresa, poderá fazê-lo através da Central de Inscrições.<br/>';

        $repasse = $this->EcmRepasse->newEntity();
        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
        $repasse->set('assunto_email', 'QiSat| Confirmação de Pedido');
        $repasse->set('corpo_email',$textoEmail);
        $repasse->set('ecm_alternative_host_id', $carrinho->get('ecm_alternative_host_id'));

        $this->EcmRepasse->save($repasse);
    }
}