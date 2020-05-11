<?php
namespace DuvidasFrequentes\Controller;

use DuvidasFrequentes\Controller\AppController;

/**
 * EcmDuvidasFrequentes Controller
 *
 * @property \DuvidasFrequentes\Model\Table\EcmDuvidasFrequentesTable $EcmDuvidasFrequentes */
class EcmDuvidasFrequentesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $ids = $this->request->data['ids'];
            $retorno = ['sucesso' => true];
            foreach($ids as $id => $ordem){
                if($id > 0){
                    $ecmDuvidasFrequentes = $this->EcmDuvidasFrequentes->get($id);
                    $ecmDuvidasFrequentes->ordem = $ordem;
                    $ecmDuvidasFrequentes->timemodified = date_create();
                    if(!$this->EcmDuvidasFrequentes->save($ecmDuvidasFrequentes)){
                        $retorno = ['sucesso' => false, 'mensagem' => 'Ocorreu um erro ao salvar a ordem.'];
                    }
                }
            }
            echo json_encode($retorno);
        }

        $conditions = [];
        if(count($this->request->query)){
            if(isset($this->request->query['titulo']) && !empty($this->request->query['titulo'])){
                array_push($conditions, 'titulo LIKE "%'.$this->request->query['titulo'].'%"');
            }
            if(isset($this->request->query['url']) && !empty($this->request->query['url'])){
                array_push($conditions, 'url LIKE "%'.$this->request->query['url'].'%"');
            }
        }

        $this->paginate = ['conditions' => $conditions,
            'order' => ['ordem' => 'asc']
        ];
        $ecmDuvidasFrequentes = $this->paginate($this->EcmDuvidasFrequentes);

        $count = $this->EcmDuvidasFrequentes->find()->count();
        for ($i = 1; $i <= $count; $i++)
            $ordens[$i] = $i;

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmDuvidasFrequentes', 'ordens'));
        $this->set('_serialize', ['ecmDuvidasFrequentes']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Duvidas Frequente id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->get($id);

        $this->set('ecmDuvidasFrequente', $ecmDuvidasFrequente);
        $this->set('_serialize', ['ecmDuvidasFrequente']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['timemodified'] = date_create();
            $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->patchEntity($ecmDuvidasFrequente, $this->request->data);
            if ($this->EcmDuvidasFrequentes->save($ecmDuvidasFrequente)) {
                $this->Flash->success(__('The ecm duvidas frequente has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm duvidas frequente could not be saved. Please, try again.'));
            }
        }

        $count = $this->EcmDuvidasFrequentes->find()->count() + 1;

        $this->set(compact('ecmDuvidasFrequente', 'count'));
        $this->set('_serialize', ['ecmDuvidasFrequente']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Duvidas Frequente id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data['timemodified'] = date_create();
            $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->patchEntity($ecmDuvidasFrequente, $this->request->data);
            if ($this->EcmDuvidasFrequentes->save($ecmDuvidasFrequente)) {
                $this->Flash->success(__('The ecm duvidas frequente has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm duvidas frequente could not be saved. Please, try again.'));
            }
        }

        $count = $this->EcmDuvidasFrequentes->find()->count() + 1;

        $this->set(compact('ecmDuvidasFrequente', 'count'));
        $this->set('_serialize', ['ecmDuvidasFrequente']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Duvidas Frequente id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmDuvidasFrequente = $this->EcmDuvidasFrequentes->get($id);
        if ($this->EcmDuvidasFrequentes->delete($ecmDuvidasFrequente)) {
            $this->Flash->success(__('The ecm duvidas frequente has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm duvidas frequente could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
