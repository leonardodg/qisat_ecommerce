<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 23/06/2016
 * Time: 09:47
 */

namespace Carrinho\Controller;


use App\Controller\WscController;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
// use Produto\Criterio\Criterio;
use Produto\Model\Entity\EcmTipoProduto;
use Cake\Routing\Router;

class CarrinhoController extends WscController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Carrinho.EcmCarrinho');
    }

    /*
     * Função reponsável por adicionar um produto em um carrinho.
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  produto: (id do produto),
     *  presencial: (id das turmas presenciais),
     *  quantidade: (quantidade de itens, esse parâmetro é opcional, default: 1)
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
    protected function addItem(){
        $validarRequisicao = $this->validarRequisicao();

        if($validarRequisicao === true){
            $idProduto = $this->request->data('produto');
            $idAplicacao = $this->request->data('aplicacao');
            $idPresencial = $this->request->data('presencial');
            $quantidadeParam = $this->request->data('quantidade');

            $this->loadModel('Produto.EcmProduto');
            $this->loadModel('Promocao.EcmPromocao');
            $this->loadModel('Produto.MdlFase');

            $carrinho = $this->getCarrinho();

            /**
             * Verificação da quantidade de produtos e de vagas inseridas no carrinho
             *
            $criterio = new Criterio();
            $result = $criterio->get_status($this->request->data, $carrinho, [
                CRITERIO_TIPO_QUANTIDADE, CRITERIO_TIPO_VAGAS
            ]);
            if(!$result["sucesso"]) return $result; */

            if( (is_null($this->Auth->user()) && is_null($carrinho->get('mdl_user_modified_id')))
                || (!is_null($this->Auth->user()) && ($carrinho->get('mdl_user_modified_id') == $this->Auth->user('id')) || (is_null($carrinho->get('mdl_user_modified_id'))))) {

                    $produto = null;
                    $tipoproduto = null;
                    $presencial = null;
                    $tipos = [48,47,32,17,16,10,2];
                    $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();

                    try {
                        if(!is_null($idAplicacao)){
                            $aplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                ->contain(['EcmProdutoAplicacao','EcmProduto.EcmTipoProduto'])
                                ->where(['EcmProdutoEcmAplicacao.id' => $idAplicacao])->first();

                            $item->set('aplicacao', $aplicacao);
                        }
                            
                        if(!is_numeric($idProduto) && is_string($idProduto)){
                            $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->find()
                                ->contain(['EcmTipoProduto'])
                                ->where(['referencia' => $idProduto])->first();

                            if($produto)
                                $idProduto = $produto->get('id');
                            else if($aplicacao) {
                                $produto = $aplicacao->ecm_produto;
                                unset($aplicacao->ecm_produto);
                                $idProduto = $produto->get('id');
                            } else
                                throw new RecordNotFoundException();
                            
                        }else{
                            $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto, ['contain' => ['EcmTipoProduto' ]]);
                            $idProduto = $produto->get('id');
                        }

                    } catch (RecordNotFoundException $e) {
                        return ['sucesso' => false, 'mensagem' => __('Produto não encontrado') ];
                    }

                    foreach ($produto->ecm_tipo_produto as $ecm_tipo_produto) {
                        if (in_array($ecm_tipo_produto->id, $tipos)){
                            $tipoproduto = $ecm_tipo_produto->id;
                            break;
                        }
                    }

                    switch ($tipoproduto) {
                        case 47:
                            if($this->verificarFaseExistente($idProduto)){
                                return ['sucesso' => false, 'mensagem' => 'Não é possível inserir mais um item para esse produto'];
                            }
                            break;
                        case 10:
                            if(is_null($idPresencial)){
                                return ['sucesso' => false, 'mensagem' => __('Turma não informada'), 'erro_id_produto' => $idProduto ];
                            }
                            break;
                    }

                    try {
                        if (is_null($idPresencial)) {
                            $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto,
                                ['contain' => ['EcmTipoProduto', 'EcmImagem',
                                    'MdlCourse' => ['fields' => ['id', 'fullname', 'timeaccesssection', 'EcmProdutoMdlCourse.ecm_produto_id']],
                                ],
                                    'fields' => [
                                        'EcmProduto.id', 'EcmProduto.nome', 'EcmProduto.preco', 'EcmProduto.sigla',
                                        'EcmProduto.parcela'
                                    ],
                                    'conditions' => ['EcmProduto.habilitado' => 'true', 'EcmProduto.preco is not null']
                                ]);

                        switch ($tipoproduto) {
                            case 17:
                                $ecmProduto = $this->EcmProduto->get($idProduto, ['contain' => ['EcmProdutoPacote']]);
                                $produto->enrolperiod = (int)$ecmProduto->ecm_produto_pacote->enrolperiod;
                                break;
                            case 16:
                                $ecmProduto = $this->EcmProduto->get($idProduto, ['contain' => ['EcmProdutoPrazoExtra']]);
                                $produto->enrolperiod = (int)$ecmProduto->ecm_produto_prazo_extra->enrolperiod;
                                break;
                            default:
                                $ecmProduto = $this->EcmProduto->get($idProduto, ['contain' => ['MdlCourse' =>
                                    ['MdlEnrol' => ['conditions' => ['MdlEnrol.enrol like "manual"']]]]]);

                                    if(!empty($ecmProduto->mdl_course[0]->mdl_enrol)) {
                                        $enrolperiod = (int)$ecmProduto->mdl_course[0]->mdl_enrol[0]->enrolperiod;
                                        $produto->enrolperiod = $enrolperiod / 24 / 60 / 60;
                                    }else{
                                        $produto->enrolperiod = null;
                                    }
                                    break;
                            }
                        } else {
                            $presencial = $this->EcmCarrinho->EcmCarrinhoItem->EcmCursoPresencialTurma->get($idPresencial,
                                [
                                    'contain' => ['EcmProduto' => ['EcmTipoProduto', 'EcmImagem'],
                                        'EcmCursoPresencialData.EcmCursoPresencialLocal.MdlCidade.MdlEstado'
                                    ],
                                    'conditions' => ['EcmCursoPresencialTurma.ecm_produto_id' => $idProduto,
                                        'EcmProduto.habilitado' => 'true']
                                ]);

                            $datas = [];
                            foreach ($presencial->ecm_curso_presencial_data as $presencial_data) {
                                $data = new \stdClass();
                                $data->data_inicio = $presencial_data->datainicio->format('U');
                                $data->saida_intervalo = $presencial_data->saidaintervalo->format('U');
                                $data->volta_intervalo = $presencial_data->voltaintervalo->format('U');
                                $data->data_fim = $presencial_data->datafim->format('U');
                                $datas[] = $data;
                            }
                            $item->set('datas', $datas);

                            $cidade = $presencial->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome;
                            $item->set('cidade', $cidade);
                            $estado = $presencial->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;
                            $item->set('estado', $estado);
                            unset($presencial->ecm_curso_presencial_data);

                            $item->set('ecm_curso_presencial_turma', $presencial);

                            $produto = $presencial->get('ecm_produto');
                        }
                        $item->set('ecm_produto', clone $produto);
                    } catch (RecordNotFoundException $e) {
                        return ['sucesso' => false, 'mensagem' => __('Produto não encontrado'), 'erro_id_produto' => $idProduto];
                    }

                    $quantidade = (is_null($quantidadeParam) || $quantidadeParam <= 0) ? 1 : $quantidadeParam;

                    $valorProduto = null;
                    if (!is_null($presencial)) {
                        if ($presencial->get('valor_produto') == 'false') {
                            $produto->set('preco', $presencial->get('valor'));
                        }
                    }

                    $quantidadeAtual = 0;

                    if ($carrinho->existeItem($item)) {
                        $item = $carrinho->getItem($item);
                        $quantidadeAtual = $item->get('quantidade');

                        $quantidadeItem = $item->get('quantidade');

                        if ($item->get('status') == EcmCarrinhoItem::STATUS_REMOVIDO) {
                            $quantidadeParam = 1;
                        }

                        if (!is_null($quantidadeParam)) {
                            $item->set('quantidade', $quantidade);
                        } else {
                            $item->set('quantidade', ++$quantidadeItem);
                        }
                    } else {
                        $item->set('ecm_carrinho_id', $carrinho->id);
                        $item->set('quantidade', $quantidade);
                    }

                    if (!is_null($presencial)) {
                        $vagasUtilizadas = $this->EcmCarrinho->EcmCarrinhoItem->totalVagasUtilizadasCursoPresencial($presencial, $carrinho);
                        if (!is_null($vagasUtilizadas) && $item->get('quantidade') > ($presencial->get('vagas_total') - $vagasUtilizadas)) {
                            $item->set('quantidade', $quantidadeAtual);
                            return ['sucesso' => false, 'mensagem' => __('Não há vagas o suficiente'), 'erro_id_produto' => $idProduto];
                        }
                    }

                    $valorItem = $produto->preco;

                    if(is_null($carrinho->get('mdl_user_modified_id'))){
                        $listaPromocao = $this->EcmPromocao->buscaPromocoesAtivasUsuario($produto, $carrinho->get('mdl_user_id'));
                        $descontosFase = null;
                        if(!is_null($carrinho->get('mdl_user_id')) &&
                            EcmTipoProduto::verificarTipoProduto($produto->get('ecm_tipo_produto'), 47)) {
                            $descontosFase = $this->MdlFase->buscarDescontos($carrinho->get('mdl_user_id'), $idProduto);
                        }

                        $cupom = null;
                        $user = $this->Auth->user();
                        if(!is_null($user)){
                            $this->loadModel('Cupom.EcmCupom');
                            if(is_array($user)){
                                $this->loadModel('MdlUser');
                                $user = $this->MdlUser->newEntity($user);
                            }
                            $cupons = $this->EcmCupom->buscarCupons($user);
                            foreach($cupons as $key => $cupom){
                                $ecmCupomMdlUser = $this->EcmCupom->find()->where(['id' => $cupom->id])
                                    ->matching('EcmCupomMdlUser', function($q)use($user){
                                        return $q->where(['mdl_user_id' => $user->id]);
                                    });
                                if($cupom->tipo_aquisicao == 0 && !$ecmCupomMdlUser->first())
                                    unset($cupons[$key]);
                            }
                            $cupom = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarMelhorCupom($cupons, $carrinho, $user->get('id'), $item);
                        }
                        $cupomAtual = $this->request->session()->read('cupom');

                        if(is_null($cupomAtual)) {
                            $desconto = $carrinho->verificarDesconto($produto, $listaPromocao, $cupom, $descontosFase);
                            if (!is_null($desconto)) {
                                if (isset($desconto['promocao']))
                                    $item->set('ecm_promocao', $desconto['promocao']);
                                if (isset($desconto['cupom'])){
                                    $item->set('ecm_cupom', $desconto['cupom']);
                                    $this->request->session()->write('cupom', $cupom);
                                }
                                $valorItem = $desconto['valorTotal'];
                            }
                            if(isset($aplicacao)){
                                $item->set('aplicacao', $aplicacao);
                                $produto->preco = $valorItem = $this->EcmProduto->calcularProdutoAltoqi($aplicacao);
                            }
                            $item->set('valor_produto_desconto', $valorItem);
                            $item->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                            $item->set('valor_produto', $produto->preco);
                            $carrinho->addItem($item);
                        } else {
                            $item1 = clone $item;
                            $desconto = $carrinho->verificarDesconto($produto, $listaPromocao, $cupomAtual, $descontosFase);
                            if (!is_null($desconto)) {
                                if (isset($desconto['promocao']))
                                    $item->set('ecm_promocao', $desconto['promocao']);
                                if (isset($desconto['cupom']))
                                    $item->set('ecm_cupom', $desconto['cupom']);
                                $valorItem = $desconto['valorTotal'];
                            }

                            $valorItem1 = $produto->preco;
                            $desconto1 = $carrinho->verificarDesconto($produto, $listaPromocao, $cupom, $descontosFase);
                            if (!is_null($desconto1)) {
                                if (isset($desconto1['promocao']))
                                    $item1->set('ecm_promocao', $desconto1['promocao']);
                                if (isset($desconto1['cupom']))
                                    $item1->set('ecm_cupom', $desconto1['cupom']);
                                $valorItem1 = $desconto1['valorTotal'];
                            }

                            if(isset($aplicacao)){
                                $item->set('aplicacao', $aplicacao);
                                $item1->set('aplicacao', $aplicacao);
                                $produto->preco = $valorItem = $this->EcmProduto->calcularProdutoAltoqi($aplicacao);
                            }

                            $item->set('valor_produto_desconto', $valorItem);
                            $item->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                            $item->set('valor_produto', $produto->preco);

                            $item1->set('valor_produto_desconto', $valorItem1);
                            $item1->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                            $item1->set('valor_produto', $produto->preco);

                            $carrinho1 = clone $carrinho;
                            $carrinho->addItem($item);

                            if(isset($carrinho1->ecm_carrinho_item))
                                foreach ($carrinho1->ecm_carrinho_item as $item2) {
                                    $item2 = clone $item2;
                                    if (!is_null($item2->ecm_cupom)) {
                                        unset($item2->ecm_cupom);
                                        $item2->ecm_cupom_id = null;
                                    }
                                    $promocoes = [];
                                    if (!is_null($item2->ecm_promocao))
                                        array_push($promocoes, $item2->ecm_promocao);
                                    $desconto = $carrinho1->verificarDesconto($item2->ecm_produto, $promocoes, $cupom);
                                    if (!is_null($desconto) && isset($desconto['cupom'])) {
                                        if (!isset($desconto['promocao'])) {
                                            unset($item2->ecm_promocao);
                                            $item2->ecm_promocao_id = null;
                                        }
                                        $item2->set('ecm_cupom', $cupom);
                                        $item2->set('ecm_cupom_id', $cupom->id);
                                        $item2->set('valor_produto_desconto', $desconto['valorTotal']);
                                    } else if (is_null($desconto)) {
                                        unset($item2->ecm_promocao);
                                        $item2->ecm_promocao_id = null;
                                    }
                                    $carrinho1->addItem($item2);
                                }

                            $carrinho1->addItem($item1);

                            if($carrinho1->calcularTotal() < $carrinho->calcularTotal()){
                                $carrinho = $carrinho1;
                                $this->request->session()->write('cupom', $cupom);
                            }
                        }

                    } else {
                        /**
                         * Produto AltoQi
                         */
                        if(isset($aplicacao)){
                            $item->set('aplicacao', $aplicacao);
                            //$produto->preco = $valorItem = $this->EcmProduto->calcularProdutoAltoqi($aplicacao);
                        }

                        $item->set('valor_produto_desconto', $valorItem);
                        $item->set('status', EcmCarrinhoItem::STATUS_ADICIONADO);
                        $item->set('valor_produto', $produto->preco);

                        $carrinho->addItem($item);
                    }

                    $this->salvarCarrinho($carrinho);

                    $this->loadModel('WebService.MdlCourse');
                    foreach ($produto->ecm_tipo_produto as $tipo) {
                        if($tipo->id == 17){
                            $mdlCourses = $this->MdlCourse->find()->hydrate(false)->select(['id'])
                                ->contain(['EcmProduto' => function ($q) {
                                    return $q->where(['refcurso' => 'true', 'EcmProduto.preco IS NOT NULL'])
                                        ->autoFields(false)->select(['id', 'preco']);
                                }, 
                                'EcmProdutoMdlCourse' => function ($q) use ($produto) {
                                    return $q->where(['ecm_produto_id' => $produto->id]);
                                }])
                                ->matching('EcmProdutoMdlCourse', function ($q) use ($produto) {
                                    return $q->where(['ecm_produto_id' => $produto->id]);
                                })->toArray();

                            $item = $carrinho->getItem($item);
                            foreach($mdlCourses as $mdlCourse) {
                                $ecmCarrinhoItemMdlCourse = $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->newEntity();
                                $ecmCarrinhoItemMdlCourse->ecm_carrinho_item_id = $item->id;
                                $ecmCarrinhoItemMdlCourse->mdl_course_id = $mdlCourse['id'];
                                if(!empty($mdlCourse['ecm_produto']) && 
                                   !empty($mdlCourse['ecm_produto'][0])){
                                    if(!empty($mdlCourse['ecm_produto_mdl_course']) && 
                                       !empty($mdlCourse['ecm_produto_mdl_course'][0]) && 
                                       !empty($mdlCourse['ecm_produto_mdl_course'][0]['preco'])){
                                        $ecmCarrinhoItemMdlCourse->valor = $mdlCourse['ecm_produto_mdl_course'][0]['preco'];
                                    }else{
                                        $ecmCarrinhoItemMdlCourse->valor = $mdlCourse['ecm_produto'][0]['preco'];
                                    }
                                    // Verificar promocao e cupom 
                                    if(!is_null($item->get("ecm_promocao")) || !is_null($item->get("ecm_cupom"))){
                                        $ecmCarrinhoItemMdlCourse->valor *= $item->get("valor_produto_desconto") / $item->get("valor_produto");
                                    }
                                }
                                $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->save($ecmCarrinhoItemMdlCourse);
                            }
                        }
                    }

                return ['sucesso' => true ];
            }else{
                return ['sucesso' => false, 'mensagem' => 'Não é possível alterar Proposta' ];
            }
        }else{
            return $validarRequisicao;
        }
    }

    /*
    * Função reponsável por remover um produto em um carrinho.
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  produto: (id do produto),
    *  presencial: (id das turmas presenciais),
    *  remover_tudo: ('1 ou 0, esse parâmetro é opcional, default: 0)
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
    protected function removeItem(){

        $validarRequisicao = $this->validarRequisicao();

        if($validarRequisicao === true) {

            $idProduto = $this->request->data('produto');
            $idPresencial = $this->request->data('presencial');
            $removeTudo = $this->request->data('remover_tudo');

            $this->loadModel('Produto.EcmProduto');

            $carrinho = $this->getCarrinho();
            $produto = null;
            $presencial = null;
            $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();

            try {
                if (is_null($idPresencial)) {
                    $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto,
                        ['contain' => ['EcmTipoProduto']]);

                    $produtoAltoqi = false;
                    foreach ($produto->ecm_tipo_produto as $ecmTipoProduto) {
                        if ($ecmTipoProduto->nome == "Presencial") {
                            return ['sucesso' => false, 'mensagem' => __('Turma não informada')];
                        }
                        if ($ecmTipoProduto->nome == "Produtos AltoQi") {
                            $produtoAltoqi = true;
                        }
                    }

                    // Verificando se existem dependencias no produto AltoQi
                    if($produtoAltoqi){
                        $produtoAltoqi = [];
                        foreach ($carrinho->ecm_carrinho_item as $key => $val) {
                            if (strpos($key, $idProduto.'-A') !== false) {
                                $produtoAltoqi[] = $key;
                                $keys = explode("-", $key);
                                $this->request->data('produto', $keys[0]);
                                $this->request->data('presencial', $keys[1]);
                                $this->removeItem();
                            }
                        }
                    }
                } else if (strpos($idPresencial, 'A') !== false) {
                    $aplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->get(substr($idPresencial,1),
                    [
                        'contain' => ['EcmProdutoAplicacao', 'EcmProduto' => ['EcmTipoProduto']],
                    ]);
                    $item->set('aplicacao', $aplicacao);
                    $produto = $aplicacao->get('ecm_produto');
                } else {
                    $presencial = $this->EcmCarrinho->EcmCarrinhoItem->EcmCursoPresencialTurma->get($idPresencial,
                    [
                        'contain' => ['EcmProduto' => ['EcmTipoProduto']],
                        'conditions' => ['EcmCursoPresencialTurma.ecm_produto_id' => $idProduto]
                    ]);
                    $item->set('ecm_curso_presencial_turma', $presencial);
                    $produto = $presencial->get('ecm_produto');
                }

                $item->set('ecm_produto', $produto);
            } catch (RecordNotFoundException $e) {
                return ['sucesso' => false, 'mensagem' => __('Produto não encontrado')];
            }

            if ($carrinho->existeItem($item)) {
                $item = $carrinho->getItem($item);
                if ($item->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO) {
                    $quantidadeItem = $item->get('quantidade');

                    if ($removeTudo || $quantidadeItem == 1) {
                        $item->set('status', EcmCarrinhoItem::STATUS_REMOVIDO);
                    } else {
                        $item->set('quantidade', --$quantidadeItem);
                    }
                    $carrinho->addItem($item);
                    $this->salvarCarrinho($carrinho);
                }
            }

            /**
             * Recalcular aplicações restantes (remove 25% de desconto)
             */
            foreach ($carrinho->ecm_carrinho_item as $item2) {
                if ($item2->status == "Adicionado" && isset($item2->aplicacao)) {
                    foreach ($item2->ecm_produto->ecm_tipo_produto as $tipo) {
                        if ($tipo->id == 48 && 
                        $item2->aplicacao->ecm_produto_aplicacao->aplicacao == $item->aplicacao->ecm_produto_aplicacao->aplicacao && 
                        $item2->aplicacao->ecm_produto_aplicacao->licenca   == $item->aplicacao->ecm_produto_aplicacao->licenca && 
                        $item2->aplicacao->ecm_produto_aplicacao->especiais == $item->aplicacao->ecm_produto_aplicacao->especiais) {
                            $refresh = true;
                            $item2->valor_produto_desconto = $item2->valor_produto;
                        }
                    }
                }
            }

            /**
             * Verificação de melhor cupom pro carrinho
             */
            $cupons = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarCupons();

            $user = $carrinho->get('mdl_user');
            if (!is_null($user)) {
                $cupons = array_merge($cupons, $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarCupons($user));
                foreach ($cupons as $key => $cupom) {
                    $ecmCupomMdlUser = $this->EcmCupom->find()->where(['id' => $cupom->id])
                        ->matching('EcmCupomMdlUser', function ($q) use ($user) {
                            return $q->where(['mdl_user_id' => $user->id]);
                        });
                    $ecmCupomCampanha = $this->EcmCupom->find()->where(['id' => $cupom->id])
                        ->matching('EcmCupomCampanha', function ($q) use ($user) {
                            return $q->where(['email' => $user->email]);
                        });
                    if ($cupom->tipo_aquisicao == 0 && !$ecmCupomMdlUser->first()) {
                        unset($cupons[$key]);
                    } else if ($cupom->tipo_aquisicao == 2 && !$ecmCupomCampanha->first()) {
                        unset($cupons[$key]);
                    }
                }
            }
            $cupom = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarMelhorCupom($cupons, $carrinho, $carrinho->get('mdl_user_id'), $item);

            $carrinho1 = clone $carrinho;

            foreach ($carrinho1->ecm_carrinho_item as $item2) {
                $item2 = clone $item2;
                if (!is_null($item2->ecm_cupom)) {
                    unset($item2->ecm_cupom);
                    $item2->ecm_cupom_id = null;
                }
                $promocoes = [];
                if (!is_null($item2->ecm_promocao))
                    array_push($promocoes, $item2->ecm_promocao);
                $desconto = $carrinho1->verificarDesconto($item2->ecm_produto, $promocoes, $cupom);
                if (!is_null($desconto) && isset($desconto['cupom'])) {
                    if (!isset($desconto['promocao'])) {
                        unset($item2->ecm_promocao);
                        $item2->ecm_promocao_id = null;
                    }
                    $item2->set('ecm_cupom', $cupom);
                    $item2->set('ecm_cupom_id', $cupom->id);
                    $item2->set('valor_produto_desconto', $desconto['valorTotal']);
                } else if (is_null($desconto)) {
                    unset($item2->ecm_promocao);
                    $item2->ecm_promocao_id = null;
                }
                $carrinho1->addItem($item2);
            }

            if ($carrinho1->calcularTotal() < $carrinho->calcularTotal())
                $this->salvarCarrinho($carrinho1);

            // atualizar pagina 
            if(isset($refresh)){
                return ['sucesso' => true, 'refresh' => $refresh];
            }

            // remove os itens dependencia da lista que ja foram removidos do carrinho 
            if(isset($produtoAltoqi) && $produtoAltoqi && count($produtoAltoqi)){
                return ['sucesso' => true, 'dependencias' => $produtoAltoqi];
            }
            
            return ['sucesso' => true];
            
        }else{
            return $validarRequisicao;
        }
    }

    /*
    * Função reponsável por alterar valor do produto no carrinho.
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  produto: (id do produto),
    *  presencial: (id das turmas presenciais),
    *  valor: foot
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro valor incorreto'}
    * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
    * 7- {'sucesso':false, 'mensagem': 'Turma não informada'}
    *
    * */
    protected function editValor(){

        $validarRequisicao = $this->validarRequisicao();

        if($validarRequisicao === true){

            $idProduto = $this->request->data('produto');
            $idPresencial = $this->request->data('presencial');
            $valor = $this->request->data('valor');
            $modulos = $this->request->data('modulos');

            $this->loadModel('Produto.EcmProduto');

            $carrinho = $this->getCarrinho();
            $produto = null;
            $presencial = null;
            $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();

            try {
                if (strpos($idProduto, 'A') !== false) {
                    $idAplicacao = explode("-A", $idProduto)[1];
                    $aplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->get($idAplicacao,
                    [
                        'contain' => ['EcmProdutoAplicacao', 'EcmProduto' => ['EcmTipoProduto']],
                    ]);
                    $item->set('aplicacao', $aplicacao);
                    $produto = $aplicacao->get('ecm_produto');
                } else if (is_null($idPresencial)) {
                    $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto,
                        ['contain' => ['EcmTipoProduto']]);

                    foreach($produto->ecm_tipo_produto as $ecmTipoProduto){
                        if($ecmTipoProduto->nome == "Presencial"){
                            return ['sucesso' => false, 'mensagem' => __('Turma não informada')];
                        }
                    }
                } else {
                    $presencial = $this->EcmCarrinho->EcmCarrinhoItem->EcmCursoPresencialTurma->get($idPresencial,
                        [
                            'contain' => ['EcmProduto' => ['EcmTipoProduto']],
                            'conditions'=>['EcmCursoPresencialTurma.ecm_produto_id' => $idProduto]
                        ]);
                    $item->set('ecm_curso_presencial_turma', $presencial);

                    $produto = $presencial->get('ecm_produto');
                }

                $item->set('ecm_produto', $produto);
            }catch(RecordNotFoundException $e){
                return ['sucesso' => false, 'mensagem' => __('Produto não encontrado')];
            }

            if ($carrinho->existeItem($item)) {
                $item = $carrinho->getItem($item);

                if (!is_null($modulos)) {
                    $valor = 0;
                    foreach($modulos as $modulo){
                        $valor += $modulo['valor'];
                        if(array_key_exists('mdl_course_id', $modulo)){
                            $course = $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->find()
                                ->where(['ecm_carrinho_item_id' => $item->id, 'mdl_course_id' => $modulo['mdl_course_id']])
                                ->first();
                            $course->valor = $modulo['valor'];
                            $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->save($course);
                        }else{
                            $aplicacao = $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->find()
                                ->where(['ecm_carrinho_item_id' => $item->id, 'ecm_produto_ecm_aplicacao_id' => $modulo['ecm_produto_ecm_aplicacao_id']])
                                ->first(); 
                            $aplicacao->valor = $modulo['valor'];
                            $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->save($aplicacao);
                        }
                    }
                }

                if($item->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO) {
                    $item->set('valor_produto_desconto', $valor);
                    $carrinho->addItem($item);
                    $this->salvarCarrinho($carrinho);
                }
            }
            return ['sucesso' => true];
        }else{
            return $validarRequisicao;
        }
    }


    protected function validarRequisicao(){
        if(!$this->request->is('post')){
            return ['sucesso' => false, 'mensagem' => __('Deve ser feito uma requisição do tipo POST')];
        }

        /**
         * Verificar regra de negocio
         *
        $carrinho = $this->getCarrinho();
        if(!is_null($carrinho->get('mdl_user_modified_id')) && $carrinho->get('mdl_user_modified_id') != $this->Auth->user('id')){
            return ['sucesso' => false, 'mensagem' => __('O usuário não pode alterar o carrinho')];
        }*/

        if(empty($this->request->data) || is_null($this->request->data('produto'))) {
            return  ['sucesso' => false, 'mensagem' => __('Parâmetros não informados')];
        }else {
            $idProduto = $this->request->data('produto');
            $idPresencial = $this->request->data('presencial');
            $quantidadeParam = $this->request->data('quantidade');
            $removeTudo = $this->request->data('remover_tudo');

            if (!is_numeric($idProduto) && !is_array($idProduto) && !is_string($idProduto)) {
                return ['sucesso' => false, 'mensagem' => __('Parâmetro produto incorreto')];
            }

            if (!is_null($quantidadeParam) && !is_numeric($quantidadeParam)) {
                return ['sucesso' => false, 'mensagem' => __('Parâmetro quantidade incorreto')];
            }

            //if (!is_null($idPresencial) && !is_numeric($idPresencial)) {
            if (!is_null($idPresencial) && !preg_match("/A?[0-9]+/i", $idPresencial)) {
                return ['sucesso' => false, 'mensagem' => __('Parâmetro presencial/aplicação incorreto')];
            }

            if(!is_null($removeTudo) && !is_numeric($removeTudo) && ($removeTudo != 1 || $removeTudo != 0)){
                return ['sucesso' => false, 'mensagem' => __('Parâmetro remover_tudo incorreto')];
            }
        }

        return true;
    }

    protected function getCarrinho(){
        $carrinho = $this->request->session()->read('carrinho');

        if(is_null($carrinho) || ($carrinho->get('status') != 'Em Aberto'
                && $this->request->action != 'agendamento')) {
            $carrinho = $this->EcmCarrinho->newEntity();
            $carrinho->set('ecm_carrinho_item', array());
            $carrinho->set('status', EcmCarrinho::STATUS_EM_ABERTO);

            $this->request->session()->write('carrinho', $carrinho);
            
        } else {
            if($carrinho->get('status') != 'Em Aberto' &&
              ($carrinho->get('status') != 'Finalizado' && $this->request->action != 'agendamento')) {

                $carrinho = $this->EcmCarrinho->newEntity();
                $carrinho->set('ecm_carrinho_item', array());
                $carrinho->set('status', EcmCarrinho::STATUS_EM_ABERTO);
           

            if ($this->request->session()->check('cupom'))
                $this->request->session()->delete('cupom');

            }
            
        }

        if(is_null($carrinho->get('ecm_alternative_host_id')) || !$carrinho->calcularTotal()){
            $alternativeHostId = $this->request->session()->read('alternativeHostId');
            if(!is_numeric($alternativeHostId) || !$carrinho->calcularTotal())
                $alternativeHostId = $this->validaAlternativeHost();

            if(is_numeric($alternativeHostId)){
                $carrinho->set('ecm_alternative_host_id', $alternativeHostId);
                $this->request->session()->write('carrinho', $carrinho);
            }
        }

        if(!is_null($this->Auth->user()) && (
            is_null($carrinho->get('mdl_user_id')) ||
                ($this->Auth->user('id') != $carrinho->get('mdl_user_id') &&
                    $this->Auth->user('id') != $carrinho->get('mdl_user_modified_id'))
            )) {
            $carrinho->set('mdl_user_id', $this->Auth->user('id'));
            $this->request->session()->write('carrinho', $carrinho);
        }

        return $carrinho;
    }

    protected function salvarCarrinho(EcmCarrinho $carrinho){
        $this->EcmCarrinho->save($carrinho);
        $this->request->session()->write('carrinho', $carrinho);
    }

    /**
     * Função em desuso
     *
    protected function validarCupomCarrinho(){
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Cupom.EcmCupom');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $ecmCarrinho = $this->request->session()->read('carrinho');
        if(!is_null($ecmCarrinho) && !is_null($ecmCarrinho->get('ecm_carrinho_item'))
            && count($ecmCarrinho->get('ecm_carrinho_item')) > 0){

            $usuario = $this->EcmCarrinho->MdlUser->newEntity($this->Auth->user());
            $idAlternativeHost = $this->request->session()->read('alternativeHostId');

            $alternativeHost = $this->EcmAlternativeHost->newEntity();
            $alternativeHost->set('id', $idAlternativeHost);

            $cupons = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarCupons($usuario, $alternativeHost);
            $cupons = array_merge($cupons, $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarCupons(null, $alternativeHost));

            foreach($cupons as $key => $cupom){
                $ecmCupomMdlUser = $this->EcmCupom->find()->where(['id' => $cupom->id])
                    ->matching('EcmCupomMdlUser', function($q)use($usuario){
                        return $q->where(['mdl_user_id' => $usuario->id]);
                    });
                $ecmCupomCampanha = $this->EcmCupom->find()->where(['id' => $cupom->id])
                    ->matching('EcmCupomCampanha', function($q)use($usuario){
                        return $q->where(['email' => $usuario->email]);
                    });
                if($cupom->tipo_aquisicao == 0 && !$ecmCupomMdlUser->first()){
                    unset($cupons[$key]);
                } else if($cupom->tipo_aquisicao == 2 && !$ecmCupomCampanha->first()){
                    unset($cupons[$key]);
                }
            }

            $cupom = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarMelhorCupom($cupons, $ecmCarrinho, $usuario->id);

            if(!is_null($cupom) && is_null($ecmCarrinho->get('mdl_user_modified_id'))){
                $this->request->session()->write('cupom', $cupom);
                $ecmCarrinho->calcularItens($cupom);

                $this->EcmCarrinho->save($ecmCarrinho);
                $this->request->session()->write('carrinho', $ecmCarrinho);
            }
        }
    }*/

    /**
     *  Agendar produtos do carrinho apos a finalização da compra
     */
    protected function agendar(){
        if(!$this->request->is('post')) {
            return ['sucesso' => false, 'mensagem' => 'Deve ser feito uma requisição do tipo POST'];
        } else if(!empty($this->request->data)) {
            $this->loadModel('Carrinho.EcmAgendamentoProduto');
            if(array_key_exists('datainicio', $this->request->data) || array_key_exists('ecm_carrinho_item_id', $this->request->data) ||
                    array_key_exists('item', $this->request->data) || array_key_exists('duracao', $this->request->data)){
                $data = $this->request->data;
                unset($this->request->data);
                $this->request->data = [0 => $data];
            }
            foreach($this->request->data as $data){
                if(isset($data['datainicio']) && !empty($data['datainicio'])) {
                    $data["datainicio"] = date_create_from_format('j/m/Y H:i:s', $data["datainicio"] . ' 00:00:00');
                } else {
                    return ['sucesso' => false, 'mensagem' => 'Parâmetro datainicio não informado'];
                }
                if(!isset($data['ecm_carrinho_item_id']) || !is_numeric($data['ecm_carrinho_item_id'])){
                    if(isset($data['item']) && is_numeric($data['item'])){
                        $data['ecm_carrinho_item_id'] = $data['item'];
                        unset($data['item']);
                    }else{
                        return ['sucesso' => false, 'mensagem' => 'Parâmetro item não informado'];
                    }
                }
                if(!isset($data['duracao']) || !is_numeric($data['duracao']))
                    return ['sucesso' => false, 'mensagem' => 'Parâmetro duracao não informado'];
                if(isset($data['pedido']) && !is_numeric($data['pedido']))
                    return ['sucesso' => false, 'mensagem' => 'Parâmetro pedido incorreto'];

                $where = ['ecm_carrinho_item_id' => $data['ecm_carrinho_item_id']];
                if($this->EcmAgendamentoProduto->exists($where)) {
                    $ecmAgendamentoProduto = $this->EcmAgendamentoProduto->find()->where($where)->first();
                } else {
                    $ecmAgendamentoProduto = $this->EcmAgendamentoProduto->newEntity();
                }
                $ecmAgendamentoProduto = $this->EcmAgendamentoProduto->patchEntity($ecmAgendamentoProduto, $data);
                if (!$this->EcmAgendamentoProduto->save($ecmAgendamentoProduto))
                    return ['sucesso' => false, 'mensagem' => 'Não foi possivel agendar o produto'];
            }
            $this->request->session()->delete('carrinho');
            $this->request->session()->delete('cupom');
        } else {
            return ['sucesso' => false, 'mensagem' => 'Parametros obrigatórios não encontrados.'];
        }
        return ['sucesso' => true];
    }

    private function verificarFaseExistente($idProduto){
        $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto, [
                'contain' => ['EcmTipoProduto']
            ]);

        $isFaseTrilha = EcmTipoProduto::verificarTipoProduto($produto->get('ecm_tipo_produto'), 47);

        if($isFaseTrilha) {
            $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();
            $item->set('ecm_produto', $produto);

            $carrinho = $this->getCarrinho();

            if($carrinho->existeItem($item)) {
                $item = $carrinho->getItem($item);
                if ($item->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO)
                    return true;
            }
        }

        return false;
    }


    /**
     * return json array 
     */
    public function produtosAltoqi() {
        if ($this->request->is('ajax') || $this->request->is('post')) {
            $this->loadModel('Produto.EcmProduto');
            if(array_key_exists('decript_tw', $this->request->data) && count($this->request->data) == 1){
                $retorno = $this->EcmProduto->decriptCodigotw($this->request->data['decript_tw']);
                //$retorno = json_encode($retorno);
            } else if(!array_key_exists('codigo_tw', $this->request->data)){
                /*if(!array_key_exists('edicao', $this->request->data))
                    $this->request->data['edicao'] = date("y");*/

                $modulos = [];
                $where = ['ativo' => 1];

                //aplicacao
                if(array_key_exists('app', $this->request->data)) {
                    $aplicacao = ['LITE', 'FLEX', 'BASIC', 'PRO', 'PLENA', 'MODULO'];
                    $where['aplicacao'] = $aplicacao[$this->request->data['app']];
                }
                //conexao
                $conexao = ['MONO','REDE'];
                $where['conexao'] = $conexao[$this->request->data['conexao']];
                //licenca
                if(!array_key_exists('licenca', $this->request->data))
                    $this->request->data['licenca'] = 'VITALÍCIA';

                $licenca = ['LTEMP' => 'LTEMP', 'VITALÍCIA' => 'INDET', 'LANUAL' => 'LANUAL'];
                $where['licenca'] = $licenca[$this->request->data['licenca']];
                //ativacao
                $where['ativacao'] = $this->request->data['ativacao'];
                //especiais
                $especiais = ['ativa' => 'ATIVAÇÃO', 'renova' => 'RENOVAÇÃO', 'update' => 'UPGRADE'];
                $where['especiais'] = $especiais[$this->request->data['especiais']];

                if(!array_key_exists('tempo-up', $this->request->data) OR empty($this->request->data['tempo-up']))
                    $this->request->data['tempo-up'] = 1;

                //modulos_linha
                //mudanca_de_aplicacao
                $mudanca_de_versao = [
                    [$this->request->data['edicao'],$this->request->data['edicao']],
                    ['17','18'],
                    ['04','18'],
                    ['09','18'],
                    ['10','18'],
                    ['18','19']
                ];
                $modulos_linha = ['Light' => '02 - LIGHT', 'Essencial' => '03 - ESSENCIAL', 'Top' => '04 - TOP'];
                switch($this->request->data['produto']){
                    case 'ebv':
                        $where['codigo'] = 'EBV';
                        if(!empty($this->request->data['modulos_ltemp']))
                            $where['modulos_linha'] = $modulos_linha[$this->request->data['modulos_ltemp']];
                        if(array_key_exists('up-app', $this->request->data)) {
                            $aplicacao = ['L','F','B','P','PL'];
                            $mudanca_de_aplicacao = [
                                [$aplicacao[$this->request->data['app']], $aplicacao[$this->request->data['app']]],
                                ['F','B'],
                                ['F','P'],
                                ['F','PL'],
                                ['L','B'],
                                ['L','P'],
                                ['L','PL'],
                                ['B','P'],
                                ['B','PL'],
                                ['P','PL']
                            ];
                            if ($this->request->data['licenca'] == 'LTEMP') {
                                $mudanca_de_modulos = [
                                    [substr($this->request->data['modulos_ltemp'],0,1), substr($this->request->data['modulos_ltemp'],0,1)],
                                    ['L','E'],
                                    ['L','T'],
                                    ['E','T']
                                ];
                                $where['mudanca_de_aplicacao'] =
                                    $mudanca_de_aplicacao[$this->request->data['up-app']][0].
                                    $mudanca_de_modulos[$this->request->data['up-mod']][0];
                                    //$mudanca_de_aplicacao[$this->request->data['up-app']][1].
                                    //$mudanca_de_modulos[$this->request->data['up-mod']][1];
                            } else if($this->request->data['licenca'] == 'VITALÍCIA'){
                                $where['tabela'] = 'cli';
                                $where['mudanca_de_aplicacao'] = //($this->request->data['up-app'] ? 'A' : 'V').
                                    $mudanca_de_aplicacao[$this->request->data['up-app']][0].
                                    $mudanca_de_versao[$this->request->data['up-vs']][0];
                                    //$mudanca_de_aplicacao[$this->request->data['up-app']][1].
                                    //$this->request->data['edicao'];
                            } else {
                                $aplicacao = ['','BA','PR','PL'];
                                $where['mudanca_de_aplicacao'] = //'V' .
                                    $aplicacao[$this->request->data['app']] . '18';
                                    //$aplicacao[$this->request->data['app']] . '19';
                            }
                        }
                        break;
                    case 'qib':
                        $where['codigo'] = 'QIB';
                        if ($this->request->data['licenca'] == 'LTEMP') {
                            if ($this->request->data['edicao'] == '18') {
                                $linhas_json = ["hidraulica" => "H", "eletrica" => "E", "preventivos" => "SP", "predial" => "IP"];
                                $modulos_linha = "B";
                                $this->request->data['linhas_json'] = json_decode($this->request->data['linhas_json']);
                                foreach ($linhas_json as $key => $value) {
                                    if (in_array($key, $this->request->data['linhas_json']))
                                        $modulos_linha .= $value;
                                }
                                array_push($where, 'modulos_linha LIKE "%' . $modulos_linha . '"');
                            } else {
                                $where['modulos_linha'] = $modulos_linha[$this->request->data['modulos_ltemp']];
                            }
                        } else {
                            $aplicacao = ['','BA','','PL'];
                            if (!empty($this->request->data['modulos_ltemp'])) {
                                $this->request->data['up-vs'] = 5;
                                array_push($where, 'modulos_linha LIKE "%' . $this->request->data['modulos_ltemp'] . '"');
                            } else {
                                $where['modulos_linha'] = '01 - STANDARD';
                                array_push($where, 'codigo LIKE "QIB%"');
                            }

                            if($this->request->data['especiais'] == 'update')
                                $where['mudanca_de_aplicacao'] =
                                    $aplicacao[$this->request->data['app']].
                                    $mudanca_de_versao[$this->request->data['up-vs']][0];
                                    //$aplicacao[$this->request->data['app']].
                                    //$this->request->data['edicao'];
                        }
                        break;
                    case 'mod':
                        $where['tabela'] = 'PRE';
                        $where['licenca'] = 'INDET';
                        $modulos_json = json_decode($this->request->data['modulos_json']);
                        array_push($where, 'codigo REGEXP "' . implode('|',$modulos_json) . '"');
                        break;
                    case 'ebvqib':
                        $where['codigo'] = 'EBV';
                        if ($this->request->data['licenca'] == 'LTEMP'){
                            if($where['aplicacao'] == "FLEX")
                                array_push($where, 'modulos_linha LIKE "02 - LIGHT"');
                            else
                                array_push($where, 'modulos_linha LIKE "%' . $this->request->data['modulos_ltemp'] . '"');
                        }

                        if(array_key_exists('up-app', $this->request->data)) {
                            $aplicacao = ['L','F','B','P','PL'];
                            $mudanca_de_aplicacao = [
                                [$aplicacao[$this->request->data['app']], $aplicacao[$this->request->data['app']]],
                                ['F','B'],
                                ['F','P'],
                                ['F','PL'],
                                ['L','B'],
                                ['L','P'],
                                ['L','PL'],
                                ['B','P'],
                                ['B','PL'],
                                ['P','PL']
                            ];
                            if($this->request->data['licenca'] == 'VITALÍCIA'){
                                $where['tabela'] = 'cli';
                                $where['mudanca_de_aplicacao'] =
                                    $mudanca_de_aplicacao[$this->request->data['up-app']][0].
                                    $mudanca_de_versao[$this->request->data['up-vs']][0];
                            } else {
                                $where['mudanca_de_aplicacao'] =
                                    $mudanca_de_aplicacao[$this->request->data['up-app']][0].
                                    $mudanca_de_aplicacao[$this->request->data['up-app']][1];
                            }
                        }
                }

                $ecmProdutoEcmAplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                    ->contain(['EcmProdutoAplicacao'])->where($where);

                if($this->request->data['produto'] == 'mod'){
                    $modulos = $ecmProdutoEcmAplicacao->toArray();
                    $ecmProdutoEcmAplicacao = array_shift($modulos);
                } else {
                    $edicao = '20'.$this->request->data['edicao'];
                    $ecmProdutoEcmAplicacao = $ecmProdutoEcmAplicacao->where(['edicao' => $edicao]);

                    if($where['aplicacao'] == "FLEX")
                        $ecmProdutoEcmAplicacao = $ecmProdutoEcmAplicacao
                            ->matching('EcmProduto.EcmTipoProduto', function($q){
                                return $q->where(['parcela' => intval($this->request->data['tempo-up']) * 12, 
                                    'EcmTipoProduto.id' => 58]); // Pacotes AltoQi
                            })
                            ->matching('EcmProdutoAplicacao', function($q)use($where){
                                return $q->where(['licenca' => $where['licenca']]);
                            })->first();
                    else if($this->request->data['produto'] != 'ebvqib')
                        $ecmProdutoEcmAplicacao = $ecmProdutoEcmAplicacao
                            ->matching('EcmProduto.EcmTipoProduto', function($q){
                                return $q->where(['EcmTipoProduto.id' => 48]);//Produtos AltoQi
                            })->first();
                    else 
                        $ecmProdutoEcmAplicacao = null;
                }

                /*echo '<pre>';
                    var_dump($where);
                    var_dump($ecmProdutoEcmAplicacao);
                echo '</pre>'; die;*/

                if(empty($ecmProdutoEcmAplicacao)){
                    if(Configure::read('debug')){
                        Log::debug('Produto não encontrado: "./Carrinho/src/Controller/CarrinhoController::produtosAltoqi"');
                        Log::debug($where);
                    }
                    $retorno = ['codigo_tw' => '-', 'descricao' => 'Produto não encontrado', 'qtd_parcelas' => 0];
                } else {
                    $mdlCourses = $this->EcmProduto->MdlCourse->find()->contain(['EcmProduto'])
                        ->contain(['EcmProdutoMdlCourse' => function($q)use($ecmProdutoEcmAplicacao){
                            return $q->matching('EcmProduto', function($q)use($ecmProdutoEcmAplicacao){
                                return $q->where(['EcmProduto.id' => $ecmProdutoEcmAplicacao->ecm_produto_id]);
                            })->select(['id', 'ecm_produto_id', 'mdl_course_id', 'preco']);
                        }])->matching('EcmProduto', function($q)use($ecmProdutoEcmAplicacao){
                            return $q->where(['EcmProduto.id' => $ecmProdutoEcmAplicacao->ecm_produto_id]);
                        });

                    $modulos = array_merge($modulos, $mdlCourses->toArray());

                    // MÓDULO NEXT
                    if (strpos($this->request->data['produto'], 'ebv') !== false) {
                        $next = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                            ->contain(['EcmProdutoAplicacao'])->where([
                                'ativacao' => $where['ativacao'],
                                'licenca'   => $where['licenca'],
                                'especiais' => $where['especiais'],
                                'codigo'    => 'EB045',
                                'edicao'    => '20'.$this->request->data['edicao']
                            ]);
                        if($this->request->data['produto'] == 'ebvqib'){
                            $next = $next->where(['ecm_produto_id' => $ecmProdutoEcmAplicacao->ecm_produto_id]);
                        }
                        $next = $next->first();
    
                        if(!is_null($next))
                            array_push($modulos, $next);
                    }
                    // Qi NEXT
                    if (strpos($this->request->data['produto'], 'qib') !== false) {
                        $next = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                            ->contain(['EcmProdutoAplicacao'])->where([
                                'ativacao' => $where['ativacao'],
                                'licenca'       => $where['licenca'],
                                'especiais'     => $where['especiais'],
                                'codigo'        => 'QIB',
                                'modulos_linha' => '07 - TIPO',
                                'aplicacao'     => $where['aplicacao'],
                                'edicao'        => '20'.$this->request->data['edicao']
                            ]);
                        if($this->request->data['produto'] == 'ebvqib'){
                            $next = $next->where(['ecm_produto_id' => $ecmProdutoEcmAplicacao->ecm_produto_id]);
                        }
                        $next = $next->first();

                        if(!is_null($next))
                            array_push($modulos, $next);
                    }
                    
                    /*echo '<pre>';
                        var_dump($where);
                        var_dump($modulos);
                    echo '</pre>'; die;*/

                    $modulos_linha = [
                        'QIBHID' => '08 - QH',
                        'QIBINC' => '09 - QI',
                        'QIBGAS' => '10 - QG',
                        'QIBELT' => '11 - QE',
                        'QIBCAB' => '12 - QC',
                        'QIBSPD' => '13 - QS',
                        'QIBALV' => '14 - QA',
                        'QIBEDT' => '19 - QEA', 'QIBEDIT' => '19 - QEA'
                    ];

                    $itens_json = [];
                    if(array_key_exists('itens_json', $this->request->data))
                        $itens_json = json_decode($this->request->data['itens_json']);

                    $where['edicao'] = '20'.$this->request->data['edicao'];

                    if($this->request->data['produto'] == 'qib'){
                        unset($where['modulos_linha']);
                        if ($this->request->data['licenca'] == 'VITALÍCIA' || $where['conexao'] == 'REDE' || (
                        $this->request->data['licenca'] == 'LTEMP' && $this->request->data['edicao'] == '18') ) {
                            
                            unset($where[0]);
                            if(!empty($itens_json))
                                array_shift($itens_json);

                            foreach ($itens_json as $key => $val){
                                if(array_key_exists($val, $modulos_linha))
                                    $itens_json[$key] = $modulos_linha[$val];
                            }

                            if(count($itens_json) > 0)
                                array_push($where, 'modulos_linha REGEXP "' . implode('|', $itens_json) . '"');

                        } else {
                            if ($this->request->data['licenca'] == 'LANUAL')
                                unset($where[0]);

                            if($this->request->data['modulos_ltemp'] == 'Light' || intval($this->request->data['edicao']) > 19) {
                                $where['ativacao'] = 'REMOTA';

                                foreach ($itens_json as $key => $val){
                                    if(substr($val, -2) == "BA" || substr($val, -2) == "PR")
                                        $val = substr($val, 0, -2);

                                    if(array_key_exists($val, $modulos_linha))
                                        $itens_json[$key] = $modulos_linha[$val];
                                }
                            } else {
                                foreach ($itens_json as $key => $val){
                                    if (preg_match("/(QIBALV|QIBEDT|QIBEDIT)/", $val)) 
                                        $itens_json[$key] = $modulos_linha[$val];
                                }
                                if($this->request->data['modulos_ltemp'] == 'Essencial')
                                    array_push($itens_json, $this->request->data['familia']);
                                else if($this->request->data['edicao'] == '18') {
                                    array_push($itens_json, '15 - CPH');
                                    array_push($itens_json, '16 - CPE');
                                } else
                                    array_push($itens_json, '35 - CPT');
                            }
                            array_push($where, 'modulos_linha REGEXP "' . implode('|', $itens_json) . '"');

                        }

                        if(!empty($itens_json)){
                            $modulos = array_merge($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                ->contain(['EcmProdutoAplicacao'])->where($where)->toArray());
                        }

                    } else {

                        if($this->request->data['produto'] == 'ebvqib'){
                            $where['codigo'] = 'QIB';
                            $where['edicao'] = '20'.$this->request->data['edicao'];

                            $qiBuilder = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                ->contain(['EcmProdutoAplicacao'])->where($where);

                            if($where['aplicacao'] == "FLEX" && $where['licenca'] != "INDET"){
                                $qiBuilder = $qiBuilder
                                    ->matching('EcmProduto.EcmTipoProduto', function($q){
                                        return $q->where(['parcela' => intval($this->request->data['tempo-up']) * 12, 
                                            'EcmTipoProduto.id' => 58]); // Pacotes AltoQi
                                    })
                                    ->matching('EcmProdutoAplicacao', function($q)use($where){
                                        return $q->where(['licenca' => $where['licenca']]);
                                    });
                            }

                            $modulos = array_merge($modulos, [$qiBuilder->first()]);

                            if(is_null($modulos[0]))
                                unset($modulos[0]);

                            unset($where[0]);
                            unset($where['ativacao']);

                            if($where['aplicacao'] == "FLEX"){
                                if($this->request->data['modulos_ltemp'] == 'Light')
                                    array_push($where, 'LEFT(modulos_linha,2) > 7');
                            } else {
                                if($this->request->data['modulos_ltemp'] == 'Light' || intval($this->request->data['edicao']) > 19) {
                                    $where['ativacao'] = 'REMOTA';
    
                                    foreach ($itens_json as $key => $val){
                                        if(substr($val, -2) == "BA" || substr($val, -2) == "PR")
                                            $val = substr($val, 0, -2);

                                        if(array_key_exists($val, $modulos_linha))
                                            $itens_json[$key] = $modulos_linha[$val];
                                    }
                                } else {
                                    foreach ($itens_json as $key => $val){
                                        if (preg_match("/(QIBALV|QIBEDT|QIBEDIT)/i", $val)) 
                                            $itens_json[$key] = $modulos_linha[$val];
                                    }
                                    if($this->request->data['modulos_ltemp'] == 'Essencial')
                                        array_push($itens_json, $this->request->data['familia']);
                                    else if($this->request->data['edicao'] == '18'){
                                        array_push($itens_json, '15 - CPH');
                                        array_push($itens_json, '16 - CPE');
                                    } else
                                        array_push($itens_json, '35 - CPT');
                                }
                                array_push($where, 'modulos_linha REGEXP "' . implode('|', $itens_json) . '"');
                            }

                            $where['tabela !='] = 'EDU';
                            
                    //echo '<pre>'; var_dump($where); die;

                            if($where['aplicacao'] == "FLEX")
                                $modulos = array_merge($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                    ->contain(['EcmProdutoAplicacao'])->where($where)
                                    ->matching('EcmProduto.EcmTipoProduto', function($q){
                                        return $q->where(['parcela' => intval($this->request->data['tempo-up']) * 12, 
                                            'EcmTipoProduto.id' => 58]); // Pacotes AltoQi
                                    })
                                    ->matching('EcmProdutoAplicacao', function($q)use($where){
                                        return $q->where(['licenca' => $where['licenca']]);
                                    })
                                    ->toArray());
                            else
                                $modulos = array_merge($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                    ->contain(['EcmProdutoAplicacao'])->where($where)
                                    ->matching('EcmProduto.EcmTipoProduto', function($q){
                                        return $q->where(['EcmTipoProduto.id' => 48]);//Produtos AltoQi
                                    })->toArray());

                            //echo '<pre>';
                                //var_dump($where);
                                //var_dump($modulos);
                            //echo '</pre>'; die;
                            
                        }

                        if($this->request->data['licenca'] != 'VITALÍCIA'){
                            unset($where[1]);
                            unset($where['codigo']);
                            unset($where['tabela']);
                            unset($where['ativacao']);

                            if(array_key_exists('MOD-EB041', $this->request->data)) {
                                $where['modulos_linha'] = '05 - ALVENARIA';
                                array_push($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                    ->contain(['EcmProdutoAplicacao'])->where($where)->first());
                            }
                            if(array_key_exists('MOD-EB033', $this->request->data)) {
                                $where['modulos_linha'] = '06 - PRÉ-MOLDADO';
                                array_push($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                    ->contain(['EcmProdutoAplicacao'])->where($where)->first());
                            }
                        }

                        if($this->request->data['produto'] != 'mod' && $this->request->data['licenca'] == 'VITALÍCIA' && $this->request->data['especiais'] == 'ativa') {
                            $where['ativacao'] = 'REMOTA';
                            $where['aplicacao'] = 'MODULO';
                            $where['tabela'] = 'PRE';
                            $where['edicao'] = '20'.$this->request->data['edicao'];
                            if(array_key_exists(0, $where))
                                unset($where[0]);
                            if(array_key_exists('codigo', $where))
                                unset($where['codigo']);
                            if(array_key_exists('modulos_linha', $where))
                                unset($where['modulos_linha']);

                            $modulos_json = [];
                            foreach($this->request->data as $key => $value){
                                $exp_key = explode('-', $key);
                                if($exp_key[0] == 'MOD' && strpos($value, 'EB0') !== false){
                                    $modulos_json[] = $value;
                                }
                            }

                            if(!empty($modulos_json)){
                                array_push($where, ['codigo REGEXP "' . implode('|', $modulos_json) . '"']);
                                $modulos = array_merge($modulos, $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                    ->contain(['EcmProdutoAplicacao'])->where($where)->toArray()); // Modulos Eberick Vitalicia
                            }
                        }
                    }

                    /*echo '<pre>';
                        //var_dump($where);
                        var_dump($modulos);
                    echo '</pre>'; die;*/

                    if(!empty($modulos)){
                        foreach($modulos as $modulo){
                            $modulo->modulos_linha_num = (int) filter_var($modulo->modulos_linha, FILTER_SANITIZE_NUMBER_INT);
                            $modulo->calcularValor = $modulo->modulos_linha_num < 8 || ($modulo->modulos_linha_num > 13 && $modulo->modulos_linha_num < 20);
                        }
                    }

                    //Calculos dos valores da aplicação e seus modulos
                    if($this->request->data['especiais'] == 'update' && 
                    strtoupper(substr($ecmProdutoEcmAplicacao->ecm_produto_aplicacao->mudanca_de_aplicacao,0,1)) != 'F'){
                        $valor = $this->EcmProduto->upgradeProdutoAltoqi($ecmProdutoEcmAplicacao, $modulos, $this->request->data);
                    }else{
                        $modulos_calculo = [];
                        if(!empty($modulos)){
                            foreach($modulos as $modulo){
                                if ($modulo->calcularValor)
                                    array_push($modulos_calculo, $modulo);
                            }
                        }
                        $valor = $this->EcmProduto->calcularProdutoAltoqi($ecmProdutoEcmAplicacao, $modulos_calculo, $this->request->data);
                    }

                    // Geração de codigo tw dinamico para as aplicações
                    if(!empty($modulos)){
                        foreach($modulos as $key => $modulo){
                            $modulo->modulos = count($modulos);
                            if ($this->request->data['produto'] == 'ebvqib')
                                $modulo->modulos--;
    
                            $modulos[$key] = $this->EcmProduto->encriptCodigotw($modulo, $modulo->calcularValor, $this->request->data);
                        }
                    }

                    $ecmProdutoEcmAplicacao->modulos = !empty($modulos);
                    $retorno = $this->EcmProduto->encriptCodigotw($ecmProdutoEcmAplicacao, true, $this->request->data);

                    // Desconto para combo
                    if ($this->request->data['produto'] == 'ebvqib' && $this->request->data['app'] != '1' && (
                    !array_key_exists('up-app', $this->request->data) || 
                    !intval($this->request->data['up-app']) || 
                    intval($this->request->data['up-app']) > 3 ) ){
                        $retorno['valor_unitario'] *= 0.75;
                        $valor *= 0.75;
                        foreach($modulos as &$modulo)
                            $modulo['valor_unitario'] *= 0.75;
                    }
                    
                    // Calculo final dos valores
                    if(is_array($valor)){
                        $retorno = array_merge($retorno, $valor);
                    }else{
                        $frete = 0;
                        if(array_key_exists('frete', $this->request->data) && $this->request->data['frete']){
                            $frete = 60;
                            $valor += $frete;
                        }
                        $retorno['frete'] = $frete;

                        $retorno['valor_total']     = $valor;
                        $retorno['valor_parcelado'] = $valor / 12;
                        $retorno['qtd_parcelas']    = 12;
                    }
                    $retorno['modulos'] = $modulos;

                    //$retorno = json_encode($retorno);
                }
            } else {
                $decript_tw = $this->EcmProduto->decriptCodigotw($this->request->data['codigo_tw']);
                $where = ['EcmProdutoEcmAplicacao.ecm_aplicacao_id' => $decript_tw->ecm_aplicacao_id];
                if(array_key_exists('edicao', $this->request->data))
                    $where['EcmProdutoEcmAplicacao.edicao'] = '20'.$this->request->data['edicao'];//date('Y'),

                $sigla = '';
                if($this->request->data['produto'] == 'ebvqib'){
                    if(!array_key_exists('tempo-up', $this->request->data) OR empty($this->request->data['tempo-up'])){
                        $this->request->data['tempo-up'] = 1;
                    }
                    
                    if($this->request->data['licenca'] != "VITALÍCIA"){
                        if($this->request->data['edicao'] == '19'){
                            $sigla = 'PFB'.(intval($this->request->data['tempo-up'])*12);
                        }else if($this->request->data['edicao'] == '20'){
                            if($this->request->data['tempo-up'] > 1){
                                $sigla = 'LTEMPFLEX'.(intval($this->request->data['tempo-up'])*12);
                            }else{
                                $sigla = 'LTEMPFLEX';
                            }
                        }
                    }

                    $aplicacoes = [$this->EcmProduto->EcmProdutoEcmAplicacao->find()
                        ->contain(['EcmProdutoAplicacao'])
                        ->matching('EcmProduto.EcmTipoProduto', function($q)use($sigla){
                            if(empty($sigla))
                                return $q->where(['EcmTipoProduto.id' => 58]);//Pacotes AltoQi

                            return $q->where(['EcmTipoProduto.id' => 58, 'sigla' => $sigla]);
                        })
                        ->where($where)->first()];
                }

                if(!isset($aplicacoes) || is_null($aplicacoes[0])){
                    $aplicacoes = [$this->EcmProduto->EcmProdutoEcmAplicacao->find()
                        ->contain(['EcmProdutoAplicacao'])
                        ->notMatching('EcmProduto.EcmTipoProduto', function($q){
                            return $q->where(['EcmTipoProduto.id' => 58]);//Pacotes AltoQi
                        })
                        ->where($where)->first()];

                    if($this->request->data['produto'] == 'ebvqib'){
                        if(array_key_exists('modulos', $this->request->data)) {
                            foreach($this->request->data['modulos'] as $key => $modulo){
                                if(array_key_exists('codigo_tw', $modulo)) {
                                    if($decript_tw = $this->EcmProduto->decriptCodigotw($modulo['codigo_tw'])){
                                        $modulos_linha = (int) filter_var($decript_tw->modulos_linha, FILTER_SANITIZE_NUMBER_INT);
                                        if(($modulos_linha < 8 && $aplicacoes[0]->ecm_produto_aplicacao->licenca == 'INDET') || $modulos_linha < 7){
                                            $where['EcmProdutoEcmAplicacao.ecm_aplicacao_id'] = $decript_tw->ecm_aplicacao_id;

                                            array_push($aplicacoes, 
                                                $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                                    ->contain(['EcmProdutoAplicacao'])
                                                    ->notMatching('EcmProduto.EcmTipoProduto', function($q){
                                                        return $q->where(['EcmTipoProduto.id' => 58]);//Pacotes AltoQi
                                                    })
                                                    ->where($where)->first());

                                            unset($this->request->data['modulos'][$key]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $data = $this->request->data;
                $especiais = [ 'ativa' => 'ATIVAÇÃO', 'renova' => 'RENOVAÇÃO', 'update' => 'UPGRADE'];
                $data['especiais'] = $especiais[$data['especiais']];

                $this->loadModel('Carrinho.EcmCarrinhoItem');
                foreach($aplicacoes as $key => $ecmProdutoEcmAplicacao){

                    $modulos = [];
                    if(array_key_exists('modulos', $data)) {
                        foreach($data['modulos'] as $key2 => $modulo){
                            if($this->EcmProduto->exists(['sigla' => $modulo['codigo_tw']])){
                                $aplicacao = $this->EcmProduto->MdlCourse->find()
                                    ->contain(['EcmProdutoMdlCourse' => function($q)use($ecmProdutoEcmAplicacao){
                                        return $q->where(['ecm_produto_id' => $ecmProdutoEcmAplicacao->ecm_produto_id]);
                                    }])
                                    ->matching('EcmProduto', function($q)use($modulo){
                                        return $q->where(['sigla' => $modulo['codigo_tw']]);
                                    })->first();

                                array_push($modulos, $aplicacao);
                                unset($data['modulos'][$key2]);
                            } else {
                                $decript_tw = $this->EcmProduto->decriptCodigotw($modulo['codigo_tw']);
                                $where['EcmProdutoEcmAplicacao.ecm_aplicacao_id'] = $decript_tw->ecm_aplicacao_id;

                                if($data['produto'] == 'ebvqib' && count($aplicacoes) == 1){
                                    $aplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                        ->contain(['EcmProdutoAplicacao'])
                                        ->matching('EcmProduto.EcmTipoProduto', function($q)use($sigla){
                                            if(empty($sigla))
                                                return $q->where(['EcmTipoProduto.id' => 58]);//Pacotes AltoQi

                                            return $q->where(['EcmTipoProduto.id' => 58, 'sigla' => $sigla]);
                                        })
                                        ->where($where)->first();

                                    array_push($modulos, $aplicacao);
                                    unset($data['modulos'][$key2]);
                                } else if($data['produto'] != 'ebvqib' || 
                                substr($decript_tw->codigo,0,2) == substr($ecmProdutoEcmAplicacao->ecm_produto_aplicacao->codigo,0,2)){
                                    $aplicacao = $this->EcmProduto->EcmProdutoEcmAplicacao->find()
                                        ->contain(['EcmProdutoAplicacao'])
                                        ->notMatching('EcmProduto.EcmTipoProduto', function($q){
                                            return $q->where(['EcmTipoProduto.id' => 58]);//Pacotes AltoQi
                                        })
                                        ->where($where)->first();

                                    if(!empty($aplicacao)){
                                        array_push($modulos, $aplicacao);
                                        unset($data['modulos'][$key2]);
                                    }
                                }
                            }
                        }
                    }

                    if($data['especiais'] == 'update'){
                        $valor = $this->EcmProduto->upgradeProdutoAltoqi($ecmProdutoEcmAplicacao, $modulos, $data);
                        $valor = $valor['valor_total'];
                    }else{
                        $modulos_calculo = [];

                        if(count($modulos) > 0){
                            foreach($modulos as $modulo){
                                if(isset($modulo->ecm_produto_aplicacao->modulos_linha)){
                                    $modulo->modulos_linha_num = (int) filter_var($modulo->ecm_produto_aplicacao->modulos_linha, FILTER_SANITIZE_NUMBER_INT);
                                    if ($modulo->modulos_linha_num < 8 || ($modulo->modulos_linha_num > 13 && $modulo->modulos_linha_num < 20)){
                                        array_push($modulos_calculo, $modulo);
                                    }
                                } else {
                                    array_push($modulos_calculo, $modulo);
                                }
                            }
                        }
                        
                        $valor = $this->EcmProduto->calcularProdutoAltoqi($ecmProdutoEcmAplicacao, $modulos_calculo, $data);
                    }

                    $ecmCarrinho = $this->getCarrinho();
                    
                    $this->request->data = array('quantidade' => 1, 
                            'produto' => $ecmProdutoEcmAplicacao->ecm_produto_id, 
                            'aplicacao' => $ecmProdutoEcmAplicacao->id);

                    $ecm_carrinho_item = $ecmCarrinho->ecm_carrinho_item;
                    foreach($ecm_carrinho_item as $key => $val) {
                        if(!is_null($val->aplicacao) && $val->aplicacao->id == $ecmProdutoEcmAplicacao->id){
                            if(isset($val->modulos) && count($val->modulos) > 0){
                                foreach($val->modulos as $modulo){
                                    if(!in_array($modulo, $modulos)){
                                        $this->request->data['aplicacao'] = $modulo->id;
                                        unset($ecm_carrinho_item[$key]);
                                        break;
                                    }
                                }
                            }
                        } else {
                            unset($ecm_carrinho_item[$key]);
                        }
                    }

                    $retorno = ['sucesso' => true];
                    if(empty($ecm_carrinho_item)) {
                        $retorno = $this->addItem();
                        $item = end($ecmCarrinho->ecm_carrinho_item);
                    } else {
                        $item = end($ecm_carrinho_item);
                        if($item->get('status') == "Adicionado")
                            $item->set('quantidade', $item->get('quantidade') + 1);
                        else if($item->get('status') == "Removido"){
                            $item->set('status', "Adicionado");
                            $item->set('quantidade', 1);
                        }
                    }
                    
                    $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->deleteAll(['ecm_carrinho_item_id' => $item->id]);
                    $this->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->deleteAll(['ecm_carrinho_item_id' => $item->id]);

                    if(array_key_exists('rede', $data) && $data['rede'] > 1)
                        $item->ecm_produto->nome .= ' x ' . $data['rede'];

                    $item->set('valor_produto', $valor);
                    // Desconto para combo
                    if($data['produto'] == 'ebvqib' && count($aplicacoes) > 1){
                        $valor *= 0.75;
                    }
                    $frete = 0;
                    if(array_key_exists('frete', $data) && $data['frete'] && !$key){
                        $frete = 60;
                        $valor += $frete;
                    }
                    $item->set('valor_produto_desconto', $valor);

                    $ecmCarrinhoItemEcmProdutoAplicacao = $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->newEntity();
                    $ecmCarrinhoItemEcmProdutoAplicacao->ecm_carrinho_item_id = $item->id;
                    $ecmCarrinhoItemEcmProdutoAplicacao->ecm_produto_ecm_aplicacao_id = $ecmProdutoEcmAplicacao->id;
                    
                    if(($data['produto'] == 'ebvqib' && count($aplicacoes) == 1))
                        $ecmCarrinhoItemEcmProdutoAplicacao->valor = $ecmProdutoEcmAplicacao->vl_sugerido;
                    else 
                        $ecmCarrinhoItemEcmProdutoAplicacao->valor = $this->EcmProduto->calcularProdutoAltoqi($ecmProdutoEcmAplicacao, [], $data);

                    if(array_key_exists('rede', $data))
                        $ecmCarrinhoItemEcmProdutoAplicacao->qtde_pontos_rede = $data['rede'];

                    $ecmCarrinhoItemEcmProdutoAplicacao->frete = $frete;
                    if(array_key_exists('obs', $data)) $ecmCarrinhoItemEcmProdutoAplicacao->observacao = $data['obs'];
                    $ecmCarrinhoItemEcmProdutoAplicacao->ativacao = $data['ativacao'];
                    $ecmCarrinhoItemEcmProdutoAplicacao->tipo = $data['especiais'];
                    if(array_key_exists('tempo', $data))
                        $ecmCarrinhoItemEcmProdutoAplicacao->tempo = $data['tempo'];
                    if(array_key_exists('upapp', $data))
                        $ecmCarrinhoItemEcmProdutoAplicacao->upgrade = $data['upapp'];

                    $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->save($ecmCarrinhoItemEcmProdutoAplicacao);

                    foreach($modulos as $modulo) {
                        if (isset($modulo->ecm_produto_mdl_course)) {
                            $ecmCarrinhoItemMdlCourse = $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->newEntity();
                            $ecmCarrinhoItemMdlCourse->ecm_carrinho_item_id = $item->id;
                            $ecmCarrinhoItemMdlCourse->mdl_course_id = $modulo->id;

                            $ecmCarrinhoItemMdlCourse->valor = $this->EcmProduto->calcularProdutoAltoqi($modulo, [], $data);

                            $this->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->save($ecmCarrinhoItemMdlCourse);
                        }else if (isset($modulo)){
                            $ecmCarrinhoItemEcmProdutoAplicacao = $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->newEntity();
                            $ecmCarrinhoItemEcmProdutoAplicacao->ecm_carrinho_item_id = $item->id;
                            $ecmCarrinhoItemEcmProdutoAplicacao->ecm_produto_ecm_aplicacao_id = $modulo->id;
                            $ecmCarrinhoItemEcmProdutoAplicacao->valor = $modulo->vl_sugerido;
                            $ecmCarrinhoItemEcmProdutoAplicacao->valor = $this->EcmProduto->calcularProdutoAltoqi($modulo, [], $data);

                            if(array_key_exists('rede', $data))
                                $ecmCarrinhoItemEcmProdutoAplicacao->qtde_pontos_rede = $data['rede'];

                            $ecmCarrinhoItemEcmProdutoAplicacao->ativacao = $data['ativacao'];
                            $ecmCarrinhoItemEcmProdutoAplicacao->tipo = $data['especiais'];
                            if(array_key_exists('tempo', $data))
                                $ecmCarrinhoItemEcmProdutoAplicacao->tempo = $data['tempo'];
                            if(array_key_exists('upapp', $data))
                                $ecmCarrinhoItemEcmProdutoAplicacao->upgrade = $data['upapp'];

                            $this->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->save($ecmCarrinhoItemEcmProdutoAplicacao);
                        }
                    }

                    $item->set('codigo_tw', $data['codigo_tw']);
                    if(array_key_exists('descricao', $data)) $item->set('descricao', $data['descricao']);
                    if(array_key_exists('rede', $data))  $item->set('rede',  $data['rede']);
                    if(array_key_exists('frete', $data)) $item->set('frete', $data['frete']);
                    
                    $item->set('aplicacao', $ecmProdutoEcmAplicacao);
                    $item->set('modulos', $modulos);

                    $ecmCarrinho->addItem($item);
                    $this->salvarCarrinho($ecmCarrinho);
            
                }
                
            }

            if(!is_array($retorno)){
                $retorno_aux = (array) $retorno;
                $retorno = [];
                foreach($retorno_aux as $key => $val){
                    if (strpos($key, 'properties') !== false) {
                        foreach ($val as $k => $v)
                            $retorno[$k] = $v;
                    }
                }
            }

            foreach($retorno as $key => $val)
                $$key = $val;
                
            if(!$this->request->is('ajax')) return $retorno;

            $this->set(compact(array_keys($retorno)));
            $this->set('_serialize', array_keys($retorno));

        } else {
            $updates = ['BA17BA18' => 'DE: BASIC 2017 PARA: BASIC 2018', 'AB18PL18' => 'DE: V2018 BASIC PARA: V2018 PLENA', 'AB18P18' => 'DE: V2018 BASIC PARA: V2018 PRO', 'AL18B18' => 'DE: V2018 LITE PARA: V2018 BASIC', 'AL18PL18' => 'DE: V2018 LITE PARA: V2018 PLENA', 'AL18P18' => 'DE: V2018 LITE PARA: V2018 PRO', 'AP18PL18' => 'DE: V2018 PRO PARA: V2018 PLENA', 'BEBT' => 'DE: BASIC ESSENCIAL PARA: BASIC TOP', 'BEPLE' => 'DE: BASIC ESSENCIAL PARA: PLENA ESSENCIAL', 'BEPLT' => 'DE: BASIC ESSENCIAL PARA: PLENA TOP', 'BEPE' => 'DE: BASIC ESSENCIAL PARA: PRO ESSENCIAL', 'BEPT' => 'DE: BASIC ESSENCIAL PARA: PRO TOP', 'BLBE' => 'DE: BASIC LIGHT PARA: BASIC ESSENCIAL', 'BLBT' => 'DE: BASIC LIGHT PARA: BASIC TOP', 'BLPLE' => 'DE: BASIC LIGHT PARA: PLENA ESSENCIAL', 'BLPLT' => 'DE: BASIC LIGHT PARA: PLENA TOP', 'BLPE' => 'DE: BASIC LIGHT PARA: PRO ESSENCIAL', 'BLPL' => 'DE: BASIC LIGHT PARA: PRO LIGHT', 'BLPT' => 'DE: BASIC LIGHT PARA: PRO TOP', 'BTPLE' => 'DE: BASIC TOP PARA: PLENA ESSENCIAL', 'BTPLT' => 'DE: BASIC TOP PARA: PLENA TOP', 'BTPE' => 'DE: BASIC TOP PARA: PRO ESSENCIAL', 'BTPT' => 'DE: BASIC TOP PARA: PRO TOP', 'PL17PL18' => 'DE: PLENA 2017 PARA: PLENA 2018', 'PLEPLT' => 'DE: PLENA ESSENCIAL PARA: PLENA TOP', 'PLLPLT' => 'DE: PLENA LIGHT PARA: PLENA TOP', 'PLLPLE' => 'DE: PLENA LIGHT PLENA ESSENCIALPARA: ', 'PEPLT' => 'DE: PRO ESSENCIAL PARA: PLENA TOP', 'PEPT' => 'DE: PRO ESSENCIAL PARA: PRO TOP', 'PEPLE' => 'DE: PRO ESSENCIAL PLENA ESSENCIALPARA: ', 'PLPLE' => 'DE: PRO LIGHT PARA: PLENA ESSENCIAL', 'PLPLT' => 'DE: PRO LIGHT PARA: PLENA TOP', 'PLPE' => 'DE: PRO LIGHT PARA: PRO ESSENCIAL', 'PLPT' => 'DE: PRO LIGHT PARA: PRO TOP', 'PTPLT' => 'DE: PRO TOP PARA: PLENA TOP', 'PTPLE' => 'DE: PRO TOP PLENA ESSENCIALPARA: ', 'VB09B18' => 'DE: V09 BASIC PARA: V2018 BASIC', 'VL09L18' => 'DE: V09 LITE PARA: V2018 LITE', 'VPL09PL18' => 'DE: V09 PLENA PARA: V2018 PLENA', 'VP09P18' => 'DE: V09 PRO PARA: V2018 PRO', 'VB10B18' => 'DE: V10 BASIC PARA: V2018 BASIC', 'AB10PL18' => 'DE: V10 BASIC PARA: V2018 PLENA', 'AB10P18' => 'DE: V10 BASIC PARA: V2018 PRO', 'AL10B18' => 'DE: V10 LITE PARA: V2018 BASIC', 'VL10L18' => 'DE: V10 LITE PARA: V2018 LITE', 'AL10PL18' => 'DE: V10 LITE PARA: V2018 PLENA', 'AL10P18' => 'DE: V10 LITE PARA: V2018 PRO', 'VPL10PL18' => 'DE: V10 PLENA PARA: V2018 PLENA', 'AP10PL18' => 'DE: V10 PRO PARA: V2018 PLENA', 'VP10P18' => 'DE: V10 PRO PARA: V2018 PRO'];

            $modulos_json = '[{ "nome": "Vigas com mesa colaborante", "codigo": "EB002", "grupo": "Light", "tipo": "TIPO-I" },{ "nome": "Memorial de cálculo", "codigo": "EB010", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Viga com variação de seção no trecho", "codigo": "EB015", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Aberturas em vigas e lajes", "codigo": "EB012", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Pilares e Vigas inclinados", "codigo": "EB005", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Lajes treliçadas 1D e 2D", "codigo": "EB017", "grupo": "Light", "tipo": "TIPO-II"},{ "nome": "Lajes nervuradas", "codigo": "EB018", "grupo": "Light", "tipo": "TIPO-II"},{ "nome": "Biblioteca de detalhes típicos", "codigo": "EB011", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Lançamento de estacas isoladas", "codigo": "EB013", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Vigas curvas", "codigo": "EB016", "grupo": "Light", "tipo": "TIPO-II"},{ "nome": "Tubulões", "codigo": "EB023", "grupo": "Light", "tipo": "TIPO-II"},{ "nome": "Verificação em situação de incêndio", "codigo": "EB031", "grupo": "Light", "tipo": "TIPO-III"},{ "nome": "Região maciça em lajes", "codigo": "EB036", "grupo": "Light", "tipo": "TIPO-II"},{ "nome": "Rampas", "codigo": "EB004", "grupo": "Light", "tipo": "TIPO-I"},{ "nome": "Pilares com seção composta", "codigo": "EB001", "grupo": "Essencial", "tipo": "TIPO-I"},{ "nome": "Lajes com vigotas protendidas", "codigo": "EB006", "grupo": "Essencial", "tipo": "TIPO-I"},{ "nome": "Paredes de contenção", "codigo": "EB009", "grupo": "Essencial", "tipo": "TIPO-I"},{ "nome": "Planta de locação das estacas", "codigo": "EB008", "grupo": "Essencial", "tipo": "TIPO-I"},{ "nome": "Estacas metálicas", "codigo": "EB014", "grupo": "Essencial", "tipo": "TIPO-I"},{ "nome": "Pilares esbeltos e pilar parede", "codigo": "EB020", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Blocos com mais de 6 estacas", "codigo": "EB022", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Radier", "codigo": "EB024", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Escadas especiais", "codigo": "EB025", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Muros de concreto", "codigo": "EB026", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Muros de gravidade", "codigo": "EB027", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Reservatórios elevados", "codigo": "EB028", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Reservatórios enterrados", "codigo": "EB029", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Plastificação das lajes", "codigo": "EB019", "grupo": "Essencial", "tipo": "TIPO-II"},{ "nome": "Lajes Planas", "codigo": "EB030", "grupo": "Essencial", "tipo": "TIPO-III"},{ "nome": "Fundações associadas", "codigo": "EB007", "grupo": "Top", "tipo": "TIPO-I"},{ "nome": "Vinculos elásticos para fundações", "codigo": "EB003", "grupo": "Top", "tipo": "TIPO-I"},{ "nome": "Sapata corrida em apoio elástico", "codigo": "EB021", "grupo": "Top", "tipo": "TIPO-II"},{ "nome": "Editor de Grelhas", "codigo": "EB037", "grupo": "Top", "tipo": "TIPO-II"},{ "nome": "Integração com Adapt", "codigo": "EB040", "grupo": "Top", "tipo": "TIPO-IV"},{ "nome": "Exportador para o SAP 2000", "codigo": "EB044", "grupo": "Top", "tipo": "TIPO-III"},{ "nome": "Análise dos efeitos dinâmicos devidos ao vento", "codigo": "EB043", "grupo": "Top", "tipo": "TIPO-IV"},{ "nome": "Concreto de alto desempenho", "codigo": "EB038", "grupo": "Top", "tipo": "TIPO-III"},{ "nome": "Dimensionamento de Alvenaria Estrutural", "codigo": "EB041", "grupo": "Alv", "tipo": "TIPO-IV"},{ "nome": "Pré-Moldados", "codigo": "EB033", "grupo": "Pre", "tipo": "TIPO-IV"},{ "nome": "MNEXT", "codigo": "MNEXT", "grupo": "MNEXT", "tipo": "TIPO-IV"}]';

            $produtos_json = '[{ "nome": "QiBuilder", "codigo": "QIB", "grupo": "all" },
                               { "nome": "QiHidrossanitário", "codigo": "QIBHID", "grupo": "hidraulica" },
                               { "nome": "QiIncêndio", "codigo": "QIBINC", "grupo": "preventivos" },
                               { "nome": "QiGás", "codigo": "QIBGAS", "grupo": "predial" },
                               { "nome": "QiElétrico", "codigo": "QIBELT", "grupo": "eletrica" },
                               { "nome": "QiCabeamento", "codigo": "QIBCAB", "grupo": "predial" },
                               { "nome": "QiSPDA", "codigo": "QIBSPD", "grupo": "preventivos" },
                               { "nome": "QiAlvenaria", "codigo": "QIBALV", "grupo": "alvenaria" },
                               { "nome": "QiEditor de Armaduras", "codigo": "QIBEDIT", "grupo": "all"},
                               { "nome": "QIBNEXT", "codigo": "QBNEXT", "grupo": "all"}]';
            
            $modulos = json_decode($modulos_json);
            $produtos = json_decode($produtos_json);

            $this->set(compact('modulos', 'modulos_json', 'produtos_json', 'produtos', 'updates'));
            $this->set('_serialize', ['modulos', 'modulos_json', 'produtos_json', 'produtos', 'updates']);
        }
    }


}