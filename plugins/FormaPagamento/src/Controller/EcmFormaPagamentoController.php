<?php
namespace FormaPagamento\Controller;

use FormaPagamento\Controller\AppController;

/**
 * EcmFormaPagamento Controller
 *
 * @property \FormaPagamento\Model\Table\EcmFormaPagamentoTable $EcmFormaPagamento */
class EcmFormaPagamentoController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('JqueryMask');
    }
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
                array_push($conditions, 'nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['descricao']) && !empty($this->request->query['descricao'])){
                array_push($conditions, 'descricao LIKE "%'.$this->request->query['descricao'].'%"');
            }
            if(isset($this->request->query['habilitado']) && !is_numeric($this->request->query['habilitado'])){
                array_push($conditions, 'habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
            if(isset($this->request->query['tipo']) && !is_numeric($this->request->query['tipo'])){
                array_push($conditions, 'tipo LIKE "'.$this->request->query['tipo'].'"');
            }
        }

        $this->paginate = ['conditions' => $conditions];

        $ecmFormaPagamento = $this->paginate($this->EcmFormaPagamento);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];
        $tipo = ['Todos', 'boleto' => 'Boleto', 'cartao' => 'Cartão', 'online' => 'Online'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmFormaPagamento', 'habilitado', 'tipo'));
        $this->set('_serialize', ['ecmFormaPagamento']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Forma Pagamento id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmFormaPagamento = $this->EcmFormaPagamento->get($id, [
            'contain' => ['EcmOperadoraPagamento', 'EcmTipoPagamento']
        ]);

        $this->set('ecmFormaPagamento', $ecmFormaPagamento);
        $this->set('_serialize', ['ecmFormaPagamento']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmFormaPagamento = $this->EcmFormaPagamento->newEntity();
        if ($this->request->is('post')) {
            $ecmFormaPagamento = $this->EcmFormaPagamento->patchEntity($ecmFormaPagamento, $this->request->data);

            if($this->request->data['todos_tipos'])
                $ecmFormaPagamento->set('ecm_tipo_produto', null);

            if ($this->EcmFormaPagamento->save($ecmFormaPagamento)) {
                $this->Flash->success(__('Forma de pagamento salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar a forma de pagamento!'));
            }
        }

        $optionsTipoProduto = $this->EcmFormaPagamento->EcmTipoProduto
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->where(['habilitado'=>'true']);

        $this->set('titulo', __('Nova Forma de Pagamento'));
        $this->set(compact('ecmFormaPagamento', 'optionsTipoProduto'));
        $this->set('_serialize', ['ecmFormaPagamento']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Forma Pagamento id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmFormaPagamento = $this->EcmFormaPagamento->get($id,
            [
                'contain' => ['EcmTipoProduto']
            ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmFormaPagamento = $this->EcmFormaPagamento->patchEntity($ecmFormaPagamento, $this->request->data);

            if($this->request->data['todos_tipos'])
                $ecmFormaPagamento->set('ecm_tipo_produto', null);

            if ($this->EcmFormaPagamento->save($ecmFormaPagamento)) {
                $this->Flash->success(__('Forma de pagamento salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar a forma de pagamento!'));
            }
        }

        $optionsTipoProduto = $this->EcmFormaPagamento->EcmTipoProduto
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->where(['habilitado'=>'true']);

        $this->set('titulo', __('Editar Forma de Pagamento'));
        $this->set(compact('ecmFormaPagamento', 'optionsTipoProduto'));
        $this->set('_serialize', ['ecmFormaPagamento']);
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Forma Pagamento id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmFormaPagamento = $this->EcmFormaPagamento->get($id);
        if ($this->EcmFormaPagamento->delete($ecmFormaPagamento)) {
            $this->Flash->success(__('Forma de pagamento excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir a forma de pagamento!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
