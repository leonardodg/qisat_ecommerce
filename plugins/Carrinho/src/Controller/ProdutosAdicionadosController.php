<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 01/09/2016
 * Time: 12:16
 */

namespace Carrinho\Controller;


use Cake\Validation\Validator;

class ProdutosAdicionadosController extends AppController
{

    public function index(){
        $this->loadModel('Carrinho.EcmCarrinhoItem');
        $this->loadModel('Carrinho.EcmCarrinho');

        $carrinho = $this->EcmCarrinho->newEntity();

        $conditions = ["EcmCarrinhoItem.status = 'Adicionado'"];

        if(!empty($this->request->query)){
            $validator = $this->validarDados();
            $errors = $validator->errors($this->request->query);

            if(empty($errors)) {
                $produto = $this->request->query('produto');
                $entidade = $this->request->query('entidade');
                $dataInicio = $this->request->query('data_inicio_pesquisa');
                $dataFim = $this->request->query('data_fim_pesquisa');

                if (strlen(trim($produto)) > 0) {
                    $conditions['ecm_produto_id'] = $produto;
                }

                if (strlen(trim($entidade)) > 0) {
                    $conditions['EcmAlternativeHost.id'] = $entidade;
                }

                if (strlen(trim($dataInicio)) == 10) {
                    $dataInicio = \DateTime::createFromFormat('d/m/Y', $dataInicio);
                    $dataInicio->setTime(0, 0, 0);

                    $conditions['EcmCarrinho.data >='] = $dataInicio->format('Y-m-d H:i:s');
                }

                if (strlen(trim($dataFim)) == 10) {
                    $dataFim = \DateTime::createFromFormat('d/m/Y', $dataFim);
                    $dataFim->setTime(23, 59, 59);

                    $conditions['EcmCarrinho.data <='] = $dataFim->format('Y-m-d H:i:s');
                }

                $this->request->data = $this->request->query;
            }else{
                $carrinho->errors($errors);
            }
        }

        $total = $this->EcmCarrinhoItem->find('all', [
                'fields' =>['total' => 'SUM(EcmCarrinhoItem.quantidade)']
            ])
            ->contain(['EcmProduto'=>['joinType' => 'INNER'],'EcmCarrinho'=>['EcmAlternativeHost']])
            ->where($conditions)
            ->first();

        $total = $total->total;

        $this->paginate = [
             'contain' => ['EcmProduto'=>['joinType' => 'INNER'],'EcmCarrinho'=>['EcmAlternativeHost']],

            'fields' => ['EcmProduto.nome', 'EcmProduto.sigla', 'total' => 'SUM(EcmCarrinhoItem.quantidade)','entidade' => 'EcmAlternativeHost.shortname'],
            'group' => ['EcmCarrinhoItem.ecm_produto_id', 'EcmAlternativeHost.id'],
            'order' => ['SUM(EcmCarrinhoItem.quantidade) DESC'],
            'conditions' => $conditions
        ];
        $ecmCarrinhoItem = $this->paginate($this->EcmCarrinhoItem);


        $ecmProduto = $this->EcmCarrinhoItem->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla'])->orderAsc('sigla');

        $ecmEntidade = $this->EcmCarrinhoItem->EcmCarrinho->EcmAlternativeHost->find('list',
            ['keyField' => 'id', 'valueField' => 'shortname'])->orderAsc('shortname');

        $this->set(compact('ecmCarrinhoItem','total','ecmProduto', 'ecmEntidade','carrinho'));
        $this->set('_serialize', ['ecmCarrinhoItem']);
    }

    private function validarDados(){
        $validator = new Validator();

        $validator
            ->date('data_inicio_pesquisa',['dmy'])->allowEmpty('data_inicio_pesquisa');
        $validator
            ->date('data_fim_pesquisa',['dmy'])->allowEmpty('data_fim_pesquisa');

        return $validator;
    }

}