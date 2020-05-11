<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EcmGrupoPermissao Controller
 *
 * @property \App\Model\Table\EcmGrupoPermissaoTable $EcmGrupoPermissao */
class EcmGrupoPermissaoController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('MutipleSelect', 'UserScript');
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
        }
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['EcmAlternativeHost']
        ];
        $ecmGrupoPermissao = $this->paginate($this->EcmGrupoPermissao);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmGrupoPermissao'));
        $this->set('_serialize', ['ecmGrupoPermissao']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Grupo Permissao id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmGrupoPermissao = $this->EcmGrupoPermissao->get($id, [
            'contain' => ['EcmPermissao', 'MdlUser', 'EcmAlternativeHost']
        ]);

        $this->set('ecmGrupoPermissao', $ecmGrupoPermissao);
        $this->set('_serialize', ['ecmGrupoPermissao']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmGrupoPermissao = $this->EcmGrupoPermissao->newEntity();
        if ($this->request->is('post')) {
            $ecmGrupoPermissao = $this->EcmGrupoPermissao->patchEntity($ecmGrupoPermissao, $this->request->data);
            if ($this->EcmGrupoPermissao->save($ecmGrupoPermissao)) {
                $this->Flash->success(__('Grupo de permissão salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o grupo de permissão!'));
            }
        }

        $optionsPermissao = $this->EcmGrupoPermissao->EcmPermissao
            ->find('list', ['keyField' => 'id', 'valueField' => 'descricao'])
            ->where(['restricao'=>'permissao']);

        $this->loadModel('Entidade.EcmAlternativeHost');
        $ecmAlternativeHost = $this->EcmAlternativeHost->find('list', [
            'keyField' => 'id', 'valueField' => 'fullname'
        ])->where(['shortname NOT LIKE "CREA%"']);

        $this->set(compact('ecmGrupoPermissao', 'optionsPermissao', 'ecmAlternativeHost'));
        $this->set('_serialize', ['ecmGrupoPermissao']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Grupo Permissao id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmGrupoPermissao = $this->EcmGrupoPermissao->get($id, [
            'contain' => ['EcmPermissao', 'MdlUser']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmGrupoPermissao = $this->EcmGrupoPermissao->patchEntity($ecmGrupoPermissao, $this->request->data);
            if ($this->EcmGrupoPermissao->save($ecmGrupoPermissao)) {
                $this->Flash->success(__('Grupo de permissão salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o grupo de permissão!'));
            }
        }

        $optionsUser = [];
        foreach($ecmGrupoPermissao->mdl_user as $user){
            $optionsUser[$user->id] = $user->firstname.' '.$user->lastname;
        }

        $optionsPermissao = $this->EcmGrupoPermissao->EcmPermissao
            ->find('list', ['keyField' => 'id', 'valueField' => 'descricao'])
            ->where(['restricao'=>'permissao']);

        $this->loadModel('Entidade.EcmAlternativeHost');
        $ecmAlternativeHost =  $this->EcmAlternativeHost->find('list', ['keyField' => 'id','valueField' => 'shortname'])
                                    ->where(['or' => ['id' => 1, 'shortname' => 'AltoQi']])->toArray();

        $this->set(compact('ecmGrupoPermissao', 'optionsPermissao', 'mdlUser', 'optionsUser', 'ecmAlternativeHost'));
        $this->set('_serialize', ['ecmGrupoPermissao']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Grupo Permissao id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmGrupoPermissao = $this->EcmGrupoPermissao->get($id);
        if ($this->EcmGrupoPermissao->delete($ecmGrupoPermissao)) {
            $this->Flash->success(__('Grupo de permissão excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir o grupo de permissão!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
