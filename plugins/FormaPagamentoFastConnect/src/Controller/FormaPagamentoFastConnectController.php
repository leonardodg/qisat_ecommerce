<?php

namespace FormaPagamentoFastConnect\Controller;


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

class FormaPagamentoFastConnectController extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;

    const LINK_PRODUCAO = 'https://api.fpay.me';
    const LINK_HOMOLOGACAO = 'https://api-sandbox.fpay.me';

    private $venda = null;
    public function initialize()
    {
        $this->loadModel('Entidade.EcmAlternativeHost');
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
            $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento'], 'EcmTipoPagamento' ])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $carrinho = $this->request->session()->read('carrinho');
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        
        if($ecmAlternativeHost = $this->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id)){

            $this->estabelecimento = $ecmAlternativeHost->shortname;
            $nomeKey = strtolower($ecmAlternativeHost->shortname).'_fast_connect_key';
            $nomeCode = strtolower($ecmAlternativeHost->shortname).'_fast_connect_code';

            if($ambienteProducao->valor == 1){
                $this->environment = 'prodution';
                $this->endPoint = self::LINK_PRODUCAO;
            }else{
                $this->environment = 'sandbox';
                $this->endPoint = self::LINK_HOMOLOGACAO;
                $nomeKey = 'teste_'.$nomeKey;
                $nomeCode = 'teste_'.$nomeCode;
            }

            $key = $this->EcmConfig->find()->where(['EcmConfig.nome' => $nomeKey])->first()->valor;
            $code = $this->EcmConfig->find()->where(['EcmConfig.nome' => $nomeCode])->first()->valor;

            $this->header = [ "Client-Code" => $code, "Client-key" => $key ];
        }

    }

    public function requisicao()
    {
        $retorno = [ 'sucesso' => false ];
        $http = new Client([ 'headers' => $this->header ]);

        if($trasacao = $this->setTransacao()){
            $dados = $this->getDados($trasacao);
            $carrinho = $this->request->session()->read('carrinho');

            try {
                $response = $http->post( $this->endPoint.'/credito', json_encode($dados), ['type' => 'json'] );
                $result = $response->json;
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição! ').$e->getMessage();
                return $retorno;
            }

            $this->setTransacao($trasacao, $result);

           if($response->isOk()){
                if(isset($result) && array_key_exists('success',  $result) && $result['success'] == true ){

                    $retorno = [
                        'sucesso' => true,
                        'mensagem' => __('Pagemento efetuado com sucesso'),
                        'venda' => $this->venda->id
                    ];

                    if($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)){
                        $carrinhoNovo = $carrinho->novaEntidadeComValores();
                        $this->EcmCarrinho->save($carrinhoNovo);

                        $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                        $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                        $this->EcmCarrinho->save($carrinhoNovo);
                        $this->request->session()->write('carrinho', $carrinhoNovo);
                    }

                    $vendaStatus = $this->EcmVenda->EcmVendaStatus->find()
                                                                ->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])
                                                                ->first();

                    $this->venda->set('ecm_venda_status', $vendaStatus);
                    $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                    $this->EcmVenda->save($this->venda);
                    $this->EcmCarrinho->save($carrinho);

                    $carrinho->ecm_alternative_host = $this->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id);

                    if($mail = $this->enviarEmailCompra($carrinho)){
                        $this->loadModel('MdlUser');
                        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
                        $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        $this->inserirRepasse($usuario, $carrinho,$mensagem);
                    }

                }else{
                    $retorno['mensagem'] =  __('Requisição sem sucesso!');
                }
            }else{
                $retorno['mensagem'] = __('Falha na Transação!!');
                if(isset($result) && array_key_exists('success',  $result) && $result['success'] == false ){
                    if(array_key_exists('errors',  $result) && isset( $result['errors'][0]) ){

                        if(array_key_exists('fields',  $result['errors'][0])){
                            foreach ($result['errors'][0]['fields'] as $msg) {
                                $retorno['mensagem'] .= $msg;
                            }
                        }else{
                            $retorno['mensagem'] = $result['errors'][0]['code'] . '-'. $result['errors'][0]['message'];
                        }
                    }
                }
            }
        }

        $referer = substr($this->referer(), 0, strpos($this->referer(), "/", 8)+1);
        $link_ecommerce = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'link_ecommerce'])->first()->valor;

        if (strpos($referer, $link_ecommerce) !== false) {
            if(!$retorno['sucesso'])
                return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);

            $usuario = $carrinho->get('mdl_user');
            $venda = $this->venda;

            $this->set(compact('usuario','venda'));
            $this->set('_serialize', ['usuario','venda']);

            $this->render("confirmarcompra");
        }else 
            return $retorno;
    }

    public function retorno()
    {
        return;
    }

    private function consulta($id)
    {
        $http = new Client([ 'headers' => $this->header ]);

        try {
            $response = $http->get( $this->endPoint.'/credito\/'.$id, ['type' => 'json'] );
        } catch (\Exception $e) {
            return false;
        }

        return ($response->isOk()) ? $response->json : false;
    }

    public function cancelar()
    {
        
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $retorno = ['sucesso' => false ];
        $http = new Client([ 'headers' => $this->header ]);
        $id = $this->request->data('id');

        if($this->request->is('post') and !is_null($id)){

            try {

                $transacao = $this->EcmTransacao->get($id);
                if($transacao){
                    $response = $http->delete( $this->endPoint.'/credito\/'.$id.'/estornar', ['type' => 'json'] );
                    $result = $response->json;
                }
    
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição Cancelar!');
            } catch (RecordNotFoundException $e) {
                $retorno['mensagem'] = __('Transação não Encontrada!');
            }

            if($response){
                if($response->isOk()){
                    $transacao->set('ecm_transacao_status_id', 3); //estorno
                    $retorno['sucesso'] = true;
    
                    $this->EcmTransacao->save($transacao);
                }else{
                    $retorno['result'] = $result;
                }
            }
        }

        $this->set(compact('retorno'));
    }

    private function setTransacao($transacao = null, $result = null){

        if(is_null($transacao)){
            $transacao = $this->EcmTransacao->newEntity();
        }else{
            try {
                $transacao = $this->EcmTransacao->get( $transacao->id );
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
        $ecmAlternativeHost = $this->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id);

        if(is_null($result)) {
            $transacao->set('descricao', 'Cartão FastConnect');
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_operadora_pagamento', $operadoraPagamento);
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('valor', $valorVenda);
            $transacao->set('ecm_transacao_status_id', 2); //aguardando_pagamento
            $transacao->set('estabelecimento', $this->estabelecimento );
        }else{

            $transacao->set('data_retorno', new \DateTime());

            if(isset($result)){
                if( array_key_exists('success',  $result) && $result['success'] == true ){

                    if( array_key_exists('data',  $result) && $result['data']['situacao'] == 'Pago' ){
                        $transacao->set('data_cobranca', new \DateTime());
                        $transacao->set('ecm_transacao_status_id', 7); // Paga
                        $transacao->set('id_integracao', $result['data']['fid']);
                    }else{
                        $transacao->set('ecm_transacao_status_id', 4); // erro
                        $transacao->set('erro', 'Retorno Situacao não Pago'); // erro
                    }

                }else if( array_key_exists('success',  $result) && $result['success'] == false ){
                    $transacao->set('ecm_transacao_status_id', 6); // negada

                    if( array_key_exists('errors',  $result)  ){
                        $transacao->set('erro', $result['errors'][0]['code'].'-'.$result['errors'][0]['message']); 
                    }else{
                        $transacao->set('erro', 'Sem dados de retorno!'); 
                    }

                }else{
                    $transacao->set('ecm_transacao_status_id', 4); // erro
                    $transacao->set('erro', 'Sem dados de retorno!'); 
                }
            }else{
                $transacao->set('ecm_transacao_status_id', 4); // erro
                $transacao->set('erro', 'Falha na requisisão!');
            }
        }

        return $this->EcmTransacao->save($transacao);
    }

    private function getDados($transacao){

        $documento = '03984954000174';
        $nome = 'QiSat MN';
        $phone = '4833325000';
        $email = 'qisat@qisat.com.br';
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

        $dataCartao = $this->request->data('cartao');
        if(is_null($dataCartao)){
            $dataCartao = JWT::decode(
                $this->request->session()->read('info')
            , Security::salt(), array('HS256'));
            $dataCartao = get_object_vars($dataCartao);
        }

        if(!is_null($transacao)){

            $carrinho = $this->request->session()->read('carrinho');
            $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
            $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
            $formaPagamento = $operadoraPagamento->get('ecm_forma_pagamento');
            $valor_parcelas = $this->venda->get('valor_parcelas');
            $numeroParcelas = $this->venda->get('numero_parcelas');
            $valorVenda = $carrinho->calcularTotal();

            $dados['nu_referencia'] = $transacao->id;
            $dados['url_retorno'] = $url_retorno;

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
                $nome = $usuario->get('firstname').' '.$usuario->get('lastname');
                $documento = preg_replace("/[^0-9]/", "", $usuario->get('cpf'));
                $email = (!empty($usuario->get('email'))) ? $usuario->get('email') : $email ;
                $phone = (!empty($usuario->get('phone1'))) ? preg_replace("/[^0-9]/", "", $usuario->get('phone1')) : $phone ;
            }

            $dados['nm_cliente'] = $nome;
            $dados['nu_documento'] = $documento;
            $dados['nu_telefone'] = $phone;
            $dados['ds_email'] = $email;

            $dados['vl_total'] = number_format($valorVenda, 2, '.', '');
            $dados['nu_parcelas'] = $numeroParcelas;

            /* 
                Os tipos são: 
                    - AV = (A Vista)
                    - PB = (Parcelado pelo banco)
                    - PL = (Parcelado pela loja)
                    - AS = (Assinatura)
            */
            $dados['tipo_venda'] =  "AV";
            $dados["ds_softdescriptor"] = "MN TECNOLOGIA"; // nome na fatura
            $dados['tp_capturar'] =  true;
            /* Efetuar antecipação automática desta transação, esta funcionalidade depende da liberação prévia da FastConnect, Entre em contato para liberar. Opções de antecipaçõa 1 = (O vendedor assume todas as taxas), 2 = (O Cliente assume a taxa de antecipação), 3 = (O Cliente assume todas as taxas) */
            if($this->environment == 'prodution'){ // PROBLEMA NO SERVIÇO FASTCONNECT - ISSO SO FUNCIONA EM PRODUÇÃO
                $dados['tp_antecipacao'] =  1;
            }
            $dados['nm_bandeira'] = $operadoraPagamento->dataname; 
            $dados['nm_titular'] = $dataCartao['nome'];
            $dados['nu_cartao'] = $dataCartao['numero'];
            $dados['dt_validade'] = $dataCartao['mesSelect'].'/'. substr($dataCartao['anoSelect'], -2); ;

        }
        
        return $dados;
    }

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');
        $this->loadModel('Entidade.EcmAlternativeHost');
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('Carrinho.EcmCarrinho');

        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
        $aesHash = new AESPasswordHasher();
        $senha = $aesHash->decrypt($usuario->get('password'));

        $list_servicos = [];
        $list_produtos = [];

        $carrinho = $this->EcmCarrinho->get($carrinho->id,  [ 'contain' => [  
                                                                            'MdlUser' => ['MdlUserEcmAlternativeHost' => ['EcmAlternativeHost']],
                                                                              'EcmCarrinhoItem' => [ 'EcmProduto' => ['EcmTipoProduto'], 
                                                                              'EcmCarrinhoItemEcmProdutoAplicacao' => ['EcmProdutoEcmAplicacao' => ['EcmProduto', 'EcmProdutoAplicacao']], 'EcmCarrinhoItemMdlCourse' ]
                                                            ]]);
       if( $carrinho ){
            foreach($carrinho->ecm_carrinho_item as $item){
                if($item->status == 'Adicionado'){

                    $item_produto = $item->get('ecm_produto');
                    $item_apps = $item->get('ecm_carrinho_item_ecm_produto_aplicacao');
                    $item_cursos = $item->get('ecm_carrinho_item_mdl_course');
                    $produtoAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 48; }); // produto AltoQi
                    $pacoteAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 58; }); // produto AltoQi
                    $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setProductsInCourse($item);

                    if(count($pacoteAltoQi) > 0){
                        $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setAppsInPackageAltoQi($item);
                    }

                    if( count($item_cursos) == 0  && count($produtoAltoQi) == 0 ) {
                        array_push($list_servicos, $item);
                    }else if(count($item_cursos) > 0){
                        foreach( $item->course_products as $item_curso ){
                            array_push($list_servicos, $item_curso);
                        }
                    }
                    
                    if(count($item_apps) > 0 ){
                        foreach($item_apps as $item_app){
                            $ecm_produto_ecm_app = $item_app->get('ecm_produto_ecm_aplicacao');
                            $app_produto = $ecm_produto_ecm_app->get('ecm_produto');
                            $item_app->quantidade = $item->quantidade;
                            array_push($list_produtos, $item_app);
                        }
                    }
                }
            }
        }

        $paramsEmail = [
            'usuario' => $carrinho->get('mdl_user'),
            'senha' => $senha,
            'produtos' => $list_produtos,
            'servicos' => $list_servicos,
            'pedido' => $this->venda->id,
            'valor' => number_format($this->venda->valor_parcelas, 2, ',', ''),
            'parcelas' => $this->venda->numero_parcelas
        ];

        $ecmAlternativeHost = $this->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id);

        $params = [$carrinho->get('mdl_user')->get('email'), $paramsEmail];
        $this->request->session()->delete('compraConfirmada');

        if($ecmAlternativeHost->shortname == 'AltoQi'){
            return $this->getMailer('FormaPagamentoFastConnect.FormaPagamentoFastConnect')->send('compraEfetuadaAltoQi', $params);
        }else{
            return $this->getMailer('FormaPagamentoFastConnect.FormaPagamentoFastConnect')->send('compraEfetuadaQiSat', $params);
        }
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

