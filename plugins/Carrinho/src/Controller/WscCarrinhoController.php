<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 23/06/2016
 * Time: 10:16
 */

namespace Carrinho\Controller;

use App\Model\Entity\MdlUser;
use Cake\Core\Configure;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Event\Event;
use Cake\Routing\Router;
use Carrinho\Model\Entity\EcmCarrinho;
use Produto\Model\Entity\EcmProduto;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use stdClass;

class WscCarrinhoController extends CarrinhoController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }


    /**
     * @return json array
     */
    public function produtosAltoqi() {
        if( empty($this->request->data) || 
        is_null($this->request->data('produto')) || 
        is_null($this->request->data('conexao')) || 
        is_null($this->request->data('especiais')) || 
        is_null($this->request->data('ativacao')) 
        /* || is_null($this->request->data('edicao')) 
        || is_null($this->request->data('app')) 
        || is_null($this->request->data('licenca')) 
        || is_null($this->request->data('modulos_ltemp')) 

        || is_null($this->request->data('modulos_json')) */
        ) {
            $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetros não informados')];
        }else {
            $produto = $this->request->data('produto');
            $conexao = $this->request->data('conexao');
            $especiais = $this->request->data('especiais');
            $ativacao = $this->request->data('ativacao');
            $modulos_json = $this->request->data('modulos_json');
            $edicao = $this->request->data('edicao');
            $app = $this->request->data('app');
            $licenca = $this->request->data('licenca');
            $modulos_ltemp = $this->request->data('modulos_ltemp');

            if (!is_null($produto) && !is_string($produto)) {
                $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro produto incorreto')];
            }
            if (!is_null($conexao) && !is_numeric($conexao)) {
                $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro conexao incorreto')];
            }
            if (!is_null($especiais) && !is_string($especiais)) {
                $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro especiais incorreto')];
            }
            if (!is_null($ativacao) && !is_string($ativacao)) {
                $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro ativacao incorreto')];
            }

            if (!is_null($modulos_json) && !is_string($modulos_json)) {
                //$retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro modulos_json incorreto')];
                if (!is_null($edicao) && !is_numeric($edicao)) {
                    $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro edicao incorreto')];
                }
                if (!is_null($app) && !is_numeric($app)) {
                    $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro app incorreto')];
                }
                if (!is_null($licenca) && !is_string($licenca)) {
                    $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro licenca incorreto')];
                }
                if (!is_null($modulos_ltemp) && !is_string($modulos_ltemp)) {
                    $retorno = ['codigo_tw' => "-", 'descricao' => __('Parâmetro modulos_ltemp incorreto')];
                }
            }

        }

        if(!isset($retorno)) $retorno = parent::produtosAltoqi();
        
        $this->set(compact('retorno'));
    }

    /**
     * @return json array
     */
    public function criarPropostaAltoqi()
    {
        $this->loadModel('MdlUser');
        $this->loadModel('Vendas.DbaVendasProdutos');
        $this->loadModel('Entidade.EcmAlternativeHost');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Cupom.EcmCupom');

        $retorno = ['sucesso' => true];
        $tempo = $this->EcmConfig->find()->where(['nome' => 'tempo_carrinho_proposta_altoqi'])->first();
        $cupomLtemp = $this->EcmConfig->find()->where(['nome' => 'cupom_ltemp_carrinho_proposta_altoqi'])->first();
        $cupomLanual = $this->EcmConfig->find()->where(['nome' => 'cupom_lanual_carrinho_proposta_altoqi'])->first();
        
        if( $this->request->data['idStatusDoPedido'] == 'LTEMP' ){
            $cupom = $this->EcmCupom->get($cupomLtemp->valor , [ 'contain' => ['EcmTipoProduto', 'EcmProduto']]);
        }else if( $this->request->data['idStatusDoPedido'] == 'LANUAL' ){
            $cupom = $this->EcmCupom->get($cupomLanual->valor , [ 'contain' => ['EcmTipoProduto', 'EcmProduto']]);
        }else{
            $cupom = false;
        }

        $aplicacao = ['lite'=>0,  'LT'=>0,
                      'flex'=>1,  'FLE'=>1,
                      'basic'=>2, 'BA'=>2, 'BAR'=>2,
                      'pro'=>3,   'PR'=>3, 'PRR'=>3,
                      'plena'=>4, 'PL'=>4, 'PLR'=>4,
                      'modulo'=>5];

        $idTopProdutos = array_replace($this->DbaVendasProdutos->pacotes,
                               $this->DbaVendasProdutos->ebericks,
                               $this->DbaVendasProdutos->qibs,
                               $this->DbaVendasProdutos->modulos,
                               $this->DbaVendasProdutos->mod_tipo_idtop);

        $data = $this->request->data;

        if($user = $this->MdlUser->validarUserAltoQi($data)){
            $this->request->session()->delete('carrinho');

            foreach ($data['produtos'] as $value) {
                $this->request->data = array();
    
                $this->request->data['conexao'] = !array_key_exists('pontos_rede', $value) || $value['pontos_rede'] == 0 ? 0 : 1; // EBV, QIB, MOD 
                $this->request->data['especiais'] = $value['especiais']; // EBV, QIB, MOD 
                
                if ($value['tipo_protecao'] == 'Sem protetor')// EBV, QIB, MOD 
                    $this->request->data['ativacao'] = 'remota'; 
                else if (preg_match("/(USB|REMOTA|ONLINE)/i", $value['tipo_protecao'])) 
                    $this->request->data['ativacao'] = strtolower($value['tipo_protecao']); 
                else
                    $this->request->data['ativacao'] = 'online'; 
                
                if(array_key_exists("modulos_json", $value)) $this->request->data['modulos_json'] = $value['modulos_json']; // EBV, MOD 
                
                if (strpos($value['sigla'], 'EB0') !== false) {
                    $this->request->data['produto'] = 'mod'; // MOD 
                    $modulos_json = array_key_exists('modulos_json', $this->request->data) ? json_decode($this->request->data['modulos_json']) : [];
                    array_unshift($modulos_json, $value['sigla']);
                    $this->request->data['modulos_json'] = json_encode($modulos_json); 
                } else {
    
                    if(array_key_exists($value['produto_top_id'], $idTopProdutos)) $pac = $idTopProdutos[$value['produto_top_id']];
    
                    if(!isset($pac)) continue;
    
                    if(array_key_exists('modulo', $pac))
                        $this->request->data['modulos_ltemp'] = ucfirst($pac['modulo']);
                    else if(array_key_exists('modulos', $pac))
                        $this->request->data['modulos_ltemp'] = ucfirst($pac['modulos']);
                    else //continue;
                        $this->request->data['modulos_ltemp'] = $value['modulos_ltemp'];
    
                    $sufixoItem = '';
                    if(array_key_exists('itens_json', $value) && !empty($value['itens_json'])){
                        foreach ($value['itens_json'] as $item) {
                            if(substr($item, -2) == "BA") {
                                $this->request->data['app'] = 2;
                                $sufixoItem = 'BA';
                            } else if(substr($item, -2) == "PR") {
                                $this->request->data['app'] = 3;
                                $sufixoItem = 'PR';
                            }
                        }
                    }

                    if(!array_key_exists('app', $this->request->data)){
                        $keywords = preg_split("/([0-9])+/", $value['sigla']);
                        if(count($keywords) > 1 && !empty($keywords[1]))
                            $this->request->data['app'] = $aplicacao[$keywords[1]];
                        if(array_key_exists('aplicacao', $pac) && array_key_exists($pac['aplicacao'], $aplicacao))
                            $this->request->data['app'] = $aplicacao[$pac['aplicacao']]; // EBV, QIB 
                        else 
                            $this->request->data['app'] = 4;
                    }
    
                    if(empty($this->request->data['modulos_ltemp']) && !empty($value['itens_json'])){
                        $itens = ['HID', 'INC', 'GAS', 'ELT', 'CAB', 'SPD'];
                        $count = 0;
                        foreach ($value['itens_json'] as $item) {
                            if(in_array(substr($item, 3, 3), $itens)) $count++;
                        }
                        switch ($count) {
                            case 0: // Verificar
                            case 1:
                            $this->request->data['modulos_ltemp'] = 'Light';
                                break;
                            case 2:
                            case 3:
                            $this->request->data['modulos_ltemp'] = 'Essencial';
                                break;
                            default:
                            $this->request->data['modulos_ltemp'] = 'Top';
                                break;
                        }
                    }
    
                    if(array_key_exists("itens_json", $value) && !empty($value['itens_json'])) { // QIB 
                        if($this->request->data['modulos_ltemp'] == 'Essencial'){
                            $familias = [
                                '15 - CPH'  => ["QIBHID".$sufixoItem,"QIBINC".$sufixoItem,"QIBGAS".$sufixoItem],
                                '16 - CPE'  => ["QIBELT".$sufixoItem,"QIBCAB".$sufixoItem,"QIBSPD".$sufixoItem],
                                '17 - CPEH' => ["QIBHID".$sufixoItem,"QIBELT".$sufixoItem]
                            ];
                            foreach($familias as $key => $fam) {
                                if(empty(array_diff($fam, $value['itens_json']))){
                                    $this->request->data['familia'] = $key;
                                    break;
                                }
                            }
                        }

                        $this->request->data['itens_json'] = json_encode($value['itens_json']); 
                    }
    
                    $this->request->data['licenca'] = $data['idStatusDoPedido'] == 'VENDI' ? 'VITALÍCIA' : $data['idStatusDoPedido']; // EBV, QIB 

                    if( preg_match('/\d+/', $value['sigla']) > 0 ){
                        $this->request->data['produto'] = strtolower(substr($value['sigla'], 0, 3)); // EBV, QIB 
                        $this->request->data['edicao'] = intval(preg_replace('/[^0-9.]/','',$value['sigla'])); // EBV, QIB 
                    } else {
                        $this->request->data['produto'] = strtolower($value['sigla']);
                        $this->request->data['edicao'] = $pac['edicao'];
                        if($this->request->data['licenca'] != 'VITALÍCIA' && array_key_exists("tempo-up", $pac))
                            $this->request->data['tempo-up'] = $pac['tempo-up'];
                    }
                    if($this->request->data['edicao'] > 2000) 
                        $this->request->data['edicao'] -= 2000;
    
                    if(array_key_exists("rede", $value)) $this->request->data['rede'] = $value['rede']; // EBV, QIB 
                    if(array_key_exists("tempo-renova", $value)) $this->request->data['tempo-renova'] = $value['tempo-renova']; // EBV, QIB 
    
                    if($this->request->data['ativacao'] == 'usb') $this->request->data['frete'] = 1;
                    if(array_key_exists("frete", $value)) $this->request->data['frete'] = $value['frete']; // EBV, QIB 
    
                    foreach($value as $k => $v) {
                        if (strpos($k, 'MOD-') !== false || strpos($k, 'ITEM-') !== false) 
                            $this->request->data[$k] = $v;
                    }
                }

                $dados = parent::produtosAltoqi();
    
                if($dados['codigo_tw'] == "-"){
                    if(Configure::read('debug')){
                        Log::error('Erro na calculo do produto: "./Carrinho/src/Controller/WscCarrinhoController::criarPropostaAltoqi"');
                        Log::error($data['idStatusDoPedido']);
                        Log::error($value);
                    }
                    $retorno['mensagem'] = 'Ocorreu um possivel erro ao criar o carrinho no calculo de valores dos produtos';
                    continue;
                }
                
                $this->request->data['codigo_tw'] = $dados['codigo_tw'];
                $this->request->data['descricao'] = $dados['descricao'];
                $this->request->data['valor_unitario'] = $dados['valor_unitario'];
                if(array_key_exists("modulos", $dados) && !empty($dados['modulos'])){
                    $this->request->data['modulos'] = array();
                    foreach ($dados['modulos'] as $key => $val) {
                        $this->request->data['modulos'][$key] = array(
                            'codigo_tw' => $val['codigo_tw'],
                            'descricao' => $val['descricao'],
                            'valor_unitario' => $val['valor_unitario']
                        );
                    }
                }
    
                if (strpos($value['sigla'], 'EB0') !== false) 
                    unset($this->request->data['edicao']);
    
                if(!parent::produtosAltoqi()['sucesso']){
                    if(Configure::read('debug')){
                        Log::error('Erro na criacao do carrinho: "./Carrinho/src/Controller/WscCarrinhoController::criarPropostaAltoqi"');
                        Log::error($this->request->data);
                    }
                    $retorno['sucesso'] = false;
                    break;
                }
            }

            $carrinho = parent::getCarrinho();
            $retorno['carrinho'] = $carrinho->id;

            if($cupom){
                foreach ($carrinho->ecm_carrinho_item as $item) {

                    $item->ecm_produto->preco = $item->valor_produto_desconto;
                    $desconto = $carrinho->verificarDesconto($item->ecm_produto, [], $cupom);

                    if(!is_null($desconto)){
                        $item->set('ecm_cupom', $cupom);
                        $item->set('ecm_cupom_id', $cupom->id);
                        $item->set('valor_produto_desconto', $desconto['valorTotal']);
                        $carrinho->addItem($item);
                    }
                }
            }
            
            if(!$retorno['sucesso'] || empty($carrinho->get('ecm_carrinho_item'))){
                $retorno['mensagem'] = 'Ocorreu um erro ao criar o carrinho';
            }
    
            if(empty($data['alternativeHostId'])){
                $alternativeHost = $this->EcmAlternativeHost->find()->where(['shortname' => $data['empresa']])->first();
                $carrinho->set('ecm_alternative_host_id', empty($alternativeHost) ? 1 : $alternativeHost->id);
            }else{
                $carrinho->set('ecm_alternative_host_id', $data['alternativeHostId']);
            }
    
            $carrinho->set('mdl_user_modified_id', 2);
            $carrinho->set('mdl_user_id', $user->id);
    
            if(!is_null($tempo)){
                $edicao = new \DateTime();
                $edicao->modify($tempo->valor); // +2 weeks
                $edicao->setTime(0, 0, 0); 
                $carrinho->set('edicao', $edicao);
            }

            $this->EcmCarrinho->save($carrinho, ['associated' => ['EcmCarrinhoItem']]);

        } else {
            $retorno = ['sucesso' => false, 'mensagem' => 'Ocorreu um erro ao validar o usuário'];
        }

        echo json_encode($retorno);
        die;
    }


    /*
     * Função reponsável por adicionar um produto em um carrinho.
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  produto: (id do produto),
     *  presencial: (id das turmas presenciais),
     *  quantidade: (quantidade de itens, esse parâmetro é opcional),
     *  entidade: (id da entidade)
     * }
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
     * 4- {'sucesso':false, 'mensagem': 'Parâmetro quantidade incorreto'}
     * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
     * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
     * 7- {'sucesso':false, 'mensagem': 'Não há vagas o suficiente'}
     * 8- {'sucesso':false, 'mensagem': 'Turma não informada'}
     *
     * */
    public function add(){
        $validaAlternativeHost = $this->validaAlternativeHost();

        if(is_array($validaAlternativeHost)) {
            $retorno = $validaAlternativeHost;
        }else{
            $retorno = $this->naoExisteTransacao();
            if($retorno['sucesso'])
                $retorno = parent::addItem();
        }

        $carrinho = $this->listarItens();
        $retorno['carrinho'] = $carrinho;
        $this->set(compact('retorno'));
    }

    /*
    * Função reponsável por remover um produto em um carrinho.
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  produto: (id do produto),
    *  remover_tudo: ('1 ou 0, esse parâmetro é opcional),
    *  entidade: (id da entidade)
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro remover_tudo incorreto'}
    * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
    * 7- {'sucesso':false, 'mensagem': 'Turma não informada'}
    *
    * */
    public function remove(){
        $validaAlternativeHost = self::validaAlternativeHost();

        if(is_array($validaAlternativeHost)) {
            $retorno = $validaAlternativeHost;
        }else{
            $retorno = $this->naoExisteTransacao();
            if($retorno['sucesso'])
                $retorno = parent::removeItem();
        }

        $carrinho = $this->listarItens();
        $retorno['carrinho'] = $carrinho;
        $this->set(compact('retorno'));
    }

    /*
    * Função reponsável por retornar uma lista de produtos que estão no carrinho
    *
    * Retornos: JSON
    * "retorno": {
    *    "carrinho": { Dados }
    *  }
    * */
    public function listar(){

        $errorTransacao = $this->request->session()->read('error_transacao');

        if($errorTransacao){
            $retorno = $this->naoExisteTransacao($errorTransacao);
            $this->request->session()->write('error_transacao', false);
        }else if(!is_null($this->Auth->user())){
            $retorno = $this->naoExisteTransacao();
        }

        $carrinho = $this->listarItens();
        $retorno['carrinho'] = $carrinho;
        $this->set(compact('retorno'));
    }

    /*
    * Função reponsável por agendar o inicio de uma lista de produtos que estão no carrinho
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  datainicio: (data de inicio do curso),
    *  item: (id do item do carrinho),
    *  duracao: (periodo de acesso em dias),
    *  pedido: (id do pedido [opcional])
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
    * 3- {'sucesso':false, 'mensagem': 'Não foi possivel agendar o produto'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro datainicio não informado'}
    * 5- {'sucesso':false, 'mensagem': 'Parâmetro item não informado'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetro duracao não informado'}
    * 7- {'sucesso':false, 'mensagem': 'Parâmetro pedido incorreto'}
    *
    * */
    public function agendar(){
        $validaAlternativeHost = $this->validaAlternativeHost();

        if(is_array($validaAlternativeHost)) {
            $retorno = $validaAlternativeHost;
        }else{
            $retorno = $this->naoExisteTransacao();
            if($retorno['sucesso'])
                $retorno = parent::agendar();
        }

        $carrinho = $this->listarItens();
        $retorno['carrinho'] = $carrinho;
        $this->set(compact('retorno'));
    }

    protected function naoExisteTransacao($statusTransacao = '2'){
        $carrinho = clone parent::getCarrinho();

        $this->loadModel('Carrinho.EcmTransacao');
        $ecmTransacao = $this->EcmTransacao->find()->contain([
                'EcmVenda' => [
                    'EcmCarrinho' => ['conditions' => ['EcmCarrinho.id' => $carrinho->id]],
                    'fields' => [
                        'numero_parcelas' => 'EcmVenda.numero_parcelas'
                    ]
                ],
                'EcmOperadoraPagamento' => [
                    'EcmFormaPagamento',
                    'fields' => [
                        'operadora' => 'EcmOperadoraPagamento.nome',
                        'forma_pagamento' => 'EcmFormaPagamento.nome'
                    ]
                ]
            ])
            ->select(['id', 'valor', 'data_envio', 'data_retorno', 'url', 'erro', 'lr', 'ecm_transacao_status_id'])
            ->orderDesc('EcmTransacao.id')
            ->where(['EcmTransacao.ecm_transacao_status_id' => $statusTransacao])
            ->first();


        if($ecmTransacao){
            if(isset($ecmTransacao->data_envio))
                $ecmTransacao->data_envio = $ecmTransacao->data_envio->format('U');
            if(isset($ecmTransacao->data_retorno))
                $ecmTransacao->data_retorno = $ecmTransacao->data_retorno->format('U');
            if(isset($ecmTransacao->lr))
                $ecmTransacao->mensagem = $ecmTransacao->getMsgErrorOperadora($ecmTransacao->lr);
            if($ecmTransacao->ecm_transacao_status_id){
                $ecmTransacao->set('status', $ecmTransacao->getStatusV3($ecmTransacao->ecm_transacao_status_id));
                $ecmTransacao->set('erro', $ecmTransacao->getMensagemV3($ecmTransacao->ecm_transacao_status_id));
            }

            return ['sucesso' => false, 'transacao' => $ecmTransacao ];
        }

        return ['sucesso' => true];
    }

    protected function validaAlternativeHost(){
        $alternativeHostId = $this->request->session()->read('alternativeHostId');

        if(!is_null($alternativeHostId))
            return $alternativeHostId;

        $validaAlternativeHost = parent::validaAlternativeHost();

        if(is_numeric($validaAlternativeHost)){
            $this->request->session()->write('alternativeHostId', $validaAlternativeHost);
        }

        return $validaAlternativeHost;
    }

    /*
     * Função responsável por tratar os dados de retorno da requisição
     *
     * @return array
     */
    private function listarItens(){
        $this->loadModel('Produto.EcmProduto');
        $carrinho = clone parent::getCarrinho();

        unset($carrinho->mdl_user);
        unset($carrinho->mdl_user_id);
        unset($carrinho->mdl_user_modified_id);
        unset($carrinho->ecm_alternative_host_id);
        unset($carrinho->data);
        unset($carrinho->edicao);
        unset($carrinho->id);
        $itens = [];

        if(!is_null($carrinho->get('ecm_carrinho_item'))){
            foreach($carrinho->get('ecm_carrinho_item') as $item){

                $item = clone $item;

                if($item->status == "Adicionado" || $item->status == "Aguardando Pagamento") {
                    $produto = null;
                    if (!is_null($item->ecm_curso_presencial_turma)) {

                        $crusoPresencial = clone $item->ecm_curso_presencial_turma;
                        $produto = clone $crusoPresencial->ecm_produto;
                        unset($crusoPresencial->ecm_produto);

                        $item->ecm_curso_presencial_turma = $crusoPresencial;
                    } else {
                        $produto = clone $item->ecm_produto;
                    }

                    unset($produto->descricao);

                    foreach($produto->ecm_tipo_produto as $tipo){

                        if($tipo->id == 47 || $tipo->id == 48 || $tipo->id == 49) {
                            if($pacelas = $this->verificaParcelasTrilhas($produto)) {
                                $produto->valor_parcelado = ($item->valor_produto_desconto / $pacelas);
                                $produto->parcelas = intval($pacelas);
                            }
                        }

                        if($tipo->id == 17) {
                            $ecmProduto = $this->EcmProduto->get($produto->id, ['contain' => ['EcmProdutoPacote']]);
                            $produto->enrolperiod = (int)$ecmProduto->ecm_produto_pacote->enrolperiod;
                        }

                        if($tipo->id == 16) {
                            $ecmProduto = $this->EcmProduto->get($produto->id, ['contain' => ['EcmProdutoPrazoExtra']]);
                            $produto->enrolperiod = (int)$ecmProduto->ecm_produto_prazo_extra->enrolperiod;
                        }

                        unset($tipo->_joinData);
                        unset($tipo->theme);
                        unset($tipo->categoria);
                    }

                    $produto->categorias = $produto->ecm_tipo_produto;
                    unset($produto->ecm_tipo_produto);
                    $imagem = $this->getUrlImagemProduto($produto);

                    unset($produto->ecm_imagem);
                    $produto->imagem = $imagem;
                    $item->ecm_produto = $produto;

                    if (isset($item->ecm_cupom)) {
                        $cupom = clone $item->ecm_cupom;

                        unset($cupom->ecm_produto);
                        unset($cupom->ecm_tipo_produto);
                        $cupom->datainicio = strtotime($cupom->datainicio->format('Y-m-d 00:00:00'));
                        $cupom->datafim = strtotime($cupom->datafim->format('Y-m-d 23:59:59'));

                        $item->set('ecm_cupom', $cupom);
                    }

                    if (isset($item->ecm_promocao)) {
                        $promocao = clone $item->ecm_promocao;

                        $promocao->datainicio = strtotime($promocao->datainicio->format('Y-m-d 00:00:00'));
                        $promocao->datafim = strtotime($promocao->datafim->format('Y-m-d 23:59:59'));

                        $item->set('ecm_promocao', $promocao);
                    }

                    $item->valor_produto = number_format($item->valor_produto, 2, '.', '');
                    $item->valor_produto_desconto = number_format($item->valor_produto_desconto, 2, '.', '');

                    $itens[] = $item;
                }
            }
            $carrinho->set('ecm_carrinho_item', $itens);
        }

        return $carrinho;
    }
    /*
     * Função responsável por tratar a url para acesso a imagem do produto
     *
     * @return String ou null
     */
    private function getUrlImagemProduto($produto){
        if(count($produto->ecm_imagem) > 0){
            $imagem = current($produto->ecm_imagem);
            $url = Router::url('/webroot/upload/'.$imagem->src, true);
            return $url;
        }

        return null;
    }

    /*
    * Função reponsável por cancelar uma transação em aberto
    * Deve ser feito requisições do tipo GET
    *
    * Retornos:
    * 1- {'sucesso':true, 'mensagem': 'Transação cancelada'}
    * 2- {'sucesso':false, 'mensagem': 'Não é possível cancelar a transação, o carrinho não está com status "Em Andamento"'}
    * 3- {'sucesso':false, 'mensagem': 'Não é possível cancelar a transação'}
    *
    * */
    public function cancelarTransacao(){
        $this->loadModel('Carrinho.EcmTransacao');

        $retorno = [
            'sucesso' => false,
            'mensagem' => 'Não é possível cancelar a transação, o carrinho não está com status "Em Andamento"'
        ];

        $carrinho = parent::getCarrinho();
        if($carrinho->get('status') == EcmCarrinho::STATUS_EM_ABERTO) {

            $retorno['mensagem'] = 'Não é possível cancelar a transação';

            $transacao = $this->EcmTransacao->find()
                ->contain(['EcmVenda'])
                ->where([
                    'EcmVenda.ecm_carrinho_id' => $carrinho->get('id'),
                    'OR' => ['EcmTransacao.data_retorno IS NULL', 'EcmTransacao.ecm_transacao_status_id' => 5 ]
                ])
                ->orderDesc('EcmTransacao.id')
                ->first();

            if(!is_null($transacao)){
                $transacao->set('ecm_transacao_status_id', 13);

                if($this->EcmTransacao->save($transacao)){
                    $retorno = ['sucesso' => true, 'mensagem' => 'Transação cancelada'];
                }
            }
        }

        $this->set(compact('retorno'));
    }

    private function verificaParcelasTrilhas(EcmProduto $produto){
        $this->loadModel('FormaPagamento.EcmFormaPagamento');
        $this->loadModel('Configuracao.EcmConfig');

        $maximoNumeroParcela = $this->EcmConfig->find()->where(['nome' => 'maximo_numero_parcela'])->first();
        $valorMinimoParcela = $this->EcmConfig->find()->where(['nome' => 'valor_minimo_parcela'])->first();

        $formaPagamento = $this->EcmFormaPagamento->find()
            ->contain([
                'EcmTipoProduto' => function($q){
                    return $q->where(['EcmTipoProduto.id in' => [47, 48, 49]]);
                }
            ])
            ->where([
                'EcmFormaPagamento.tipo' => 'cartao_recorrencia',
                'EcmFormaPagamento.habilitado' => 'true'
            ])->first();

        $numeroParcelas = EcmProduto::verificaParcelasTrilhas(
            $produto, $formaPagamento, $maximoNumeroParcela, $valorMinimoParcela
        );

        return $numeroParcelas;
    }

    /**
     * Função responsável por buscar uma proposta criada por um atendente
     * Deve ser feito requisições do tipo POST
     *
     * Retornos:
     * 1- {'sucesso':true,  'carrinho': $ecmCarrinho}
     * 2- {'sucesso':false, 'mensagem': 'Proposta inválida'}
     * 3- {'sucesso':false, 'mensagem': 'Proposta inexistente'}
     * 4- {'sucesso':false, 'mensagem': 'Proposta encerrada'}
     * 5- {'sucesso':false, 'mensagem': 'Proposta expirada'}
     **/
    public function getProposta(){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Carrinho.EcmCarrinho');
        $idCarrinho = $this->request->data('proposta');
        $carrinhoAtual = $this->getCarrinho();
        $tempo_carrinho_proposta = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'tempo_carrinho_proposta'])->first();
        $retorno = ['sucesso' => false ];

        $ecmCarrinho = null;
        try {
            $ecmCarrinho = $this->EcmCarrinho->get($idCarrinho, ['contain' => [
                'MdlUser' => [
                    'MdlUserEcmAlternativeHost' => [
                        'EcmAlternativeHost'
                    ]
                ],
                'EcmCarrinhoItem' => [
                    'EcmProduto' => [
                        'EcmTipoProduto', 'EcmImagem',
                        'MdlCourse' => ['fields' => ['id', 'fullname', 'timeaccesssection', 'EcmProdutoMdlCourse.ecm_produto_id']]
                    ],
                    'EcmCursoPresencialTurma' => [
                        'EcmProduto' => [ // Redundância
                            'EcmTipoProduto', 'EcmImagem'
                        ]
                    ],
                    'EcmCarrinhoItemEcmProdutoAplicacao' => [
                        'EcmProdutoEcmAplicacao' => [
                            'EcmProdutoAplicacao'
                        ],
                    ],
                    'EcmCarrinhoItemMdlCourse' => [
                        'MdlCourse' => [
                            'EcmProduto' => function ($q) {
                                return $q->where(['refcurso' => 'true']);
                            }
                        ],
                    ],
                    'EcmPromocao', 'EcmCupom'
                ]
            ]]);
        } catch (RecordNotFoundException $e) {
             $retorno['mensagem'] = __('Proposta Inválida');
        } catch (InvalidPrimaryKeyException $e) {
            $retorno['mensagem'] = __('Proposta Inválida');
        }

        if(!is_null($ecmCarrinho)){
            $dataEdit = new \DateTime();
            $dataAtual = new \DateTime();
            $dataEdit->setTimestamp($ecmCarrinho->get('edicao')->format('U'));
            $dataEdit->modify('+'.$tempo_carrinho_proposta->valor);

            if($ecmCarrinho->get('status') != "Em Aberto"){
                $retorno['mensagem'] = __('Proposta Encerrada');
            } else if($dataAtual->format('U') > $dataEdit->format('U')) {
                $retorno['mensagem'] = __('Proposta Expirada');
            }else{

                if(!is_null($carrinhoAtual) && $carrinhoAtual->get('id') && ($carrinhoAtual->get('id') != $ecmCarrinho->get('id') )){
                    $carrinhoAtual->set('status', EcmCarrinho::STATUS_CANCELADO);
                    $this->EcmCarrinho->save($carrinhoAtual);
                }

                $itens = $ecmCarrinho->ecm_carrinho_item;
                $ecmCarrinho->ecm_carrinho_item = [];

                foreach($itens as $item){
                    $chave = $item->ecm_produto_id;
                    if(!is_null($item->get('ecm_curso_presencial_turma'))) {
                        $chave .= '-' . $item->ecm_curso_presencial_turma_id;
                    }
                    if(!empty($item->get('ecm_carrinho_item_ecm_produto_aplicacao'))) {
                        foreach($item->get('ecm_produto')->get('ecm_tipo_produto') as $tipo){
                            if($tipo->id == 56 || $tipo->id == 57){
                                $nome = '';
                                foreach($item->get('ecm_carrinho_item_ecm_produto_aplicacao') as $aplicacao){
                                    if($aplicacao->ecm_produto_ecm_aplicacao->ecm_produto_id == $item->ecm_produto_id && empty($nome)){
                                        $app = $aplicacao->ecm_produto_ecm_aplicacao->ecm_produto_aplicacao;
                                        $nome = ' '.substr($app->modulos_linha, 5);
                                    }
                                }
                                if(!empty($nome))
                                    $item->ecm_produto->nome .= $nome;
                            }
                        }
                    }
                    $ecmCarrinho->ecm_carrinho_item[$chave] = $item;
                }

                if(is_numeric($ecmCarrinho->get('mdl_user_modified_id'))){
                    unset($retorno);
                    if(!is_null($this->Auth->user()) && $this->Auth->user('id') && $this->Auth->user('id') != $ecmCarrinho->get('mdl_user_id')){
                        $ecmCarrinho->set('mdl_user_id',$this->Auth->user('id'));
                        $this->EcmCarrinho->save($ecmCarrinho);
                    }
                    $this->request->session()->write('carrinho', $ecmCarrinho);
                    $this->listar();
                }else
                    $retorno['mensagem'] = __('Proposta Inexistente');
            }
        }else{
            $retorno['mensagem'] = __('Proposta Inexistente');
        }

        if(isset($retorno))
            $this->set(compact('retorno'));
    }

    /**
     * Função responsável por cancelar o carrinho em aberto na sessão
     * Deve ser feito requisições do tipo GET
     *
     * Retornos:
     * 1- {'sucesso':true, 'mensagem': 'Carrinho cancelado'}
     **/
    public function cancelarCarrinho(){
        $carrinho = $this->getCarrinho();
        $carrinho->set('status', EcmCarrinho::STATUS_CANCELADO);
        $this->salvarCarrinho($carrinho);

        $retorno = ['sucesso' => true, 'mensagem' => __('Carrinho cancelado')];
        $this->set(compact('retorno'));
    }
}