<?php

namespace FormaPagamentoSuperPayV3\Controller;


use ADmad\JwtAuth\Auth\JwtAuthenticate;
use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
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

class FormaPagamentoSuperPayV3Controller extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;
    // use Client;
    const LINK_PRODUCAO = 'https://gateway.yapay.com.br/checkout/api/v3/transacao';
    const LINK_HOMOLOGACAO = 'https://sandbox.gateway.yapay.com.br/checkout/api/v3/transacao';

    private $venda = null;
    public function initialize()
    {
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('MdlUser');

        parent::initialize();
        $this->configuracao();
    }

    public function beforeFilter(Event $event)
    {
        $carrinho = $this->request->session()->read('carrinho');

        if(!is_null($carrinho)){
            $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento'], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->environment = 'prodution';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
            $this->endPoint = self::LINK_PRODUCAO;
        }else{
            $this->environment = 'sandbox';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_estabelecimento_qisat_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_yapay_teste'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_yapay_teste'])->first()->valor;
            $this->endPoint = self::LINK_HOMOLOGACAO;
        }

        $this->auth = [ "username" => $login, "password" => $senha ];
    }

    public function requisicao()
    {
        $retorno = [ 'sucesso' => false ];
        $http = new Client();

        if($trasacao = $this->setTransacao()){
            $dados = $this->getDados($trasacao);
            $carrinho = $this->request->session()->read('carrinho');

            try {
                $response = $http->post( $this->endPoint, json_encode($dados), ['type' => 'json', 'auth' =>  $this->auth ] );
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição!! ').$e->getMessage();
                return $retorno;
            }

           if($response->isOk()){
                $result = $response->json;

                if($result['numeroTransacao']){

                    $this->setTransacao($result);

                    if($this->request->is('post')){
                        return ['sucesso' => true, 'mensagem' => __('Pagamento no cartão gerado com sucesso'),
                            'venda' => $this->venda->id,
                            'url' => $result['urlPagamento']];
                    }

                    if(!$this->request->session()->check('url_requisicao'))
                        $this->request->session()->write('url_requisicao', $this->referer());
    
                    return $this->redirect($result['urlPagamento']);

                }else
                     $retorno['mensagem'] =  __('Sem dados da recorrência no retorno!');
            }
        }

        return $retorno;
    }

    public function retorno($numero = null)
    {
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $carrinho = $this->request->session()->read('carrinho');
        $link_site = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_site'])->first()->valor;
        $referer  =$this->request->session()->read('url_requisicao');
        $return_site = true;

        if($referer){
            $referer = substr($referer, 0, strpos($referer, "/", 8)+1);
            $return_site = (strpos($referer, $link_site) === false) ? false : true;
        }
        
        if($return_site && is_null($numero)){
                $numeroTransacao = $this->request->data('numeroTransacao');
        } else if(!is_null($numero)){
            $numeroTransacao = $numero;
        } else{
            $ecmTransacao = $this->EcmTransacao->find('all', [
                                                                    'contain' => [
                                                                        'EcmVenda' => ['EcmCarrinho']
                                                                    ]
                                                    ])
                                                ->where([
                                                    'EcmCarrinho.id' => $carrinho->get('id')
                                                ])
                                                ->orderDesc('EcmTransacao.id')
                                                ->first();
            if($ecmTransacao){
                $numeroTransacao = $ecmTransacao->get('id');
            }
        }
        
        $transacao = null;
        $retorno = ['sucesso' => false ];

        try {
            $transacao = $this->EcmTransacao->get($numeroTransacao);
        } catch (RecordNotFoundException $e) {
            if(!is_null($numeroTransacao)){
                if (strpos($referer, $link_site) == false)
                    return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
                else
                    $retorno['mensagem'] = __('Transação não encontrada');
            }
        }


        if(!is_null($transacao)) {

            $result = $this->consulta($this->estabelecimento,$numeroTransacao);
            $transacao->set('data_retorno', new \DateTime());

            if ($result and ($result['statusTransacao'] == 1 or $result['statusTransacao'] == 2)) {
                    
                    $transacao->set('data_cobranca', new \DateTime());
                    $transacao->set('nsu', $result['nsu'] );
                    $transacao->set('tid', $result['numeroComprovanteVenda'] );
                    $transacao->set('arp', $result['autorizacao'] );

                    if($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)){
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

                    $transacao->set('ecm_transacao_status_id', $result['statusTransacao']);

                    $this->EcmTransacao->save($transacao);

                    $retorno = ['sucesso' => true, 'mensagem' => __('Transação efetivada com sucesso')];
                    
                    if($mail = $this->enviarEmailCompra($carrinho)){
                        $this->loadModel('MdlUser');
                        $usuario = $this->MdlUser->get($carrinho->mdl_user_id);
                        $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        $this->inserirRepasse($usuario, $carrinho, $mensagem);
                    }

            }else if($result){

                if( array_key_exists('statusTransacao', $result)){
                    $retorno[ 'mensagem'] = $result['statusTransacao'];
                }

                if( array_key_exists('numeroComprovanteVenda',$result)){
                    $transacao->set('tid', $result['numeroComprovanteVenda']);
                }

                if( array_key_exists('statusTransacao', $result)){
                    $transacao->set('ecm_transacao_status_id', $result['statusTransacao']);
                    $this->request->session()->write('error_transacao', $result['statusTransacao']);
                }

                if( array_key_exists('codigoTransacaoOperadora',$result)) {
                    $transacao->set('lr', $result['codigoTransacaoOperadora']);
                    $transacao->set('erro', $transacao->getMsgErrorOperadora($result['codigoTransacaoOperadora']));
                }
                
                $this->EcmTransacao->save($transacao);
                $retorno['mensagem'] = $transacao->getMensagemV3($transacao->ecm_transacao_status_id);
            }
        }

        if (strpos($referer, $link_site) === false){
            if($retorno['sucesso']){
                $mensagem = $transacao->getMensagemV3($transacao->ecm_transacao_status_id);
                $this->Flash->success(__($mensagem));

                $usuario = $carrinho->get('mdl_user');
                $venda = $this->venda;

                $this->set(compact('usuario','venda'));
                $this->set('_serialize', ['usuario','venda']);

                $this->render("confirmarcompra");
            }else{
                $this->Flash->error($retorno['mensagem']);
                return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
            }
        }else
            return $retorno;
    }

    /**
    *   @param $estabelecimento => numero do estabelecimento
    *                $id => código de identificação da recorrencia
    */
    private function consulta($estabelecimento, $id)
    {
        $url = $this->endPoint.'/'.$this->estabelecimento.'/'.$id;
        $http = new Client(['type' => 'json', 'auth' =>  $this->auth ]);
        $response = $http->get( $url, [] );
        return ($response->isOk()) ? $response->json : false;
    }

    public function cancelar()
    {
                
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $retorno = ['sucesso' => false ];
        $http = new Client();
        $id = $this->request->data('id');

        if($this->request->is('post') and !is_null($id)){

            try {

                $transacao = $this->EcmTransacao->get($id);
                if($transacao){
                    $response = $http->put( $this->endPoint.'/'.$this->estabelecimento.'/'.$transacao->id_integracao.'/cancelar', json_encode([]),['type' => 'json', 'auth' =>  $this->auth ]); // 
                    $result = $response->json;  
                }
    
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição Cancelar!');
            } catch (RecordNotFoundException $e) {
                $retorno['mensagem'] = __('Recorreência não Encontrada!');
            }

            if($response){
                if($response->isOk()){
                    $transacao->set('ecm_transacao_status_id', '13');
                    $retorno['sucesso'] = true;
    
                    $this->EcmTransacao->save($transacao);
                }else{
                    $retorno['result'] = $result;
                }
            }
        }

        $this->set(compact('retorno'));
    }

    private function setTransacao($response = null){
        if(is_null($response)) 
            $transacao = $this->EcmTransacao->newEntity();
        else{
            try {
                $transacao = $this->EcmTransacao->find()->where(['id' => $response['numeroTransacao']])->first();
            } catch (RecordNotFoundException $e) {
                return false;
            }
        }

        $carrinho = $this->request->session()->read('carrinho');
        
        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $valorVenda = $this->venda->get('valor_parcelas');
        $numeroParcelas = $this->venda->get('numero_parcelas');
        $valorVenda = $carrinho->calcularTotal();

        $transacao->set('estabelecimento', $this->estabelecimento );

        if(is_null($response)) {
	        $transacao->set('estabelecimento', $this->estabelecimento );
            $transacao->set('descricao', 'Cartão SuperPay v3');
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_operadora_pagamento', $operadoraPagamento);
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('valor', $valorVenda);
            $transacao->set('ecm_transacao_status_id', '5');
        }else{
            $transacao->set('id_integracao', $transacao->id);
            $transacao->set('ecm_transacao_status', $response['statusTransacao']);
            $transacao->set('url', $response['urlPagamento']);
        }

        return $this->EcmTransacao->save($transacao);
    }

    /*
        tipoPagamento
            0 - Todas formas de pagamento; 1 - Cartões de Crédito; 2 - Cartões de Débito; 3 - Boleto; 4 - Transferência

    */
    private function getDados($transacao){

        $documento = '12345678900';
        $nome = 'QiSat MN';
        $dados = [];

        if($this->request->is('post')){
            $url_retorno = \Cake\Routing\Router::url(['plugin' => false,'controller' => 'pagamento'], true);
            $jwt = new JwtAuthenticate($this->_components, []);
            $url_retorno .=  '?token='.$jwt->getToken($this->request);
        }else {
            $url_retorno = \Cake\Routing\Router::url(['controller' => '', 'action' =>'retorno'], true);
        }

        $url_retorno = str_replace('http://', 'https://', $url_retorno);
        $url_retorno = urldecode($url_retorno);

        if(!is_null($transacao)){

            $carrinho = $this->request->session()->read('carrinho');
            $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
            $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
            $formaPagamento = $operadoraPagamento->get('ecm_forma_pagamento');
            $valor_parcelas = $this->venda->get('valor_parcelas');
            $numeroParcelas = $this->venda->get('numero_parcelas');
            $valorVenda = $carrinho->calcularTotal();

            $dados['codigoEstabelecimento'] = $this->estabelecimento;
            $dados['codigoFormaPagamento'] = intval($operadoraPagamento->dataname);
            $dados['transacao']['numeroTransacao'] = intval($transacao->id);
            $dados['transacao']['valor'] = intval(number_format($valorVenda, 2, '', ''));
            $dados['transacao']['parcelas'] = $numeroParcelas;
            $dados['transacao']['urlResultado'] = $url_retorno;

            $usuario = $carrinho->get('mdl_user');
            $usuario = $this->MdlUser->find()
                                     ->select([
                                        'MdlUser.id', 'username', 'idnumber', 'firstname', 'lastname', 'email', 'phone1', 'phone2'
                                    ])
                                    ->contain(
                                        [
                                            'MdlUserDados' => [
                                                'fields' => [
                                                    'cpf' => 'numero', 'crea' => 'numero_crea',
                                                    'tipousuario' => 'tipousuario', 'funcionarioqisat' => 'funcionarioqisat'
                                                ]
                                            ]
                                        ]
                                    )
                                    ->where(['MdlUser.id' => $usuario->get('id') ])
                                    ->first();

            if($usuario){
                $documento = preg_replace("/[^0-9]/", "", $usuario->get('cpf'));
                $nome = $usuario->get('firstname').' '.$carrinho->get('mdl_user')->get('lastname');
            }

            foreach($carrinho->get('ecm_carrinho_item') as $item){
                $aux = [];
                $aux['quantidadeProduto'] = $item->quantidade;
                $aux['valorUnitarioProduto'] =  intval(number_format($item->valor_produto_desconto, 2, '', ''));
                $dados['itensDoPedido'][] = $aux;
            }

            $dados['checkout']['processar'] = 0;
            $dados['checkout']['tipoPagamento'] = 0;
            $dados['dadosCobranca']['nome'] = $nome;
            $dados['dadosCobranca']['documento'] = $documento;
        }
        
        return $dados;
    }

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');

        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
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

        $params = [$carrinho->get('mdl_user')->get('email'), $paramsEmail];
        $this->request->session()->delete('compraConfirmada');
        return $this->getMailer('FormaPagamentoSuperPayV3.FormaPagamentoSuperPayV3')->send('compraEfetuada', $params);
    }

    private function inserirRepasse($usuario, $carrinho, $mensagem){
        $this->loadModel('Repasse.EcmRepasse');

        $repasse = $this->EcmRepasse->newEntity();
        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
        $repasse->set('assunto_email', 'QiSat | Confirmação de Pedido');
        $repasse->set('corpo_email',$mensagem);
        $repasse->set('ecm_alternative_host_id', $carrinho->get('ecm_alternative_host_id'));
        $repasse->set('data_registro', new \DateTime());

        if(!is_null($usuario))
            $repasse->set('mdl_user_cliente_id', $usuario->id);

        if($ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()->where(['LOWER(origem)' => 'Site QiSat'])->first())
            $repasse->set('ecm_repasse_origem_id', $ecmRepasseOrigem->id);

        if($ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()->where(['LOWER(categoria)' => 'Compra Efetuada'])->first())
            $repasse->set('ecm_repasse_categorias_id', $ecmRepasseCategoria->id);

        $this->EcmRepasse->save($repasse);
    }

}

