<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 01/09/2016
 * Time: 08:26
 */

namespace Vendas\Controller;


use App\Controller\WscController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;

use Cake\I18n\Number;
use App\Auth\AESPasswordHasher;
use Cake\Utility\Security;

class WscVendaController extends WscController
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

    /*
    * Função reponsável por inserir um número de pedido na venda
    * Deve ser feito requisições do tipo PUT, informando os seguintes parâmetros:
     *
    * http://{host}/vendas/wsc-venda/inserir-pedido/{id venda}
     * {
    *  pedido: (valor para armazenamento)
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Este Web Service não aceita esse tipo de requisição'}
    * 3- {'sucesso':false, 'mensagem': 'Registro não encontrado'}
    * 4- {'sucesso':false, 'mensagem': 'Ocorreu um erro ao alterar o registro'}
    * 5- {'sucesso':false, 'mensagem': 'Venda já alterada'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetro pedido não informado'}
    * 7- {'sucesso':false, 'mensagem': 'Parâmetro pedido deve ser numerico'}
    * 8- {'sucesso':false, 'mensagem': 'Já existe uma venda com o pedido informado'}
    *
    **/
    public function inserirPedido($idVenda = null){
        $this->loadModel('EcmVenda');
        $retorno = ['sucesso' => false, 'mensagem' => __('Este Web Service não aceita esse tipo de requisição')];

        if($this->request->is('put')) {

            $retorno = ['sucesso' => false, 'mensagem' => __('Registro não encontrado')];

            $validaDados = $this->validarDados();
            if (is_array($validaDados)) {
                $retorno = $validaDados;
            } else {
                try {
                    $venda = $this->EcmVenda->get($idVenda);
                    if (is_null($venda->get('proposta'))) {
                        $pedido = $this->request->data('pedido');
                        $venda->set('proposta', $pedido);

                        if ($this->EcmVenda->save($venda)) {
                            $retorno = ['sucesso' => true];
                        } else {
                            $retorno = ['sucesso' => false, 'mensagem' => __('Ocorreu um erro ao alterar o registro')];
                        }

                    } else {
                        $retorno = ['sucesso' => false, 'mensagem' => __('Venda já alterada')];
                    }
                } catch (RecordNotFoundException $e) {
                }
            }
        }
        $this->set(compact('retorno'));
    }

    protected function validarDados(){
        $pedido = $this->request->data('pedido');

        if (is_null($pedido)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro pedido não informado')];
        }

        if (!is_numeric($pedido)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro pedido deve ser numerico')];
        }

        $this->loadModel('EcmVenda');

        $existVenda = $this->EcmVenda->exists([
            'proposta' => $pedido
        ]);

        if ($existVenda) {
            return ['sucesso' => false, 'mensagem' => __('Já existe uma venda com o pedido informado')];
        }
    }

    public function import()
    {
        $this->loadModel('Vendas.DbaVendas');
        $this->loadModel('Vendas.DbaVendasProdutos');
        $this->loadModel('Vendas.DbaVendasServicos');
        $this->loadModel('MdlUser');

        $dados = $this->request->data;
        $return = [ 'sucesso' => false, 'errors' => []];

        if(is_array($dados) and count($dados)){
            foreach ($dados as $data) {

                $user = false;

                try{
                    $venda = $this->DbaVendas->get($data['pedido']);
                }catch(\RecordNotFoundException $e){
                    $venda = false;
                }catch(\Exception $e){
                    $venda = false;
                    array_push($return['errors'], $e->getMessage());
                }

                if(!$venda){

                    $venda = $this->DbaVendas->newEntity($data, ['associated' => ['MdlUser']]);
                    $venda->set('data_registro', new \DateTime());
                    $venda->set('data_venda', \DateTime::createFromFormat( "Y-m-d H:i:s" ,$data['data_venda']));

                    $user = $this->MdlUser->validarUserAltoQi($data);

                    if($user){

                        $venda->set('mdl_user', $user);

                        if($this->DbaVendas->save($venda)){
                            $return['sucesso'] = true;

                            if(array_key_exists('produtos', $data)){
                                $this->DbaVendasProdutos->setDadosAltoQi($data['produtos'], $venda);

                                foreach ($data['produtos'] as $item) {
                                    $venda_item = $this->DbaVendasProdutos->newEntity($item);
                                    $venda_item->set('dba_vendas_pedido', $venda->pedido);
                                    if(!$this->DbaVendasProdutos->save($venda_item))
                                        array_push($return['errors'], $venda_item->errors());
                                }
                            }

                            if(array_key_exists('servicos', $data)){
                                foreach ($data['servicos'] as $item) {

                                    if(array_key_exists('data_inicio', $item) && !empty($item['data_fim']))
                                        $item['data_inicio'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_inicio']);
                                    
                                    if(array_key_exists('data_fim', $item) && !empty($item['data_fim']))
                                        $item['data_fim'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_fim']);

                                    $venda_item = $this->DbaVendasServicos->newEntity($item);
                                    $venda_item->set('dba_vendas_pedido', $venda->pedido);
                                    if(!$this->DbaVendasServicos->save($venda_item))
                                        array_push($return['errors'], $venda_item->errors());
                                }
                            }
                        } else{
                            array_push($return['errors'], $venda->errors());
                        }
                    }else{
                        array_push($return['errors'], __('Falha no Registro do Usuário '.$data['chave_altoqi'].' ( Pedido: '.$venda->pedido.' Não Exportado ).'));
                        array_push($return['errors'], 'Email: '.$data['entidade']['Email'].' Chave:'.$data['chave_altoqi']. ' CPF:'. $data['entidade']['Numero']);
                    }
                }else{

                        $venda = $this->DbaVendas->get($data['pedido'], ['contain' => ['DbaVendasProdutos', 'DbaVendasServicos']]);
                        $new_itens = [];

                        // atualizar DbaVendasProdutos
                        if(array_key_exists('produtos', $data)){
                            $this->DbaVendasProdutos->setDadosAltoQi($data['produtos'], $venda);

                            foreach ($data['produtos'] as $key => $item) {

                                try{
                                    $venda_item = $this->DbaVendasProdutos->get($item['registro']);
                                }catch(\RecordNotFoundException $e){
                                    $venda_item = false;
                                }catch(\Exception $e){
                                    $venda_item = false;
                                }

                                if($venda_item){
                                    // $venda_item->newEntity($item);
                                    $venda_item = $this->DbaVendasProdutos->patchEntity($venda_item, $item);

                                    if(!$this->DbaVendasProdutos->save($venda_item)){
                                        array_push($return['errors'], $venda_item->errors());
                                    }
                                }else{
                                    array_push( $new_itens, $key);
                                }
                            }
                        }
                        
                        // Inserir novos DbaVendasProdutos
                        if(count($new_itens) > 0){
                            foreach ($new_itens as $key) {
                                $venda_item = $this->DbaVendasProdutos->newEntity($data['produtos'][$key]);
                                $venda_item->set('dba_vendas_pedido', $venda->pedido);
                                if(!$this->DbaVendasProdutos->save($venda_item)){
                                    array_push($return['errors'], $venda_item->errors());
                                }
                            }
                        }

                        /* 

                        NÃO FAZER DELETE PRODUTOS - POR ENQUANTO
                        
                        if( (count($venda->dba_vendas_produtos) > 0 ) && count($data['produtos']) > 0) {
                            foreach ($venda->dba_vendas_produtos as $item) {
                                $dba_produto = array_filter( $data['produtos'], function ($i) use ($item) { return $i['registro'] == $item->get('registro'); });

                                if(!count($dba_produto)){
                                    $this->DbaVendasProdutos->delete($item));
                                }
                            }
                        }else if(count($data['produtos']) == 0){
                            foreach ($venda->dba_vendas_produtos as $item) {
                                $this->DbaVendasProdutos->delete($item));
                            }
                        }

                        */

                        $new_itens = [];

                        // atualizar DbaVendasServicos
                        if(array_key_exists('servicos', $data)){

                            foreach ($data['servicos'] as $key => $item) {

                                try{
                                    $venda_item = $this->DbaVendasServicos->get($item['registro']);
                                }catch(\RecordNotFoundException $e){
                                    $venda_item = false;
                                }catch(\Exception $e){
                                    $venda_item = false;
                                }

                                if($venda_item){

                                    $item = $data['servicos'][$key];
                                    if(array_key_exists('data_inicio', $item) && !empty($item['data_fim'])){
                                        $item['data_inicio'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_inicio']);
                                    }
                                
                                    if(array_key_exists('data_fim', $item) && !empty($item['data_fim'])){
                                        $item['data_fim'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_fim']);
                                    }

                                    $venda_item = $this->DbaVendasServicos->patchEntity($venda_item, $item);
                                    if(!$this->DbaVendasServicos->save($venda_item)){
                                        array_push($return['errors'], $venda_item->errors());
                                    }
                                }else{
                                    array_push( $new_itens, $key);
                                }
                            }
                        }
                        
                        // Inserir novos DbaVendasServicos
                        if(count($new_itens > 0)){
                            foreach ($new_itens as $key) {

                                $item = $data['servicos'][$key];
                                if(array_key_exists('data_inicio', $item) && !empty($item['data_fim'])){
                                    $item['data_inicio'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_inicio']);
                                }
                            
                                if(array_key_exists('data_fim', $item) && !empty($item['data_fim'])){
                                    $item['data_fim'] = \DateTime::createFromFormat( "Y-m-d H:i:s" ,$item['data_fim']);
                                }
                                    
                                $venda_item = $this->DbaVendasServicos->newEntity($item);
                                $venda_item->set('dba_vendas_pedido', $venda->pedido);
                                if(!$this->DbaVendasServicos->save($venda_item)){
                                    array_push($return['errors'], $venda_item->errors());
                                }
                            }
                        }

                        if(count($return['errors']) == 0){
                            $return['sucesso'] = true;
                        }
                }

            }
        }else{
            array_push($return['errors'], __('Falha nos Dados!'));
        }

        echo json_encode($return);
        die;
    }

}