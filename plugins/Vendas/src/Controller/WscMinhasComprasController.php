<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 05/09/2016
 * Time: 08:20
 */

namespace Vendas\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Routing\Router;

class WscMinhasComprasController extends WscController
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

    public function get($id = null){
        /*$validaDados = $this->validarDados();
        if (is_array($validaDados)) {
            $retorno = $validaDados;
        } else {*/
            if(is_null($id) || $id == "/:action")
                $id = $this->request->data('venda');
                
            if(!is_null($id) && $id != "/:action"){
                $this->loadModel('Vendas.EcmVenda');

                $venda = $this->EcmVenda->find('all')
                    ->contain([
                        'EcmOperadoraPagamento'=>['fields'=>['forma_pagamento'=>'nome',
                            'operadora'=>'descricao', 'dataname' => 'dataname']],
                        'EcmCarrinho'=>[
                            'EcmCarrinhoItem' => function ($q) {
                                return $q->contain(['EcmProduto' => ['fields' => ['EcmProduto.id', 'nome' => 'nome', 'sigla' => 'sigla'], 'EcmImagem'],
                                    'EcmPromocao'])->where(['EcmCarrinhoItem.status' => 'Adicionado']);
                            }
                        ],
                        'EcmVendaBoleto' => function ($q) {
                            return $q->where(['EcmVendaBoleto.status' => 'Em aberto']);
                        }
                    ])->where(['EcmVenda.id' => $id]);

                if($this->Auth->user()){
                    $venda = $venda->where(['EcmVenda.mdl_user_id' => $this->Auth->user('id')]);
                }

                $venda = $venda->first();

                if($venda){
                    $venda->data = $venda->data->format('U');
                    $venda->total = $venda->ecm_carrinho->calcularTotal();
                    $venda->status_carrinho = $venda->ecm_carrinho->status;
                    $venda->products = [];
                    $venda->promotions = [];
                    if(!empty($venda->ecm_venda_boleto) && strpos($venda->dataname, 'boleto') !== false)
                        $venda->boleto = Router::url(['plugin' => false, 'controller' => '',
                            'action' => str_replace('_','-',$venda->dataname), 'id' => $venda->id], true);
                    unset($venda->ecm_venda_boleto);
                    foreach($venda->ecm_carrinho->ecm_carrinho_item as $key => $item) {
                        $venda->products[$key] = $item;
                        $venda->products[$key]['preco'] = number_format($item->valor_produto_desconto,2, '.', '');
                        $venda->products[$key]['total'] = number_format($item->valor_produto_desconto * $item->quantidade,2, '.', '');
                        $venda->products[$key]['imagem'] = $item->ecm_produto->ecm_imagem;
                        foreach($venda->products[$key]['imagem'] as $imagem)
                            $imagem->src = Router::url('upload/'.$imagem->src, true);
                        unset($item->ecm_produto);
                        if(!is_null($item->ecm_promocao)){
                            $venda->promotions[$key] = $item->ecm_promocao;
                            $venda->promotions[$key]['datainicio'] = strtotime($item->ecm_promocao->datainicio->format('Y-m-d 00:00:00'));
                            $venda->promotions[$key]['datafim'] = strtotime($item->ecm_promocao->datafim->format('Y-m-d 23:59:59'));
                        }
                    }
                    unset($venda->ecm_carrinho);
                    $retorno = ['sucesso' => true, 'venda' => $venda];
                } else {
                    $retorno = ['sucesso' => false, 'mensagem' => 'Venda não encontrada'];
                }
            } else {
                $retorno = ['sucesso' => false, 'mensagem' => 'Informe o identificador da venda'];
            }
        //}

        $this->set(compact('retorno'));
    }

    public function listar(){
        $validaDados = $this->validarDados();

        if (is_array($validaDados)) {
            $retorno = $validaDados;
        } else {
            $this->loadModel('Vendas.EcmVenda');

            $listaVendas = $this->EcmVenda->find('all')
                ->contain([
                    'EcmVendaStatus',
                    'EcmOperadoraPagamento',
                    'EcmCarrinho'=>[
                        'EcmCarrinhoItem' => function ($q) {
                            return $q->contain('EcmProduto')
                                ->where(['EcmCarrinhoItem.status' => 'Adicionado']);
                        }
                    ],
                    'EcmVendaBoleto' => function ($q) {
                        return $q->where(['EcmVendaBoleto.status' => 'Em aberto']);
                    }
                ])
                ->orderDesc('EcmVenda.data')
                ->where([
                    'EcmVenda.mdl_user_id' => $this->Auth->user('id')
                ]);

            $retorno = [];

            foreach($listaVendas as $venda){
                $vendaRetorno = new \stdClass();
                $vendaRetorno->id = $venda->id;
                $vendaRetorno->pedido = $venda->id;
                $vendaRetorno->data = $venda->data->format('U');
                $vendaRetorno->valor = $venda->valor_parcelas * $venda->numero_parcelas;
                $vendaRetorno->status = $venda->ecm_venda_status->status;
                $vendaRetorno->forma_pagamento = $venda->ecm_operadora_pagamento->nome;
                //$vendaRetorno->carrinho = $this->listarItens($venda->get('ecm_carrinho'));
                $vendaRetorno->carrinho = $venda->get('ecm_carrinho')->id;
                if(!empty($venda->ecm_venda_boleto) && $venda->ecm_operadora_pagamento->dataname == "boleto")
                    $vendaRetorno->boleto = Router::url(['plugin' => false, 'controller' => '',
                        'action' =>'boleto', 'id' => $venda->id], true);

                $retorno[] = $vendaRetorno;
            }

            $retorno = ['sucesso' => true, 'venda' => $retorno];
        }

        $this->set(compact('retorno'));
    }


    protected function validarDados(){
        if (is_null($this->Auth->user())) {
            return ['sucesso' => false, 'mensagem' => __('Usuário deve fazer login!')];
        }
    }

    /*
     * Função responsável para tratar os dados de retorno da requisição
     *
     * @return array
     */
    private function listarItens($carrinho){

        unset($carrinho->mdl_user);
        unset($carrinho->mdl_user_id);
        unset($carrinho->mdl_user_modified_id);
        unset($carrinho->ecm_alternative_host_id);
        unset($carrinho->data);
        unset($carrinho->edicao);
        unset($carrinho->id);
        unset($carrinho->ecm_user_modified);
        $itens = [];

        if(!is_null($carrinho->get('ecm_carrinho_item'))){
            foreach($carrinho->get('ecm_carrinho_item') as $item){

                $item = clone $item;

                if($item->status == "Adicionado") {
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
                    unset($produto->ecm_tipo_produto);
                    $imagem = $this->getUrlImagemProduto($produto);

                    unset($produto->ecm_imagem);
                    $produto->imagem = $imagem;
                    $item->ecm_produto = $produto;

                    if (isset($item->ecm_cupom)) {
                        $cupom = clone $item->ecm_cupom;

                        unset($cupom->ecm_produto);
                        unset($cupom->ecm_tipo_produto);

                        $item->set('ecm_cupom', $cupom);
                    }
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
}