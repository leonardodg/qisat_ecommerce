<?php
namespace FormaPagamento\Controller;

use FormaPagamento\Controller\AppController;

/**
 * EcmTipoPagamento Controller
 *
 * @property \FormaPagamento\Model\Table\EcmTipoPagamentoTable $EcmTipoPagamento */
class EcmTipoPagamentoController extends AppController
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
                array_push($conditions, 'EcmTipoPagamento.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['descricao']) && !empty($this->request->query['descricao'])){
                array_push($conditions, 'EcmTipoPagamento.descricao LIKE "%'.$this->request->query['descricao'].'%"');
            }
            if(isset($this->request->query['habilitado']) && !is_numeric($this->request->query['habilitado'])){
                array_push($conditions, 'EcmTipoPagamento.habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
        }

        $this->paginate = [
            'contain' => ['EcmFormaPagamento'],
            'sortWhitelist'=>['EcmFormaPagamento.nome'],
            'conditions' => $conditions
        ];
        $ecmTipoPagamento = $this->paginate($this->EcmTipoPagamento);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmTipoPagamento', 'habilitado'));
        $this->set('_serialize', ['ecmTipoPagamento']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Tipo Pagamento id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmTipoPagamento = $this->EcmTipoPagamento->get($id, [
            'contain' => ['EcmFormaPagamento']
        ]);

        $this->set('ecmTipoPagamento', $ecmTipoPagamento);
        $this->set('_serialize', ['ecmTipoPagamento']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmTipoPagamento = $this->EcmTipoPagamento->newEntity();
        if ($this->request->is('post')) {
            $ecmTipoPagamento = $this->EcmTipoPagamento->patchEntity($ecmTipoPagamento, $this->request->data);
            if ($this->EcmTipoPagamento->save($ecmTipoPagamento)) {
                $this->Flash->success(__('Tipo de pagamento salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o tipo de pagamento!'));
            }
        }
        $ecmFormaPagamento = $this->EcmTipoPagamento->EcmFormaPagamento->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);

        $this->set('titulo', __('Novo Tipo de Pagamento'));
        $this->set(compact('ecmTipoPagamento', 'ecmFormaPagamento'));
        $this->set('_serialize', ['ecmTipoPagamento']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Tipo Pagamento id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmTipoPagamento = $this->EcmTipoPagamento->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmTipoPagamento = $this->EcmTipoPagamento->patchEntity($ecmTipoPagamento, $this->request->data);
            if ($this->EcmTipoPagamento->save($ecmTipoPagamento)) {
                $this->Flash->success(__('Tipo de pagamento salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o tipo de pagamento!'));
            }
        }
        $ecmFormaPagamento = $this->EcmTipoPagamento->EcmFormaPagamento->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);
        $this->set('titulo', __('Editar Tipo de Pagamento'));
        $this->set(compact('ecmTipoPagamento', 'ecmFormaPagamento'));
        $this->set('_serialize', ['ecmTipoPagamento']);
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Tipo Pagamento id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmTipoPagamento = $this->EcmTipoPagamento->get($id);
        if ($this->EcmTipoPagamento->delete($ecmTipoPagamento)) {
            $this->Flash->success(__('Tipo de pagamento excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir o tipo de pagamento!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
