<?php
namespace Entidade\Controller;

use Entidade\Controller\AppController;

/**
 * EcmAlternativeHost Controller
 *
 * @property \Entidade\Model\Table\EcmAlternativeHostTable $EcmAlternativeHost */
class EcmAlternativeHostController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ecmAlternativeHost = $this->paginate($this->EcmAlternativeHost);

        $this->set(compact('ecmAlternativeHost'));
        $this->set('_serialize', ['ecmAlternativeHost']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Alternative Host id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmAlternativeHost = $this->EcmAlternativeHost->get($id, [
            'contain' => ['EcmProdutoEcmTipoProdutoEcmAlternativeHost'
                    => ['EcmProdutoEcmTipoProduto' => ['EcmProduto', 'EcmTipoProduto']],
                'EcmPromocao', 'MdlUser', 'EcmImagem', 'EcmCarrinho', 'EcmCupom']
        ]);

        $this->set('ecmAlternativeHost', $ecmAlternativeHost);
        $this->set('_serialize', ['ecmAlternativeHost']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmAlternativeHost = $this->EcmAlternativeHost->newEntity();
        if ($this->request->is('post')) {
            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'entidade');

            $ecmAlternativeHost = $this->EcmAlternativeHost->patchEntity($ecmAlternativeHost, $this->request->data);
            if ($this->EcmAlternativeHost->save($ecmAlternativeHost)) {
                $this->Flash->success(__('The ecm alternative host has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm alternative host could not be saved. Please, try again.'));
            }
        }

        $ecmPromocao = $this->EcmAlternativeHost->EcmPromocao
            ->find('list', ['keyField' => 'id', 'valueField' => 'descricao'])->order('descricao')
            ->where(['habilitado' => 'true', 'datainicio <=' => date("Y-m-d"), 'datafim >=' => date("Y-m-d")]);

        $this->set(compact('ecmAlternativeHost', 'ecmPromocao'));
        $this->set('_serialize', ['ecmAlternativeHost']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Alternative Host id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmAlternativeHost = $this->EcmAlternativeHost->get($id, [
            'contain' => ['EcmPromocao', 'EcmImagem']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'entidade');

            $ecmAlternativeHost = $this->EcmAlternativeHost->patchEntity($ecmAlternativeHost, $this->request->data);
            if ($this->EcmAlternativeHost->save($ecmAlternativeHost)) {
                $this->Flash->success(__('The ecm alternative host has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm alternative host could not be saved. Please, try again.'));
            }
        }

        $ecmPromocao = $this->EcmAlternativeHost->EcmPromocao
            ->find('list', ['keyField' => 'id', 'valueField' => 'descricao'])->order('descricao')
            ->where(['habilitado' => 'true', 'datainicio <=' => date("Y-m-d"), 'datafim >=' => date("Y-m-d")]);

        $this->set(compact('ecmAlternativeHost', 'ecmPromocao'));
        $this->set('_serialize', ['ecmAlternativeHost']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Alternative Host id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmAlternativeHost = $this->EcmAlternativeHost->get($id);
        if ($this->EcmAlternativeHost->delete($ecmAlternativeHost)) {
            $this->Flash->success(__('The ecm alternative host has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm alternative host could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
