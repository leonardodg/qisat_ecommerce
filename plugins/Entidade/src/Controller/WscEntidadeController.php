<?php

namespace Entidade\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Carrinho\Model\Entity\EcmCarrinho;

class WscEntidadeController extends WscController
{

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /*
    * Função responsável por listar todos os descontos de todas as entidades conveniadas
    * Deve ser feito requisições do tipo GET, sem parâmetros:
    * http://{host}/entidade/wsc-entidade/entidades-conveniadas
    *
    * Retornos:
    * 1- {'sucesso':true, 'ecmAlternativeHost': Entidades com promoções e imagens}
    *
    * */
    public function entidadesConveniadas(){
        $this->loadModel('Entidade.EcmAlternativeHost');

        $retorno = $this->EcmAlternativeHost->find('all')->where(['shortname NOT LIKE' => 'QiSat'])
            ->select(['id', 'host', 'shortname'])
            ->contain(['EcmPromocao' => function($q){
                return $q->where(['habilitado' => 'true', 'datainicio <=' => date("Y-m-d"),
                    'datafim >=' => date("Y-m-d")])->select(['descontoporcentagem']);
            }, 'EcmImagem' => ['fields' => ['src', 'descricao',
                'EcmAlternativeHostEcmImagem.ecm_alternative_host_id']]])->toArray();

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por listar todos os descontos de todas as entidades conveniadas
    * Deve ser feito requisições do tipo GET, sem parâmetros:
    * http://{host}/entidade/wsc-entidade/desconto-entidades
    *
    * Retornos:
    * 1- {'sucesso': true, 'ecmProduto': Produtos por tipo com valores padrões e com desconto}
    *
    * */
    public function descontoEntidades(){
        $this->loadModel('Promocao.EcmPromocao');
        $ecmPromocao = $this->EcmPromocao->find('all')->where(['habilitado' => 'true',
            'datainicio <=' => date("Y-m-d"), 'datafim >=' => date("Y-m-d")
        ])->notMatching('EcmAlternativeHost', function($q){
            return $q->where(['shortname LIKE' => 'QiSat']);
        })->toArray();

        $this->loadModel('Produto.EcmProduto');
        $ecmProduto = $this->EcmProduto->find('all')->where(['EcmProduto.habilitado' => 'true',
            'EcmProduto.preco IS NOT NULL'])
            ->innerJoinWith('EcmTipoProduto', function ($q) {
                return $q->where(['EcmTipoProduto.nome' => 'Cursos Software'])
                    ->orWhere(['EcmTipoProduto.nome' => 'Cursos Teóricos']);
            })->select(['EcmProduto.id', 'EcmProduto.nome', 'EcmProduto.preco',
                'tipo_produto' => 'EcmTipoProduto.nome']);

        $retorno = ['sucesso' => true];
        foreach($ecmProduto as $produto){
            $produto->valor_promocional = EcmCarrinho::verificarDesconto($produto, $ecmPromocao)['valorTotal'];
            $retorno[$produto->tipo_produto == 'Cursos Software' ? 'software' : 'teoricos'][$produto->id] = $produto;
        }

        $this->set(compact('retorno'));
    }
}