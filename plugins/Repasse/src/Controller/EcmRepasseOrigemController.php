<?php
namespace Repasse\Controller;

use Repasse\Controller\AppController;

/**
 * EcmRepasseOrigem Controller
 *
 * @property \Repasse\Model\Table\EcmRepasseOrigemTable $EcmRepasseOrigem */
class EcmRepasseOrigemController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ecmRepasseOrigem = $this->paginate($this->EcmRepasseOrigem);

        $this->set(compact('ecmRepasseOrigem'));
        $this->set('_serialize', ['ecmRepasseOrigem']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Repasse Origem id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmRepasseOrigem = $this->EcmRepasseOrigem->get($id, [
            'contain' => ['EcmRepasse']
        ]);

        $this->set('ecmRepasseOrigem', $ecmRepasseOrigem);
        $this->set('_serialize', ['ecmRepasseOrigem']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmRepasseOrigem = $this->EcmRepasseOrigem->newEntity();
        if ($this->request->is('post')) {
            $ecmRepasseOrigem = $this->EcmRepasseOrigem->patchEntity($ecmRepasseOrigem, $this->request->data);
            if ($this->EcmRepasseOrigem->save($ecmRepasseOrigem)) {
                $this->Flash->success(__('The ecm repasse origem has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm repasse origem could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmRepasseOrigem'));
        $this->set('_serialize', ['ecmRepasseOrigem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Repasse Origem id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmRepasseOrigem = $this->EcmRepasseOrigem->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmRepasseOrigem = $this->EcmRepasseOrigem->patchEntity($ecmRepasseOrigem, $this->request->data);
            if ($this->EcmRepasseOrigem->save($ecmRepasseOrigem)) {
                $this->Flash->success(__('The ecm repasse origem has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm repasse origem could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmRepasseOrigem'));
        $this->set('_serialize', ['ecmRepasseOrigem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Repasse Origem id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmRepasseOrigem = $this->EcmRepasseOrigem->get($id);
        $this->loadModel('Repasse.EcmRepasse');
        $ecmRepasse = $this->EcmRepasse->find('all', [
            'conditions' => ['ecm_repasse_origem_id' => $id]
        ])->count();

        if($ecmRepasse){
            $ecmRepasseOrigem->visivel = !$ecmRepasseOrigem->visivel;
            $retorno = $this->EcmRepasseOrigem->save($ecmRepasseOrigem);
        }else{
            $retorno = $this->EcmRepasseOrigem->delete($ecmRepasseOrigem);
        }

        if ($retorno)
            $this->Flash->success(__('The ecm repasse origem has been deleted.'));
        else
            $this->Flash->error(__('The ecm repasse origem could not be deleted. Please, try again.'));

        return $this->redirect(['action' => 'index']);
    }
}
