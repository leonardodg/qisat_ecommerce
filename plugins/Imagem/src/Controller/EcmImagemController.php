<?php
namespace Imagem\Controller;

use Cake\Filesystem\Folder;
use Imagem\Controller\AppController;

/**
 * EcmImagem Controller
 *
 * @property \Imagem\Model\Table\EcmImagemTable $EcmImagem */
class EcmImagemController extends AppController
{
    private $plugins = ['EcmOperadoraPagamento' => 'Operadora de Pagamento', 'EcmProduto' => 'Produto',
            'EcmInstrutor' => 'Instrutor', 'EcmRedeSocial' => 'Rede Social'];

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
        }

        $this->paginate = ['conditions' => $conditions];

        $ecmImagem = $this->paginate($this->EcmImagem);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmImagem'));
        $this->set('_serialize', ['ecmImagem']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Imagem id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmImagem = $this->EcmImagem->get($id, [
            'contain' => ['EcmProduto' => function($q){
                return $q->group(['EcmProduto.id']);
            }, 'EcmInstrutor', 'EcmOperadoraPagamento', 'EcmRedeSocial']
        ]);

        $this->set('ecmImagem', $ecmImagem);
        $this->set('_serialize', ['ecmImagem']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmImagem = $this->EcmImagem->newEntity();
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $plugin = $this->request->data['plugin'];
            if($plugin == "EcmInstrutor"){
                $retorno = $this->EcmImagem->$plugin->find('list', ['contain' => ['MdlUser'],
                    'keyField' => 'id', 'valueField' => function($q){
                        return $q->mdl_user->get('firstname')." ".$q->mdl_user->get('lastname');
                    }]);
            }else{
                $retorno = $this->EcmImagem->$plugin->find('list', [
                    'keyField' => 'id', 'valueField' => 'nome']);
            }
            echo json_encode(['retorno' => $retorno]);
        } else if ($this->request->is('post')) {
            $id = $this->request->data['associacao'];
            $plugin = $this->request->data['plugin'];
            $diretorio = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', '-$0', $plugin)), 5);
            if($plugin == "EcmOperadoraPagamento")
                $diretorio = "operadora";
            unset($this->request->data['plugin']);
            unset($this->request->data['associacao']);
            $this->request->data = $this->EcmImagem->enviarImagem([$this->request->data], $diretorio)[0];
            $ecmImagem = $this->EcmImagem->patchEntity($ecmImagem, $this->request->data);
            if ($this->EcmImagem->save($ecmImagem)) {
                $params = ['ecm_imagem_id' => $ecmImagem->id];
                if($plugin == "EcmProduto"){
                    $plugin = "EcmProdutoEcmImagem";
                    $associacao = $this->EcmImagem->$plugin->newEntity();
                    $params['ecm_produto_id'] = $id;
                } else {
                    $associacao = $this->EcmImagem->$plugin->get($id, ['contain' => ['EcmImagem']]);
                }
                $associacao = $this->EcmImagem->$plugin->patchEntity($associacao, $params);
                if ($this->EcmImagem->$plugin->save($associacao)) {
                    $this->Flash->success(__('The ecm imagem has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The association could not be saved. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The imagem could not be saved. Please, try again.'));
            }
        }

        $plugins = $this->plugins;

        $ecmOperadora = $this->EcmImagem->EcmOperadoraPagamento->find('list', [
            'keyField' => 'id', 'valueField' => 'nome']);

        $this->set(compact('ecmImagem', 'plugins', 'ecmOperadora'));
        $this->set('_serialize', ['ecmImagem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Imagem id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmImagem = $this->EcmImagem->get($id, [
            'contain' => ['EcmOperadoraPagamento', 'EcmProduto', 'EcmRedeSocial',
                'EcmInstrutor' => ['MdlUser']]
        ]);
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $plugin = $this->request->data['plugin'];
            if($plugin == "EcmInstrutor"){
                $retorno = $this->EcmImagem->$plugin->find('list', ['contain' => ['MdlUser'],
                    'keyField' => 'id', 'valueField' => function($q){
                        return $q->mdl_user->get('firstname')." ".$q->mdl_user->get('lastname');
                    }]);
            }else{
                $retorno = $this->EcmImagem->$plugin->find('list', [
                    'keyField' => 'id', 'valueField' => 'nome']);
            }
            $key = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', '_$0', $plugin)), 1);
            if(!empty($ecmImagem[$key])){
                if(is_array($ecmImagem[$key])){
                    echo json_encode(['retorno' => $retorno, 'default' => $ecmImagem[$key][0]->id]);
                }else{
                    echo json_encode(['retorno' => $retorno, 'default' => $ecmImagem[$key]->id]);
                }
            } else {
                echo json_encode(['retorno' => $retorno]);
            }
        } else if ($this->request->is('post') || $this->request->is('put')) {
            $plugin = $this->request->data['plugin'];
            $diretorio = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', '-$0', $plugin)), 5);
            if($plugin == "EcmOperadoraPagamento")
                $diretorio = "operadora";
            unset($this->request->data['plugin']);
            unset($this->request->data['associacao']);
            if(empty($this->request->data['nome']['tmp_name'])) {
                unset($this->request->data['nome']);
            } else {
                $this->request->data = $this->EcmImagem->enviarImagem([$this->request->data], $diretorio)[0];
            }
            $ecmImagem = $this->EcmImagem->patchEntity($ecmImagem, $this->request->data);
            if ($this->EcmImagem->save($ecmImagem)) {
                $this->Flash->success(__('The imagem has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The imagem could not be saved. Please, try again.'));
            }
        }

        $plugins = $this->plugins;

        $plugin = "";
        $value = "";
        foreach($plugins as $plugin => $value){
            $value = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', '_$0', $plugin)), 1);
            if(!empty($ecmImagem[$value])){
                $plugins = [$plugin => $plugins[$plugin]];
                break;
            }
        }

        if($plugin == "EcmInstrutor"){
            $associacao[$ecmImagem[$value][0]->id] = $ecmImagem[$value][0]->mdl_user->firstname
                . " " . $ecmImagem[$value][0]->mdl_user->lastname;
        } else {
            $associacao[$ecmImagem[$value][0]->id] = $ecmImagem[$value][0]->nome;
        }
        $associacaoid = $ecmImagem[$value][0]->id;

        $this->set(compact('ecmImagem', 'plugins', 'plugin', 'associacao', 'associacaoid'));
        $this->set('_serialize', ['ecmImagem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Imagem id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if(!is_numeric($id))
            $id = $this->request->data['id'];

        $this->request->allowMethod(['post', 'delete']);
        $ecmImagem = $this->EcmImagem->get($id, [
            'contain' => ['EcmOperadoraPagamento', 'EcmProduto', 'EcmRedeSocial',
                'EcmInstrutor' => ['MdlUser']]
        ]);

        foreach($plugins = $this->plugins as $key => $value){
            $value = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', '_$0', $key)), 1);
            if(!empty($ecmImagem[$value])){
                $value = substr(strtolower(preg_replace('/(?<!\ )[A-Z]/', ' $0', $key)), 4);
                $this->Flash->error(__('Para deletar esta imagem, acesse o(a) '.$value));
                return $this->redirect(['action' => 'index']);
            }
        }

        if(file_exists(WWW_ROOT . 'upload/' . $ecmImagem->src)){
            unlink(WWW_ROOT . 'upload/' . $ecmImagem->src);

            $folder = new Folder(WWW_ROOT . 'upload/' . substr($ecmImagem->src, 0, strrpos($ecmImagem->src, '/')));
            if(empty($folder->find()))
                $folder->delete();
            //rmdir(WWW_ROOT . 'upload/' . substr($ecmImagem->src, 0, strrpos($ecmImagem->src, '/')));
        }
        if ($this->EcmImagem->delete($ecmImagem)) {
            $this->Flash->success(__('The ecm imagem has been deleted.'));
            if(!$this->request->params['isAjax'])
                return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('The ecm imagem could not be deleted. Please, try again.'));
        }

    }
}
