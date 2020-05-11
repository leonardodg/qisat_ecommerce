<?php
namespace Instrutor\Controller;

use Instrutor\Controller\AppController;

/**
 * EcmInstrutorRedeSocial Controller
 *
 * @property \Instrutor\Model\Table\EcmInstrutorRedeSocialTable $EcmInstrutorRedeSocial */
class EcmInstrutorRedeSocialController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id = null)
    {
        if(!is_null($id)){

            $existsInstrutor = $this->EcmInstrutorRedeSocial->EcmInstrutor->exists(['EcmInstrutor.id'=>$id]);

            if($existsInstrutor) {
                $ecmInstrutor = $this->EcmInstrutorRedeSocial->EcmInstrutor->get($id,['contain' => ['MdlUser']]);

                $conditions = ['EcmInstrutorRedeSocial.ecm_instrutor_id' => $id];
                if(count($this->request->query)){
                    if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                        array_push($conditions, 'EcmInstrutorRedeSocial.ecm_rede_social_id LIKE "'.$this->request->query['nome'].'"');
                    }
                }

                $this->paginate = [
                    'contain' => ['EcmInstrutor'=>['MdlUser'], 'EcmRedeSocial' => ['EcmImagem']],
                    'sortWhitelist' => ['EcmRedeSocial.nome'],
                    'conditions' => $conditions
                ];

                $ecmInstrutorRedeSocial = $this->paginate($this->EcmInstrutorRedeSocial);

                $ecmRedeSocial = $this->EcmInstrutorRedeSocial->EcmRedeSocial->
                        find('list', ['keyField' => 'id', 'valueField' => 'nome']);

                if($this->request->is('get'))
                    $this->request->data = $this->request->query;

                $this->set(compact('ecmInstrutorRedeSocial', 'ecmInstrutor', 'ecmRedeSocial', 'id'));
                $this->set('_serialize', ['ecmInstrutorRedeSocial']);
            }else{
                $this->Flash->error(__('Instrutor não encontrado!'));
                return $this->redirect(['controller' => '','action' => 'index']);
            }
        }else{
            $this->Flash->error(__('Instrutor não encontrado!'));
            return $this->redirect(['controller' => '','action' => 'index']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Instrutor Rede Social id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->get($id, [
            'contain' => ['EcmInstrutor' => ['MdlUser', 'EcmImagem'], 'EcmRedeSocial'=>['EcmImagem']]
        ]);

        $this->set('ecmInstrutorRedeSocial', $ecmInstrutorRedeSocial);
        $this->set('_serialize', ['ecmInstrutorRedeSocial']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        if(!is_null($id)){
            $existsInstrutor = $this->EcmInstrutorRedeSocial->EcmInstrutor->exists(['EcmInstrutor.id'=>$id]);

            if($existsInstrutor) {
                $ecmInstrutor = $this->EcmInstrutorRedeSocial->EcmInstrutor->get($id,['contain' => ['MdlUser']]);

                $this->request->data['ecm_instrutor_id'] = $ecmInstrutor->id;

                $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->newEntity();
                if ($this->request->is('post')) {
                    $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->patchEntity($ecmInstrutorRedeSocial, $this->request->data);
                    if ($this->EcmInstrutorRedeSocial->save($ecmInstrutorRedeSocial)) {
                        $this->Flash->success(__('Rede social salva com sucesso'));
                        return $this->redirect(['controller' => 'rede-social', 'action' => 'index', $ecmInstrutor->id]);
                    } else {
                        $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
                    }
                }

                $ecmRedeSocial = $this->EcmInstrutorRedeSocial->EcmRedeSocial->find('list',
                    ['keyField' => 'id', 'valueField' => 'nome']);
                $this->set(compact('ecmInstrutorRedeSocial', 'ecmInstrutor', 'ecmRedeSocial'));
                $this->set('_serialize', ['ecmInstrutorRedeSocial']);
                $this->set('titulo', __('Cadastrar rede social para o instrutor').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname);
            }else{
                $this->Flash->error(__('Instrutor não encontrado!'));
                return $this->redirect(['controller' => '','action' => 'index']);
            }
        }else{
            $this->Flash->error(__('Instrutor não encontrado!'));
            return $this->redirect(['controller' => '','action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Instrutor Rede Social id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->get($id, [
            'contain' => ['EcmInstrutor' => ['MdlUser']]
        ]);
        $ecmInstrutor = $ecmInstrutorRedeSocial->ecm_instrutor;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->patchEntity($ecmInstrutorRedeSocial, $this->request->data);
            if ($this->EcmInstrutorRedeSocial->save($ecmInstrutorRedeSocial)) {
                $this->Flash->success(__('Rede social salva com sucesso'));
                return $this->redirect(['controller' => 'rede-social', 'action' => 'index', $ecmInstrutor->id]);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
            }
        }
        $ecmRedeSocial = $this->EcmInstrutorRedeSocial->EcmRedeSocial->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);
        $this->set(compact('ecmInstrutorRedeSocial', 'ecmInstrutor', 'ecmRedeSocial'));
        $this->set('_serialize', ['ecmInstrutorRedeSocial']);
        $this->set('titulo', __('Editar rede social do instrutor').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname);
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Instrutor Rede Social id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmInstrutorRedeSocial = $this->EcmInstrutorRedeSocial->get($id,['contain' => ['EcmInstrutor']]);
        $ecmInstrutor = $ecmInstrutorRedeSocial->ecm_instrutor;
        if ($this->EcmInstrutorRedeSocial->delete($ecmInstrutorRedeSocial)) {
            $this->Flash->success(__('Rede social excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir a rede social!'));
        }
        return $this->redirect(['controller' => 'rede-social', 'action' => 'index', $ecmInstrutor->id]);
    }
}
