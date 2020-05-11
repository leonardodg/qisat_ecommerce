<?php
namespace Indicacao\Controller;

use Indicacao\Controller\AppController;

/**
 * EcmIndicacaoSegmento Controller
 *
 * @property \Indicacao\Model\Table\EcmIndicacaoSegmentoTable $EcmIndicacaoSegmento */
class EcmIndicacaoSegmentoController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ecmIndicacaoSegmento = $this->paginate($this->EcmIndicacaoSegmento);

        $this->set(compact('ecmIndicacaoSegmento'));
        $this->set('_serialize', ['ecmIndicacaoSegmento']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Indicacao Segmento id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->get($id, [
            'contain' => ['EcmIndicacaoCurso']
        ]);

        $this->set('ecmIndicacaoSegmento', $ecmIndicacaoSegmento);
        $this->set('_serialize', ['ecmIndicacaoSegmento']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->newEntity();
        if ($this->request->is('post')) {
            $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->patchEntity($ecmIndicacaoSegmento, $this->request->data);
            if ($this->EcmIndicacaoSegmento->save($ecmIndicacaoSegmento)) {
                $this->Flash->success(__('The ecm indicacao segmento has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm indicacao segmento could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmIndicacaoSegmento'));
        $this->set('_serialize', ['ecmIndicacaoSegmento']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Indicacao Segmento id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->patchEntity($ecmIndicacaoSegmento, $this->request->data);
            if ($this->EcmIndicacaoSegmento->save($ecmIndicacaoSegmento)) {
                $this->Flash->success(__('The ecm indicacao segmento has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm indicacao segmento could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ecmIndicacaoSegmento'));
        $this->set('_serialize', ['ecmIndicacaoSegmento']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Indicacao Segmento id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->get($id);
        if ($this->EcmIndicacaoSegmento->delete($ecmIndicacaoSegmento)) {
            $this->Flash->success(__('The ecm indicacao segmento has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm indicacao segmento could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
