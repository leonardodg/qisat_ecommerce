<?php

namespace FormaPagamentoCieloApi3\Controller;

use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Datasource\Exception\RecordNotFoundException;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmVendaStatus;
use Firebase\JWT\JWT;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use Repasse\Model\Entity\EcmRepasse;
use Cake\Network\Http\Client;
use App\Controller\WscController;

class FormaPagamentoCieloApi3Controller extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;
    const REQUISICAO_HOMOLOGACAO = 'https://apisandbox.cieloecommerce.cielo.com.br/1/sales/';
    const REQUISICAO_PRODUCAO = 'https://api.cieloecommerce.cielo.com.br/1/sales/';

    private $venda = null;
    public function initialize()
    {
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
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

        $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();

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

        $shortname = ($ecmAlternativeHost->shortname == 'AltoQi') ? 'altoqi' : 'qisat';
        $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_'.$shortname.'_api_cielo'])->first()->valor;

        if($ambienteProducao->valor == 1){
            $this->host = self::REQUISICAO_PRODUCAO;
            $this->environment = 'prodution';

            $this->headers = [
                               'MerchantId' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_id_api_'.$shortname])->first()->valor,
                               'MerchantKey' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_key_api_'.$shortname])->first()->valor
                              ];
        }else{
            $this->host = self::REQUISICAO_HOMOLOGACAO;
            $this->environment = 'sandbox';

            $this->headers = [
                                'MerchantId' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_merchant_id_api_'.$shortname])->first()->valor,
                                'MerchantKey' => $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_merchant_key_api_'.$shortname])->first()->valor
                              ];
        }
    }

    public function requisicao()
    {
        $retorno = [ 'sucesso' => false ];
        $recorrencia = null;
        $http = new Client();
        $carrinho = $this->request->session()->read('carrinho');

        if($this->venda->ecm_operadora_pagamento->ecm_forma_pagamento->tipo == "cartao_recorrencia") {
            $recorrencia = $this->setRecorrencia();
        }
        if($transacao = $this->criarTransacao(null, null, $recorrencia)){

            $dados = $this->getDados($transacao, $recorrencia);

            try {
                $response = $http->post($this->host, json_encode($dados), ['type' => 'json', 'headers' => $this->headers]);
                $result = $response->json;
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição!');
                return $retorno;
            }

            if ($response->isOk() && $result && $result['Payment']) {
                if($transacao = $this->criarTransacao($transacao, $result['Payment'])){
                    if ($result['Payment']['ReturnCode'] == 4 || $result['Payment']['ReturnCode'] == 6) {

                        $retorno = [
                            'sucesso' => true,
                            'mensagem' => __('Pagamento efetuado com sucesso'),
                            'venda' => $this->venda->id
                        ];

                        if($this->venda->ecm_operadora_pagamento->ecm_forma_pagamento->tipo == "cartao_recorrencia") {
                            $recorrencia = $this->setRecorrencia($result, $recorrencia);

                            if($recorrencia->valor !== $this->venda->valor_parcelas){
                                $host = str_replace('sales', 'RecurrentPayment', $this->host) . $result['Payment']['RecurrentPayment']['RecurrentPaymentId'] . '/Amount';
                                $this->headers['RequestId'] = $this->headers['MerchantId'];
        
                                try {
                                    $res = $http->put($host, preg_replace("/[^0-9]/", "", $this->venda->valor_parcelas), ['type' => 'json', 'headers' => $this->headers]);
                                } catch (\Exception $e) {
                                    $retorno['error'] = __('Falha na Requisição - Atualizar valor Recorrência');
                                }

                                if(($res->isOk())){
                                    $recorrencia->set('valor', $this->venda->valor_parcelas);
                                    $this->EcmRecorrencia->save($recorrencia);
                                }
                            }
                        }

                        if ($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)) {
                            $carrinhoNovo = $carrinho->novaEntidadeComValores();
                            $this->EcmCarrinho->save($carrinhoNovo);

                            $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                            $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                            $this->EcmCarrinho->save($carrinhoNovo);

                            $this->request->session()->write('carrinho', $carrinhoNovo);
                        }

                        $vendaStatus = $this->EcmVenda->EcmVendaStatus
                                                    ->find()->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])->first();

                        $this->venda->set('ecm_venda_status', $vendaStatus);
                        $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                        $this->EcmVenda->save($this->venda);
                        $this->EcmCarrinho->save($carrinho);

                        $this->enviarEmailCompra($carrinho);

                        if(!$this->request->is('post')){
                            $this->request->session()->delete('Flash');
                            $this->Flash->success(__($retorno['mensagem']));
                            return $this->redirect(['plugin' => false, 'controller' => 'MdlUser', 'action' => 'listar-usuario']);
                        }

                    } else if ($result['Payment']['ReturnMessage']){

                        if(isset($recorrencia)) {
                            $recorrencia->set('status', '0');
                            $this->EcmRecorrencia->save($recorrencia);
                        }

                        $retorno['mensagem'] = $this->getMensagemRetornoTransacao($result['Payment']['ReturnMessage']);
                        $transacao->set('erro', $result['Payment']['ReturnMessage']);
                        $this->EcmTransacao->save($transacao);
                    }

                }else{
                    $retorno['mensagem'] = __('Falha ao salvar transação!');
                }
            }else{

                if(isset($recorrencia)) {
                    $recorrencia->set('status', '0');
                    $this->EcmRecorrencia->save($recorrencia);
                }

                if(array_key_exists('Payment', $result)) {
                    $retorno['mensagem'] = 'Erro ' . $result['Payment']['ReturnCode'] . ': ' . $result['Payment']['ReturnMessage'];
                    $transacao->set('erro', $result['Payment']['ReturnMessage']);
                    $this->EcmTransacao->save($transacao);
                }else{
                    $retorno['mensagem'] = 'Erro '.$result[0]['Code'].': '.$result[0]['Message'];
                    $transacao->set('erro', $result[0]['Message']);
                    $this->EcmTransacao->save($transacao);
                }
            }
        }else{
            $retorno['mensagem'] = __('Falha na criação da transação!');
        }

        $linkEmail = "<a href='mailto:central@qisat.com.br'>central@qisat.com.br </a>";
        $mensagem = 'Ocorreu um erro ao iniciar uma transação com o pedido {0}, informe o suporte do site através do e-mail
                 '.$linkEmail.' ou entre em contato com a nossa central de vendas (48) 3332-5000.';

        if($this->request->is('post'))
            return $retorno;

        $this->Flash->error(__($mensagem, [$dados->MerchantOrderId]), ['params' => ['hiddenClick' => false]]);
        $this->Flash->error(__($retorno['mensagem']));

        return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
    }

    public function retorno()
    {
    }

    private function getDados($transacao, $recorrencia){
        $carrinho = $this->request->session()->read('carrinho');
        $dataCartao = $this->request->data('cartao');
        if(is_null($dataCartao)){
            $dataCartao = JWT::decode(
                $this->request->session()->read('info')
                , Security::salt(), array('HS256'));
            $dataCartao = get_object_vars($dataCartao);
            $this->request->data['cartao'] = $dataCartao;
        }

        $customer = (object)['Name' => $carrinho->mdl_user->firstname . ' ' . $carrinho->mdl_user->lastname];
        $creditCard = (object)[
            'CardNumber' => $dataCartao['numero'],
            'Holder' => $dataCartao['nome'],
            'ExpirationDate' => $dataCartao['mesSelect'].'/'.$dataCartao['anoSelect'],
            'SecurityCode' => $dataCartao['codigo'],
            'Brand' => $this->venda->ecm_operadora_pagamento->dataname
        ];

        $valorparcelas = array_key_exists('valorParcelas', $this->request->data) ? $this->request->data['valorParcelas'] : $dataCartao['valorparcelas'];

        $payment = (object)[
            'Type' => 'CreditCard',
            'Amount' => preg_replace("/[^0-9]/", "", number_format($carrinho->calcularTotal(), 2)),
            'Installments' => $valorparcelas,
            'SoftDescriptor' => '',
            'CreditCard' => $creditCard,
            "Capture" => true
        ];

        $merchantOrderId = $transacao->id;
        if($this->venda->ecm_operadora_pagamento->ecm_forma_pagamento->tipo == "cartao_recorrencia" &&
                !is_null($recorrencia)) {
            $merchantOrderId = $recorrencia->id;
            $payment->Amount = $carrinho->calcularParcela($valorparcelas, true);
            $payment->Amount = preg_replace("/[^0-9]/", "", $payment->Amount);
            $payment->Installments = 1;

            $endDate = new \DateTime();
            $endDate->modify("+" . --$valorparcelas . " month");
            $payment->RecurrentPayment = (object)[
                'AuthorizeNow' => 'true',
                'EndDate' => $endDate->format("Y-m-d")
            ];
            $payment->CreditCard->SaveCard = 'false';
        }

        $dados = (object)['MerchantOrderId' => $merchantOrderId,
            'Customer' => $customer, 'Payment' => $payment];

        return $dados;
    }

    private function setRecorrencia($result = null, $recorrencia = null){

        $carrinho = $this->request->session()->read('carrinho');
        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $numeroParcelas = $this->venda->get('numero_parcelas');
        $valor_parcelas = $carrinho->calcularParcela($numeroParcelas, true);

        if(is_null($result)){
            $recorrencia = $this->EcmRecorrencia->newEntity();
            $recorrencia->set('estabelecimento', $this->estabelecimento);
            $recorrencia->set('quantidade_cobrancas', $numeroParcelas);
            $recorrencia->set('numero_cobranca_restantes', $numeroParcelas);
            $recorrencia->set('ecm_venda', $this->venda);
            $recorrencia->set('ecm_tipo_pagamento', $tipoPagamento);
            $recorrencia->set('ecm_operadora_pagamento', $operadoraPagamento);
            $recorrencia->set('mdl_user', $usuario);
            $recorrencia->set('ip', $this->request->clientIp());
            $recorrencia->set('valor', $valor_parcelas);
            $recorrencia->set('data_primeira_cobranca', new \DateTime());
            $recorrencia->set('capturar', 'true');
            $recorrencia->set('status', 1);
        }else{
            $recorrencia->set('data_retorno', new \DateTime());

            if(isset($result['Payment']['RecurrentPayment'])){
                $returnRecorrencia = $result['Payment']['RecurrentPayment'];

                if(array_key_exists('RecurrentPaymentId', $returnRecorrencia))
                    $recorrencia->set('id_integracao', $returnRecorrencia['RecurrentPaymentId']);

                $recorrencia->set('mensagem_venda', $result['Payment']['ReturnMessage']);

                if ($result['Payment']['ReturnCode'] == 4 || $result['Payment']['ReturnCode'] == 6) {
                    $recorrencia->set('data_primeira_cobranca', $result['Payment']['ReceivedDate']);
                    $recorrencia->set('numero_cobranca_restantes', $numeroParcelas - 1);
                }
            }
        }

        return $this->EcmRecorrencia->save($recorrencia);
    }

    private function criarTransacao($transacao = null, $retornoRequisicao = null, $recorrencia = null){

        if(is_null($transacao)) {
            $transacao = $this->EcmTransacao->newEntity();
        }

        $carrinho = $this->request->session()->read('carrinho');

        $numeroParcelas = $this->venda->get('numero_parcelas');
        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $transacao->set('parcela', 1);

        if(is_null($retornoRequisicao)) {
            $transacao->set('estabelecimento', $this->estabelecimento);
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_operadora_pagamento', $operadoraPagamento);
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('ecm_transacao_status_id', 0);

            if(isset($recorrencia)){
                $valor_parcelas = $carrinho->calcularParcela($numeroParcelas, true);
                $transacao->set('valor', $valor_parcelas);
                $transacao->set('ecm_recorrencia_id', $recorrencia->id);
                $transacao->set('descricao', 'Recorrência Cielo API v3');
            } else{
                $transacao->set('valor', $carrinho->calcularTotal());
                $transacao->set('descricao', 'Cartão Cielo API v3');
            }

        }else{

            $transacao->set('data_retorno', new \DateTime());
            $transacao->set('data_campainha', new \DateTime());

            if(array_key_exists('Status', $retornoRequisicao))
                $transacao->set('ecm_transacao_status_id', $retornoRequisicao['Status']);

            if(array_key_exists('PaymentId', $retornoRequisicao))
                $transacao->set('id_integracao', $retornoRequisicao['PaymentId']);

            if(array_key_exists('Links', $retornoRequisicao) && count($retornoRequisicao['Links']) && (array_key_exists('Href', $retornoRequisicao['Links'])))
                $transacao->set('url', $retornoRequisicao['Links'][0]['Href']);

            if(array_key_exists('Tid', $retornoRequisicao))
                $transacao->set('tid', $retornoRequisicao['Tid']);

            if(array_key_exists('ReturnCode', $retornoRequisicao))
                $transacao->set('lr', $retornoRequisicao['ReturnCode']);//Motivo Negação

            if(array_key_exists('ProofOfSale', $retornoRequisicao))
                $transacao->set('nsu', $retornoRequisicao['ProofOfSale']);//Nº Sequência Autorização

            if(array_key_exists('AuthorizationCode', $retornoRequisicao))
                $transacao->set('arp', $retornoRequisicao['AuthorizationCode']);//Código Autorização

            if(array_key_exists('CapturedDate', $retornoRequisicao)){
                $transacao->set('capturar', 'true');
                $transacao->set('data_cobranca', $retornoRequisicao['CapturedDate']);
            } else {
                $transacao->set('capturar', 'false');
            }
        }

        return $this->EcmTransacao->save($transacao);
    }
    
    /** 
     *  Função Responśavel por retornar a mensagem do status da transação
     *  
     * @return String A mensagem
     * @param  Integer $code Codigo do status
     * 
     *   0	=> Aguardando atualização de status
     *   1	=> Pagamento apto a ser capturado ou definido como pago
     *   2	=> Pagamento confirmado e finalizado
     *   3	=> Pagamento negado por Autorizador
     *   10	=> Pagamento cancelado
     *   11	=> Pagamento cancelado após 23:59 do dia de autorização
     *   12	=> Aguardando Status de instituição financeira
     *   13	=> Pagamento cancelado por falha no processamento ou por ação do AF
     *   20	=> Recorrência agendada
     */
    public static function getMensagemStatusTransacao($code){
        switch($code){
            case 0: 
                return 'Aguardando atualização de status';
            case 1: 
                return 'Pagamento apto a ser capturado ou definido como pago';
            case 2: 
                return 'Pagamento confirmado e finalizado';
            case 3: 
                return 'Pagamento negado por Autorizador';
            case 10: 
                return 'Pagamento cancelado';
            case 11: 
                return 'Pagamento cancelado após 23:59 do dia de autorização';
            case 12: 
                return 'Aguardando Status de instituição financeira';
            case 13: 
                return 'Pagamento cancelado por falha no processamento ou por ação do AF';
            case 20: 
                return 'Recorrência agendada';
        }
    }

    /** 
     *  Função Responśavel por traduzir a mensagem de retorno
     *  
     * @return String A mensagem traduzida
     * @param  String $message Mensagem de Retorno Cielo

     */
    public static function getMensagemRetornoTransacao($message){
        switch($message){
            case 'Blocked Card': 
                return 'Cartão Bloqueado';
            case 'Card Expired': 
                return 'Cartão Expirado';
            case 'Not Authorized': 
                return 'Não Autorizado';
            case 'Timed Out': 
                return 'Tempo Expirado';
            case 'Card Canceled': 
                return 'Cartão Cancelado';
            case 'Problems with Creditcard': 
                return 'Problemas com o Cartão de Crédito';
            default:
                 return '';
        }
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

        $this->getMailer('FormaPagamentoCieloApi3.FormaPagamentoCieloApi3')->send('compraEfetuada', $params);

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $params = [$fromEmail, $adminEmail, $paramsEmail];
        $this->getMailer('FormaPagamentoCieloApi3.FormaPagamentoCieloApi3')->send('compraEfetuada', $params);

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
                        '.$this->venda->get('ecm_operadora_pagamento')->get('ecm_forma_pagamento')->get('nome').' <br/>
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