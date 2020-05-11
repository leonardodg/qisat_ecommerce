<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EcmPermissao Controller
 *
 * @property \App\Model\Table\EcmPermissaoTable $EcmPermissao */
class EcmPermissaoController extends AppController
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
            if(isset($this->request->data['controller'])){
                $controller = $this->request->data['controller'];
                $ecmPermissao = $this->EcmPermissao->find('list', ['keyField' => 'action', 'valueField' => 'action'])
                    ->where(['controller LIKE "'.$controller.'"'])->group(['action'])->toArray();
                array_unshift($ecmPermissao, "Todas as actions");
                echo json_encode($ecmPermissao);
            } else if(isset($this->request->data['plugin'])){
                $plugin = $this->request->data['plugin'];
                $ecmPermissao = $this->EcmPermissao->find('list', ['keyField' => 'controller', 'valueField' => 'controller'])
                    ->where(['plugin LIKE "'.$plugin.'"'])->group(['controller'])->toArray();
                array_unshift($ecmPermissao, "Todos os controllers");
                echo json_encode($ecmPermissao);
            }
        }

        $conditions = [];

        if(isset($this->request->query['restricao']) && !is_numeric($this->request->query['restricao']))
            array_push($conditions, 'restricao LIKE "'.$this->request->query['restricao'].'"');

        $controllers = [];
        if(isset($this->request->query['plugin']) && $this->request->query['plugin'] != "0"){
            $plugin = $this->request->query['plugin'];
            if($plugin == "1")
                $plugin = "";
            array_push($conditions, 'plugin LIKE "'.$plugin.'"');
            $controllers = $this->EcmPermissao->find('list', ['keyField' => 'controller', 'valueField' => 'controller'])
                ->where(['plugin LIKE "'.$plugin.'"'])->group(['controller'])->toArray();
            array_unshift($controllers, "Todos os controllers");
        }

        $actions = [];
        if(isset($this->request->query['controller']) && !is_numeric($this->request->query['controller'])){
            array_push($conditions, 'controller LIKE "'.$this->request->query['controller'].'"');
            $actions = $this->EcmPermissao->find('list', ['keyField' => 'action', 'valueField' => 'action'])
                ->where(['controller LIKE "'.$this->request->query['controller'].'"'])->group(['action'])->toArray();
            array_unshift($actions, "Todas as actions");
        }

        if(isset($this->request->query['action']) && !is_numeric($this->request->query['action']))
            array_push($conditions, 'action LIKE "'.$this->request->query['action'].'"');

        $this->paginate = [
            'conditions' => $conditions
        ];
        $ecmPermissao = $this->paginate($this->EcmPermissao);

        $restricao = ['Todos', 'login' => 'Login', 'permissao' => 'Permissão', 'site' => 'Site'];

        $plugins = $this->EcmPermissao->find('list', ['keyField' => 'plugin', 'valueField' => 'plugin'])
                ->group(['plugin'])->toArray();
        $plugins = array_diff($plugins, [""]);
        array_unshift($plugins, "Todos os plugins", "Nenhum plugin");

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmPermissao', 'restricao', 'plugins', 'controllers', 'actions'));
        $this->set('_serialize', ['ecmPermissao']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Permissao id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmPermissao = $this->EcmPermissao->get($id, [
            'contain' => ['EcmGrupoPermissao']
        ]);

        $this->set('ecmPermissao', $ecmPermissao);
        $this->set('_serialize', ['ecmPermissao']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmPermissao = $this->EcmPermissao->newEntity();
        if ($this->request->is('post')) {
            $ecmPermissao = $this->EcmPermissao->patchEntity($ecmPermissao, $this->request->data);
            if ($this->EcmPermissao->save($ecmPermissao)) {
                $this->Flash->success(__('Permissão salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar a permissão!'));
            }
        }
        $this->setOptionsView();
        $this->set(compact('ecmPermissao'));
        $this->set('_serialize', ['ecmPermissao']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Permissao id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmPermissao = $this->EcmPermissao->get($id, [
            'contain' => ['EcmGrupoPermissao']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmPermissao = $this->EcmPermissao->patchEntity($ecmPermissao, $this->request->data);
            if ($this->EcmPermissao->save($ecmPermissao)) {
                $this->Flash->success(__('Permissão salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar a permissão!'));
            }
        }
        $this->setOptionsView();
        $this->set(compact('ecmPermissao'));
        $this->set('_serialize', ['ecmPermissao']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Permissao id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmPermissao = $this->EcmPermissao->get($id);
        if ($this->EcmPermissao->delete($ecmPermissao)) {
            $this->Flash->success(__('Permissão excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir a permissão!'));
        }
        return $this->redirect(['action' => 'index']);
    }

    private function setOptionsView(){
        $ecmGrupoPermissao = $this->EcmPermissao->EcmGrupoPermissao->find('all');
        $optionsGrupoPermissao = array();
        foreach ($ecmGrupoPermissao as $grupo) {
            $optionsGrupoPermissao[$grupo->id] = $grupo->nome;
        }

        $this->set(compact('optionsGrupoPermissao'));
    }
}
