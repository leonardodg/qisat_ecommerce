<?php
namespace Produto\Controller;

use Produto\Controller\AppController;

/**
 * EcmTipoProduto Controller
 *
 * @property \Produto\Model\Table\EcmTipoProdutoTable $EcmTipoProduto */
class EcmTipoProdutoController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */

    public function index()
    {
        $conditions = [];
        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($conditions, 'EcmTipoProduto.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['habilitado']) && !empty($this->request->query['habilitado'])){
                array_push($conditions, 'EcmTipoProduto.habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
        }

        $this->paginate = [
            'fields' => ['EcmTipoProduto.id', 'EcmTipoProduto.nome','EcmTipoProdutoAR.nome'],
            'conditions' => $conditions
        ];

        $find = $this->EcmTipoProduto->find()
            ->join([
                'EcmTipoProdutoAR'=>[
                    'table'=>'ecm_tipo_produto',
                    'type'=>'LEFT',
                    'conditions' => 'EcmTipoProdutoAR.id = EcmTipoProduto.ecm_tipo_produto_id'
                ]
            ]);
        $ecmTipoProduto = $this->paginate($find);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmTipoProduto', 'habilitado'));
        $this->set('_serialize', ['ecmTipoProduto']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Tipo Produto id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmTipoProduto = $this->EcmTipoProduto->get($id, [
            'contain' => ['EcmProduto']
        ]);

        //if(!is_null($ecmTipoProduto->ecm_tipo_produto_id)){
        if($ecmTipoProduto->ecm_tipo_produto_id > 0){
            $tipoProdutoRelacionado = $this->EcmTipoProduto->get($ecmTipoProduto->ecm_tipo_produto_id);
            $ecmTipoProduto->EcmTipoProduto = $tipoProdutoRelacionado;
        }

        $this->set('ecmTipoProduto', $ecmTipoProduto);
        $this->set('_serialize', ['ecmTipoProduto']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmTipoProduto = $this->EcmTipoProduto->newEntity();
        if ($this->request->is('post')) {
            $ecmTipoProduto = $this->EcmTipoProduto->patchEntity($ecmTipoProduto, $this->request->data);
            if ($this->EcmTipoProduto->save($ecmTipoProduto)) {
                $this->Flash->success(__('The ecm tipo produto has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm tipo produto could not be saved. Please, try again.'));
            }
        }
        $this->setOptionsView();
        $this->set(compact('ecmTipoProduto'));
        $this->set('_serialize', ['ecmTipoProduto']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Tipo Produto id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmTipoProduto = $this->EcmTipoProduto->get($id, [
            'contain' => ['EcmProduto']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmTipoProduto = $this->EcmTipoProduto->patchEntity($ecmTipoProduto, $this->request->data);
            if ($this->EcmTipoProduto->save($ecmTipoProduto)) {
                $this->Flash->success(__('The ecm tipo produto has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm tipo produto could not be saved. Please, try again.'));
            }
        }
        $this->setOptionsView($id);
        $this->set(compact('ecmTipoProduto'));
        $this->set('_serialize', ['ecmTipoProduto']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Tipo Produto id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     *
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmTipoProduto = $this->EcmTipoProduto->get($id);
        if ($this->EcmTipoProduto->delete($ecmTipoProduto)) {
            $this->Flash->success(__('The ecm tipo produto has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm tipo produto could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }*/

    private function setOptionsView($id = 0){
        $ecmProduto = $this->EcmTipoProduto->EcmProduto->find('all');
        $optionsProduto = array();
        foreach ($ecmProduto as $produto) {
            $optionsProduto[$produto->id] = $produto->nome;
        }

        $ecmTipoProduto = $this->EcmTipoProduto->find('all');
        $optionsTipoProduto = array(''=>__('Não Vincular'));
        foreach ($ecmTipoProduto as $tipoProduto) {
            if($id != $tipoProduto->id)
                $optionsTipoProduto[$tipoProduto->id] = $tipoProduto->nome;
        }

        $this->set(compact('optionsProduto','optionsTipoProduto'));
    }
}
