<?php

namespace FormaPagamento\Controller;

use App\Controller\WscController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Routing\Router;
use Carrinho\Controller\EcmCarrinhoController;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use FormaPagamento\Model\Entity\EcmFormaPagamento;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGateway;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGatewayConfig;
// use Produto\Criterio\Criterio;
use Produto\Model\Entity\EcmTipoProduto;
use Vendas\Model\Entity\EcmVendaStatus;
use Entidade\Model\Entity\EcmAlternativeHost;

class WscFormaPagamentoController extends WscController implements FormaPagamentoAbstractController
{
    private $event;

    public function initialize()
    {
        parent::initialize();
        $this->configuracao();
    }
    public function beforeFilter(Event $event)
    {
        $this->event = $event;

        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');

        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();

        $environment = 'sandbox';
        $token = null;
        if($ambienteProducao->valor == 1){
            $environment = 'production';
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'token_super_pay'])->first();
        }else{
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_token_super_pay'])->first();
        }

        LocawebGatewayConfig::setEnvironment($environment);
        LocawebGatewayConfig::setToken($token->valor);
    }

    /*
    * Função responsável por validar o acesso do usuario no host
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Entidade não encontrada'}
    * 3- {'sucesso':false, 'mensagem': 'O usuario não se encontra associado a este CREA'}
    * 3- {'sucesso':false, 'mensagem': 'O usuario encontra-se inadimplemente neste CREA'}
    * 4- {'sucesso':false, 'mensagem': 'O usuario não foi confirmado neste CREA'}
    *
    * */
    private function verificarAcesso(){
        $retorno = $this->validaAlternativeHost();
        if(!is_array($retorno)){
            if($retorno == 1) {
                $retorno = ['sucesso' => true];
            } else {
                $user = $this->request->session()->read('carrinho')->get('mdl_user_id');
                $this->loadModel('Entidade.MdlUserEcmAlternativeHost');
                $ecmAlternativeHost = $this->MdlUserEcmAlternativeHost->find()
                    ->where(['mdl_user_id' => $user, 'ecm_alternative_host_id' => $retorno])->first();

                if (!isset($ecmAlternativeHost))
                    $retorno = ['sucesso' => false, 'mensagem' => 'O usuario não se encontra associado a este CREA'];

                $this->loadModel('Configuracao.EcmConfig');
                $ecmConfig = $this->EcmConfig->find('list', ['keyField' => 'nome', 'valueField' => 'valor'])
                    ->where(['nome LIKE "vender_crea_%"'])->toArray();

                $adimplente = 0;
                $confirmado = 0;
                if ((isset($ecmConfig['vender_crea_inadimplente']) && $ecmConfig['vender_crea_inadimplente'] == 1) ||
                    (isset($ecmAlternativeHost->adimplente) && $ecmAlternativeHost->get('adimplente') == 1)){
                    $adimplente = 1;
                }
                if ((isset($ecmConfig['vender_crea_nao_confirmado']) && $ecmConfig['vender_crea_nao_confirmado'] == 1) ||
                    (isset($ecmAlternativeHost->confirmado) && $ecmAlternativeHost->get('confirmado') == 1)){
                    $confirmado = 1;
                }
                if (!$adimplente) {
                    echo json_encode(['sucesso' => false, 'mensagem' => 'O usuario encontra-se inadimplemente neste CREA.']);
                } else if (!$confirmado) {
                    echo json_encode(['sucesso' => false, 'mensagem' => 'O usuario não foi confirmado neste CREA.']);
                } else {
                    echo json_encode(['sucesso' => true]);
                }
            }
        }

        return $retorno;
    }

    /*
    * Função responsável por listar todas as opções de forma de pagamento validas
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/forma-pagamento/wsc-forma-pagamento/formas/{valor total da compra}
    *
    * Retornos:
    * 1- {'sucesso':true, 'formas': lista de formas de pagamento, {formas}: lista de operadoras, tipos e parcelas de pagamento}
    * 2- {'sucesso':false, 'mensagem': 'Carrinho Vazio'}
    *
    * */
    public function formas(){
        $retorno = $this->verificarAcesso();

        $retorno['false'] = false;

        if($retorno['sucesso'] == true){
            $retorno = ['sucesso' => false, 'mensagem' => __('Carrinho Vazio')];
            $carrinho = $this->request->session()->read('carrinho');

            if (!is_null($carrinho->ecm_carrinho_item)) {
                $this->loadModel('FormaPagamento.EcmFormaPagamento');
                $this->loadModel('Produto.EcmProduto');

                $idProduto = $this->request->data('produto');

                $produto = null;
                if(!is_null($idProduto)) {
                    try {
                        $produto = $this->EcmProduto->get($idProduto, [
                            'contain' => ['EcmTipoProduto']
                        ]);
                    }catch(RecordNotFoundException $e){
                        $produto = null;
                    }
                }

                //Alterar status dos itens
                $itensParaPagamento = $this->verificarItens($carrinho, $produto);
                $tipos = [];
                $where = [ 'habilitado' => 'true'];

                foreach($carrinho->get('ecm_carrinho_item') as $item){
                    $tipoproduto = null;
                    $produto = $item->ecm_produto;
                    if($item->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO) {
                        foreach ($produto->ecm_tipo_produto as $ecm_tipo_produto) {
                            array_push($tipos, $ecm_tipo_produto->id);
                        }
                    }
                }

                if(!is_null($itensParaPagamento)) {

                    $alternativeHostId = $carrinho->ecm_alternative_host_id;

                    if(count($tipos) == 0){
                        $ecmFormaPagamento = $this->EcmFormaPagamento->find('all')
                                                    ->matching( 'EcmAlternativeHost' , function ($e) use($alternativeHostId){
                                                        return $e->where(['EcmAlternativeHost.id' => $alternativeHostId]);
                                                    })
                                                    ->contain(['EcmOperadoraPagamento' => function ($e) {
                                                        return $e->where(['EcmOperadoraPagamento.habilitado' => 'true'])->contain(['EcmImagem']);
                                                    }, 'EcmTipoPagamento' => function ($e) {
                                                        return $e->where(['EcmTipoPagamento.habilitado' => 'true']);
                                                    }, 'EcmAlternativeHost'
                                                    ])->where([ 'EcmFormaPagamento.habilitado' => 'true', 'EcmFormaPagamento.tipo !=' => 'cartao_recorrencia'])
                                                        ->group(['EcmFormaPagamento.id'])
                                                        ->toArray();

                    }else{
                        $ecmFormaPagamento = $this->EcmFormaPagamento->find('all')
                                                    ->matching( 'EcmAlternativeHost' , function ($e) use($alternativeHostId){
                                                        return $e->where(['EcmAlternativeHost.id' => $alternativeHostId]);
                                                    })
                                                    ->matching( 'EcmTipoProduto' , function ($e) use($tipos){
                                                                            return $e->where(['EcmTipoProduto.id IN' => $tipos]);
                                                                        })
                                                    ->contain(['EcmOperadoraPagamento' => function ($e) {
                                                        return $e->where(['EcmOperadoraPagamento.habilitado' => 'true'])->contain(['EcmImagem']);
                                                    }, 'EcmTipoPagamento' => function ($e) {
                                                        return $e->where(['EcmTipoPagamento.habilitado' => 'true']);
                                                    }
                                                    ])->where([ 'EcmFormaPagamento.habilitado' => 'true'])
                                                        ->group(['EcmFormaPagamento.id'])
                                                        ->toArray();
                    }
                    
                    $retorno['sucesso'] = true;

                    foreach ($ecmFormaPagamento as $formaPagamento) {
                        $this->EcmCarrinhoController = new EcmCarrinhoController();
                        $parcelasArray = $this->EcmCarrinhoController->calcularValorParcelas($formaPagamento['id']);

                        $retorno['formas'][$formaPagamento->id]['pagamento'] = $formaPagamento->nome;
                        $retorno['formas'][$formaPagamento->id]['dataname'] = $formaPagamento->dataname;
                        $retorno['formas'][$formaPagamento->id]['controller'] = $formaPagamento->controller;

                        $retorno['formas'][$formaPagamento->id]['tipo'] = $formaPagamento->tipo;
                        foreach ($formaPagamento->ecm_operadora_pagamento as $operadoras) {
                            if(!is_null($operadoras->ecm_imagem)){
                                $operadoras->ecm_imagem->src = Router::url( 'upload/'. $operadoras->ecm_imagem->src, true);
                                $retorno['formas'][$formaPagamento->id]['operadoras'][$operadoras->id] = $operadoras->ecm_imagem;
                            }
                        }
                        foreach ($formaPagamento->ecm_tipo_pagamento as $tipos) {
                            $retorno['formas'][$formaPagamento->id]['tipos'][$tipos->id] = $tipos->nome;
                        }
                        $retorno['formas'][$formaPagamento->id]['parcelas'] = $parcelasArray;
                    }
                }else{
                    $retorno = ['sucesso' => false, 'mensagem' => __('produto para pagamento não selecionado')];
                }
            }
        }else{
            $retorno['false'] = true;
        }

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por verificar os itens que serão pagos, alterando os status
    *
    * @param EcmCarrinho $carrinho
    * @param EcmProduto $produto
    *
    * @return array ou EcmProduto
    * */
    private function verificarItens($carrinho, $produto){
        $this->loadModel('Carrinho.EcmCarrinho');

        $itensParaPagamento = [];
        $isAltoQiLab = false;
        $idProduto = null;

        if(!is_null($produto)) {
            $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();
            $item->set('ecm_produto', $produto);
            $idProduto = $produto->get('id');

            if($carrinho->existeItem($item))
                $isAltoQiLab = EcmTipoProduto::verificarTipoProduto($produto->get('ecm_tipo_produto'), 47);
        }

        foreach($carrinho->get('ecm_carrinho_item') as $itemCarrinho){

            $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($itemCarrinho->get('ecm_produto_id'), [
                    'contain' => ['EcmTipoProduto']
                ]);

            if(!is_null($idProduto) && $isAltoQiLab) {

                if( $itemCarrinho->get('ecm_produto_id') == $idProduto){
                    if($itemCarrinho->get('status') == EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO){
                        $itemCarrinho->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                        $carrinho->addItem($itemCarrinho);
                        $this->EcmCarrinho->EcmCarrinhoItem->save($itemCarrinho);
                    }
                    $itensParaPagamento[] = $itemCarrinho;
                }else if($itemCarrinho->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO){
                    $itemCarrinho->set('status', EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                    $carrinho->addItem($itemCarrinho);
                    $this->EcmCarrinho->EcmCarrinhoItem->save($itemCarrinho);
                }

            }else{
                $checkAltoQiLab = EcmTipoProduto::verificarTipoProduto($produto->get('ecm_tipo_produto'), 47);
                if($itemCarrinho->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO){

                    if($checkAltoQiLab){
                        $itemCarrinho->set('status', EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                        $carrinho->addItem($itemCarrinho);
                        $this->EcmCarrinho->EcmCarrinhoItem->save($itemCarrinho);
                    }else{
                         $itensParaPagamento[] = $itemCarrinho;
                    }

                }else if($itemCarrinho->get('status') == EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO){
                    if(!$checkAltoQiLab){
                         $itemCarrinho->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                        $carrinho->addItem($itemCarrinho);
                        $this->EcmCarrinho->EcmCarrinhoItem->save($itemCarrinho);
                        $itensParaPagamento[] = $itemCarrinho;
                    }
                }
            }
        }

        return (count($itensParaPagamento) == 0) ? null : $itensParaPagamento;
    }

    private function verificarPagamentoRecorrente($produto){
        $this->loadModel('FormaPagamento.EcmFormaPagamento');
        $listaTiposFormaPagamento = $this->EcmFormaPagamento
            ->listarTipoProdutoPorTipoFormaPagamento(EcmFormaPagamento::TIPO_CARTAO_RECORRENCIA);

        foreach($listaTiposFormaPagamento  as $tipo){
            if(EcmTipoProduto::verificarTipoProduto($produto->get('ecm_tipo_produto'), $tipo->get('id')))
                return $tipo->get('id');
        }
        return false;
    }
    /*
    * Função responsável por criar a requisição de pagamento do carrinho
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
    * http://{host}/forma-pagamento/wsc-forma-pagamento/requisicao
    *
    * Retornos:
    * 1- {'sucesso':true, 'mensagem': 'Pagamento gerado com sucesso', 'url': 'Direcionamento para pagamento'}
    * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessária'}
    * 3- {'sucesso':false, 'mensagem': 'Para continuar com o processo de compra você deve aceitar o contrato'}
    * 4- {'sucesso':false, 'mensagem': 'Por favor selecione a forma de pagamento'}
    * 5- {'sucesso':false, 'mensagem': 'Operadora não encontrada'}
    * 6- {'sucesso':false, 'mensagem': 'Tipo de pagamento não encontrado'}
    * 7- {'sucesso':false, 'mensagem': 'Numero de parcelas incorreto, por favor selecione a forma de pagamento'}
    *
    * */
    public function requisicao(){
        $this->loadModel('Carrinho.EcmCarrinho');
        $retorno = [ 'sucesso' => false ];

        if ($this->request->is('post')) {
            $this->request->session()->write('url_requisicao', $this->referer());


            if (!isset($this->request->data['contrato']) || $this->request->data['contrato'] != 1){
                $retorno = ['sucesso' => false, 'mensagem' => __('Para continuar com o processo de compra você deve aceitar o contrato')];
                $this->set(compact('retorno'));
                return;
            }

            if (!isset($this->request->data['operadora']) || !is_numeric($this->request->data['operadora']) ||
                    !isset($this->request->data['tipoPagamento']) || !is_numeric($this->request->data['tipoPagamento']) ||
                    !isset($this->request->data['valorParcelas']) ||  !is_numeric($this->request->data['valorParcelas'])){
                $retorno = ['sucesso' => false, 'mensagem' => __('Por favor selecione a forma de pagamento')];
                $this->set(compact('retorno'));
                return;
            }

            $operadora = null;
            try {
                $operadora = $this->EcmCarrinho->EcmVenda->EcmOperadoraPagamento
                    ->get($this->request->data['operadora'], ['contain' => ['EcmFormaPagamento']]);
            } catch (RecordNotFoundException $e) {
                $retorno = ['sucesso' => false, 'mensagem' => __('Operadora não encontrada')];
                $this->set(compact('retorno'));
                return;
            }

            $tipoPagamento = null;
            try {
                $tipoPagamento = $this->EcmCarrinho->EcmVenda->EcmTipoPagamento
                    ->get($this->request->data['tipoPagamento']);
            } catch (RecordNotFoundException $e) {
                $retorno = ['sucesso' => false, 'mensagem' => __('Tipo de pagamento não encontrado')];
                $this->set(compact('retorno'));
                return;
            }

            if($ecmCarrinho = $this->request->session()->read('carrinho')){

                $venda = $this->EcmCarrinho->EcmVenda->find()->where(['ecm_carrinho_id' => $ecmCarrinho->id])->first();

                if (!$venda)
                    $venda = $this->EcmCarrinho->EcmVenda->newEntity();

                $total = $ecmCarrinho->calcularTotal();
                $parcelas = $this->request->data['valorParcelas'];
                $valorParcelas = $total / $parcelas;

                $this->EcmCarrinhoController = new EcmCarrinhoController();
                $parcelasArray = $this->EcmCarrinhoController->calcularValorParcelas($operadora['ecm_forma_pagamento_id']);

                if (!in_array($valorParcelas, $parcelasArray)){
                    $retorno = ['sucesso' => false, 'mensagem' => __('Numero de parcelas incorreto, por favor selecione a forma de pagamento')];
                    $this->set(compact('retorno'));
                    return;
                }

                $valorParcelas = $ecmCarrinho->calcularParcela($parcelas);
                $vendaStatus = $this->EcmCarrinho->EcmVenda->EcmVendaStatus->find('all')
                    ->where(['status' => EcmVendaStatus::STATUS_ANDAMENTO])->first();

                $venda->set('valor_parcelas', $valorParcelas);
                $venda->set('numero_parcelas', $parcelas);
                $venda->set('ecm_venda_status', $vendaStatus);
                $venda->set('mdl_user_id', $ecmCarrinho->get('mdl_user_id'));
                $venda->set('ecm_operadora_pagamento', $operadora);
                $venda->set('ecm_tipo_pagamento', $tipoPagamento);
                $venda->set('ecm_carrinho_id', $ecmCarrinho->get('id'));

                $this->EcmCarrinho->EcmVenda->save($venda);

                $this->loadModel('EcmLogContrato');
                if(!$this->EcmLogContrato->exists(['ecm_venda_id' => $venda->id])) {
                    $ecmLogContrato = $this->EcmLogContrato->newEntity();
                    $ecmLogContrato->ecm_venda_id = $venda->id;
                    $ecmLogContrato->timecreated = time();
                    $this->EcmLogContrato->save($ecmLogContrato);
                }

                $this->loadModel('MdlUser');
                $mdlUser = $this->MdlUser->get($ecmCarrinho->get('mdl_user_id'));
                $ecmCarrinho->set('mdl_user', $mdlUser);

                $plugin = '\FormaPagamento' . $operadora['ecm_forma_pagamento']['controller'] . '\Controller';
                $plugin .= '\FormaPagamento' . $operadora['ecm_forma_pagamento']['controller'] . 'Controller';
                $this->FormaPagamento = new $plugin();

                $this->FormaPagamento->initialize();
                $this->FormaPagamento->beforeFilter($this->event);
                $this->request->session()->write('compraConfirmada', true);
                
                $this->FormaPagamento->request->data = $this->request->data;

                $retorno = $this->FormaPagamento->requisicao();
            }

        } else {
            $retorno = ['sucesso' => false, 'mensagem' => __('Requisição POST necessária')];
        }
        $this->set(compact('retorno'));
        
    }

    /*
    * Função responsável por criar o retorno do pagamento do carrinho
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/forma-pagamento/wsc-forma-pagamento/retorno
    *
    * Retornos:
    * 1- {'sucesso':true, 'mensagem': 'Transação efetivada com sucesso'}
    * 2- {'sucesso':false, 'mensagem': 'Carrinho não encontrado'}
    * 3- {'sucesso':false, 'mensagem': 'Transação não encontrada'}
    *
    * */
    public function retorno(){
        $numeroTransacao = $this->request->data('numeroTransacao');

        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Produto.EcmProduto');
        $ecmTransacao = null;
        $retorno = [ 'sucesso' => false,  'mensagem' => __('Transação não localizada') ];
        $ecmCarrinho = $this->request->session()->read('carrinho');

        if(!is_null($numeroTransacao)) {

            $ecmTransacao = $this->EcmTransacao->find('all', ['contain' => [
                                        'EcmOperadoraPagamento' => ['EcmFormaPagamento'],
                                        'EcmTipoPagamento' => ['EcmFormaPagamento'],
                                        'EcmVenda' => ['EcmCarrinho' => [
                                            'EcmCarrinhoItem' => ['EcmCursoPresencialTurma' => ['EcmCursoPresencialData', 'EcmProduto'],
                                                'EcmProduto' => ['EcmTipoProduto']
                                            ]
                                        ]]
                                    ]])
                                    ->where([ 'EcmCarrinho.id' => $ecmCarrinho->get('id'), 'EcmTransacao.id' => $numeroTransacao ])
                                    ->orderDesc('EcmTransacao.id')
                                    ->first();

        }else{

            $ecmTransacao = $this->EcmTransacao->find('all',
                                            [
                                                'contain' => [
                                                    'EcmOperadoraPagamento' => ['EcmFormaPagamento'],
                                                    'EcmTipoPagamento' => ['EcmFormaPagamento'],
                                                    'EcmVenda' => ['EcmCarrinho' => [
                                                        'EcmCarrinhoItem' => ['EcmCursoPresencialTurma' => ['EcmCursoPresencialData', 'EcmProduto'],
                                                            'EcmProduto' => ['EcmTipoProduto']
                                                        ]
                                                    ]]
                                                ]
                                            ])
                                        ->where([
                                            'EcmCarrinho.id' => $ecmCarrinho->get('id')
                                        ])
                                        ->orderDesc('EcmTransacao.id')
                                        ->first();
        }

        if($ecmTransacao){
            $numeroTransacao = $ecmTransacao->get('id');

            foreach($ecmTransacao->ecm_venda->ecm_carrinho->ecm_carrinho_item as $carrinho_item) {
                $tipoproduto = "";
                foreach($carrinho_item->ecm_produto->ecm_tipo_produto as $ecm_tipo_produto) {
                    if ($ecm_tipo_produto->nome == "Pacotes" || $ecm_tipo_produto->nome == "Prazo Extra" ||
                            $ecm_tipo_produto->nome == "Presencial") {
                        $tipoproduto = $ecm_tipo_produto->nome;
                        break;
                    }
                }
                unset($carrinho_item->ecm_produto->ecm_tipo_produto);
                switch($tipoproduto){
                    case "Presencial":
                        break;
                    case "Pacotes":
                        $ecmProduto = $this->EcmProduto->get($carrinho_item->ecm_produto->id, ['contain' => ['EcmProdutoPacote']]);
                        $carrinho_item->enrolperiod = (int)$ecmProduto->ecm_produto_pacote->enrolperiod;
                        break;
                    case "Prazo Extra":
                        $ecmProduto = $this->EcmProduto->get($carrinho_item->ecm_produto->id, ['contain' => ['EcmProdutoPrazoExtra']]);
                        $carrinho_item->enrolperiod = (int)$ecmProduto->ecm_produto_prazo_extra->enrolperiod;
                        break;
                    default:
                        $ecmProduto = $this->EcmProduto->get($carrinho_item->ecm_produto->id, ['contain' => ['MdlCourse' =>
                            ['MdlEnrol' => ['conditions' => ['MdlEnrol.enrol like "manual"']]]]]);
                        if(!empty($ecmProduto->mdl_course[0]->mdl_enrol)) {
                            $enrolperiod = (int)$ecmProduto->mdl_course[0]->mdl_enrol[0]->enrolperiod;
                            $carrinho_item->enrolperiod = $enrolperiod / 24 / 60 / 60;
                        }else{
                            $carrinho_item->enrolperiod = null;
                        }
                        break;
                }
            }

            if($ecmTransacao->ecm_transacao_status and $ecmTransacao->ecm_transacao_status->status == 'paga')
                $this->request->session()->write('carrinho', $ecmTransacao['ecm_venda']['ecm_carrinho']);

            $plugin = '\FormaPagamento' . $ecmTransacao['ecm_operadora_pagamento']['ecm_forma_pagamento']['controller'] . '\Controller';
            $plugin .= '\FormaPagamento' . $ecmTransacao['ecm_operadora_pagamento']['ecm_forma_pagamento']['controller'] . 'Controller';
            $this->FormaPagamento = new $plugin();

            $this->FormaPagamento->initialize();
            $this->FormaPagamento->beforeFilter($this->event);

            $retorno = $this->FormaPagamento->retorno($numeroTransacao);
        }

        $referer = $this->request->session()->read('url_requisicao');
        $referer = str_replace('https://', 'http://', $referer);
        $host = substr($referer, 0, strpos($referer, "/", 8)+1);
        if(strpos($host, 'www'))
            $host = str_replace('www.','',$host);

        $this->loadModel('Entidade.EcmAlternativeHost');
        if($this->EcmAlternativeHost->exists(['host' => $host]) && $retorno['sucesso']){
            $host = str_replace('http://', 'https://', $host);

            if(strpos( $referer, 'renovacao') === false ){
                $referer = $host . 'carrinho/confirmacao/' . $ecmTransacao->ecm_venda_id;
            }else{
                $referer = $host . 'renovacao-licencas-altoqi/confirmacao/' . $ecmTransacao->ecm_venda_id;
            }
            
        }else if($this->EcmAlternativeHost->exists(['host' => $host])){
            $host = str_replace('http://', 'https://', $host);
            
            if(strpos( $referer, 'renovacao') === false){
                $referer = $host . 'carrinho/pagamento';
            }else{
                $referer = $host . 'renovacao-licencas-altoqi/pagamento';
            }
        }

        return $this->redirect($referer);
    }
}