<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 06/12/2017
 * Time: 09:25
 */

namespace Produto\Controller;


use Cake\Event\Event;
/**
 * TrilhaController Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class TrilhaController extends ProdutoController
{

    public function beforeFilter(Event $event)
    {
        $this->tipoProduto = 46;

        if($this->request->action != 'index' AND $this->request->action != 'edit') {
            $id = $this->request->params['id'];

            $ecmProduto = $this->EcmProduto->find()
                ->matching('EcmTipoProduto', function($q){
                    return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
                })->where(['EcmProduto.id' => $id])
                ->group('EcmProduto.id')->first();

            if(is_null($ecmProduto)){
                $this->Flash->error(__('Este produto não pertence a essa categoria.'));
                return $this->redirect(['plugin' => 'Produto', 'controller' => 'EcmProduto', 'action' => 'index']);
            }
        }
        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->filtroIndex();

        $ecmProduto = $this->EcmProduto->find()
            ->matching('EcmTipoProduto', function($q) {
                return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
            })->where($this->conditions)
            ->group('EcmProduto.id');

        $sigla = $this->EcmProduto->find('list', ['keyField' => 'sigla', 'valueField' => 'sigla'])
            ->matching('EcmTipoProduto', function($q) {
                return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
            });

        $ecmProduto = $this->paginate($ecmProduto);

        $sigla = $sigla->toArray();
        array_unshift($sigla, "Todas as siglas");

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmProduto', 'sigla'));
        $this->set('_serialize', ['ecmProduto']);

        parent::index();
    }

    public function edit($id = null)
    {
        $ecmProduto = null;
        if(!is_null($id)){
            $ecmProduto = $this->EcmProduto->get($id, [
                'contain' => [
                    'EcmTipoProduto', 'EcmImagem', 'MdlCourse',
                    'MdlFase' => ['MdlCourseMdlFase'],
                    'EcmProdutoEcmProduto' => function($q){
                        return $q->contain('EcmProduto')
                            ->orderAsc('EcmProdutoEcmProduto.ordem');
                    }
                ]
            ]);

            $ecmProduto->set('ecm_produto_relacionamento', []);
            foreach($ecmProduto->get('ecm_produto_ecm_produto') as $chave => $produto){
                $ecmProduto->get('ecm_produto_relacionamento')[$chave] = $produto->get('ecm_produto_relacionamento_id');
            }

        }else
            $ecmProduto = $this->EcmProduto->newEntity();

        if ($this->request->is('post') || $this->request->is('put')) {
            $tipoproduto = array("46");
            foreach ($this->request->data as $key => $value) {
                if (strpos($key, "selectTipo") !== false) {
                    if ($value == "1") {
                        $idTipo = explode("_", $key);
                        array_shift($idTipo);
                        $tipoproduto = array_merge($tipoproduto, $idTipo);
                    }
                    unset($this->request->data[$key]);
                }
            }
            $tipoproduto = array_unique($tipoproduto);

            $this->request->data["ecm_tipo_produto"] = array("_ids" => $tipoproduto);
            $this->request->data["refcurso"] = "true";

            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

            $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

            if (empty($ecmProduto->get('referencia')))
                $ecmProduto->set('referencia', null);

            if ($this->EcmProduto->save($ecmProduto)) {
                $ecm_produto = $this->request->data('ecm_produto_ordem');
                $ecm_produto = explode(',', $ecm_produto);
                if(is_array($ecm_produto)){

                    if(!is_null($id)){
                        $this->EcmProduto->EcmProdutoEcmProduto->deleteAll([
                            'ecm_produto_id' => $id
                        ]);
                    }

                    $ordemProduto = 0;
                    foreach($ecm_produto as $value){
                        $ecmProdutoEcmProduto = $this->EcmProduto->EcmProdutoEcmProduto->newEntity();
                        $ecmProdutoEcmProduto->ecm_produto_id = $ecmProduto->get('id');
                        $ecmProdutoEcmProduto->ecm_produto_relacionamento_id = $value;
                        $ecmProdutoEcmProduto->ordem = $ordemProduto++;
                        $this->EcmProduto->EcmProdutoEcmProduto->save($ecmProdutoEcmProduto);
                    }
                }

                $this->Flash->success(__('The ecm produto has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm produto could not be saved. Please, try again.'));
            }
        }

        if($this->EcmProduto->exists(['id' => $id])){
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200,
                'fields' => ['id','nome','ecm_tipo_produto_id','EcmProdutoTipoproduto.id'],
            ])->leftJoin(['EcmProdutoTipoproduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoTipoproduto.ecm_produto_id = '.$id.'
              AND EcmProdutoTipoproduto.ecm_tipo_produto_id = EcmTipoProduto.id');
        }else{
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200]);
        }

        $ecmTipoproduto->where(['habilitado' => 'true',
            'OR' => [
                'EcmTipoProduto.id IN' => [45,29,39,
                    $this->tipoProduto
                ], 'EcmTipoProduto.ecm_tipo_produto_id' => 45
            ]
        ])
            ->order(array('EcmTipoProduto.ordem' => 'ASC'));

        $ecmProduto->refcurso = true;

        $ecmProdutoFase = $this->EcmProduto->MdlFase->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ])->matching('EcmTipoProduto', function($q){
            return $q->where(['EcmTipoProduto.id' => 47]);
        });

        $this->set(compact('ecmProduto', 'ecmTipoproduto', 'ecmProdutoFase'));
        $this->set('_serialize', ['ecmProduto']);

        parent::edit();

        $this->render('edit');
    }

    public function view($id = null)
    {
        $ecmProduto = $this->EcmProduto->get($id, [
            'contain' => ['EcmTipoProduto' => ['sort' => ['EcmTipoProduto.ordem' => 'ASC']], 'MdlCourse',
                'EcmProdutoInfo' => ['EcmProdutoInfoConteudo', 'EcmProdutoInfoFaq', 'EcmProdutoInfoArquivos' => [
                    'EcmProdutoInfoArquivosTipos'
                ]], 'EcmProdutoPacote', 'EcmProdutoPrazoExtra',
                'EcmProdutoEcmProduto' => function($q){
                    return $q->contain('EcmProduto')
                        ->orderAsc('EcmProdutoEcmProduto.ordem');
                }
            ]
        ]);

        $ecmTipoProdutoAll = $this->EcmProduto->EcmTipoProduto->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => 'nome'])->toArray();

        $this->loadModel('Configuracao.EcmConfig');

        $dominioAcessoSite = $this->EcmConfig->find('all', ['fields' => 'valor'])
            ->where(['nome' => 'dominio_acesso_site'])->first()->valor;

        $this->set(compact('ecmProduto', 'ecmTipoProdutoAll', 'dominioAcessoSite'));
        $this->set('_serialize', ['ecmProduto']);

        $this->render('Produto.Produto/view');
    }

}