<?php
namespace Repasse\Controller;

use Repasse\Controller\AppController;

/**
 * EcmRepasseCategorias Controller
 *
 * @property \Repasse\Model\Table\EcmRepasseCategoriasTable $EcmRepasseCategorias */
class EcmRepasseCategoriasController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ecmRepasseCategorias = $this->paginate($this->EcmRepasseCategorias);

        $this->set(compact('ecmRepasseCategorias'));
        $this->set('_serialize', ['ecmRepasseCategorias']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Repasse Categoria id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmRepasseCategoria = $this->EcmRepasseCategorias->get($id, [
            'contain' => ['EcmRepasse']
        ]);

        $this->set('ecmRepasseCategoria', $ecmRepasseCategoria);
        $this->set('_serialize', ['ecmRepasseCategoria']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmRepasseCategoria = $this->EcmRepasseCategorias->newEntity();
        if ($this->request->is('post')) {
            $ecmRepasseCategoria = $this->EcmRepasseCategorias->patchEntity($ecmRepasseCategoria, $this->request->data);
            if ($this->EcmRepasseCategorias->save($ecmRepasseCategoria)) {
                $this->Flash->success(__('The ecm repasse categoria has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm repasse categoria could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmRepasseCategoria'));
        $this->set('_serialize', ['ecmRepasseCategoria']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Repasse Categoria id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmRepasseCategoria = $this->EcmRepasseCategorias->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmRepasseCategoria = $this->EcmRepasseCategorias->patchEntity($ecmRepasseCategoria, $this->request->data);
            if ($this->EcmRepasseCategorias->save($ecmRepasseCategoria)) {
                $this->Flash->success(__('The ecm repasse categoria has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm repasse categoria could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmRepasseCategoria'));
        $this->set('_serialize', ['ecmRepasseCategoria']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Repasse Categoria id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmRepasseCategoria = $this->EcmRepasseCategorias->get($id);
        $this->loadModel('Repasse.EcmRepasse');
        $ecmRepasse = $this->EcmRepasse->find('all', [
            'conditions' => ['ecm_repasse_categorias_id' => $id]
        ])->count();

        if($ecmRepasse){
            $ecmRepasseCategoria->visivel = !$ecmRepasseCategoria->visivel;
            $retorno = $this->EcmRepasseCategorias->save($ecmRepasseCategoria);
        }else{
            $retorno = $this->EcmRepasseCategorias->delete($ecmRepasseCategoria);
        }

        if ($retorno)
            $this->Flash->success(__('The ecm repasse categoria has been deleted.'));
        else
            $this->Flash->error(__('The ecm repasse categoria could not be deleted. Please, try again.'));

        return $this->redirect(['action' => 'index']);
    }
}
