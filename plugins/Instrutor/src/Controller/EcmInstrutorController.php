<?php
namespace Instrutor\Controller;

use Instrutor\Model\Entity\EcmInstrutor;

/**
 * EcmInstrutor Controller
 *
 * @property \Instrutor\Model\Table\EcmInstrutorTable $EcmInstrutor */
class EcmInstrutorController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('TinyMCE');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $condition = [];

        if(isset($this->request->query) && !empty($this->request->query)){
            $nome = $this->request->query('nome');

            if (strlen(trim($nome)) > 0) {
                $condition["CONCAT(MdlUser.firstname, ' ', lastname) LIKE"] = '%'.$nome.'%';
            }
        }

        $this->paginate = [
            'contain' => ['MdlUser', 'EcmImagem'],
            'sortWhitelist'=>['MdlUser.firstname'],
            'conditions' => $condition
        ];
        $ecmInstrutor = $this->paginate($this->EcmInstrutor);
        $instrutor = $this->EcmInstrutor->newEntity();

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmInstrutor', 'instrutor'));
        $this->set('_serialize', ['ecmInstrutor']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Instrutor id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmInstrutor = $this->EcmInstrutor->get($id, [
            'contain' => ['MdlUser', 'EcmImagem', 'EcmProduto',
                'EcmInstrutorArtigo', 'EcmInstrutorRedeSocial' => ['EcmRedeSocial' => ['EcmImagem']]]
        ]);

        $this->set('ecmInstrutor', $ecmInstrutor);
        $this->set('_serialize', ['ecmInstrutor']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmInstrutor = $this->EcmInstrutor->newEntity();
        if ($this->request->is('post')) {
            $errors = $this->EcmInstrutor->validator()->errors($this->request->data);

            if(empty($errors)) {
                $ecmInstrutor = $this->EcmInstrutor->patchEntity($ecmInstrutor, $this->request->data);
                if ($ecmInstrutor = $this->EcmInstrutor->save($ecmInstrutor)) {

                    if(!is_null($ecmInstrutor->imagem))
                        $this->salvarImagem($ecmInstrutor);

                    $this->Flash->success(__('Instrutor salvo com sucesso'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar o instrutor!'));
                }
            }else{
                $ecmInstrutor->errors($errors);
            }
        }
        $ecmProduto = $this->EcmInstrutor->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla']);

        $ecmInstrutorArea = $this->EcmInstrutor->EcmInstrutorArea->find('list',
            ['keyField' => 'id', 'valueField' => 'descricao']);

        $this->set(compact('ecmInstrutor', 'mdlUser', 'ecmImagem', 'ecmProduto', 'ecmInstrutorArea'));
        $this->set('_serialize', ['ecmInstrutor']);
        $this->set('titulo', __('Novo Instrutor'));
        $this->set('requireImagem', true);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Instrutor id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmInstrutor = $this->EcmInstrutor->get($id, [
            'contain' => ['EcmProduto', 'EcmImagem', 'MdlUser', 'EcmInstrutorArea']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            if(empty($this->request->data['imagem']['name'])){
                unset($this->request->data['imagem']);
                $this->EcmInstrutor->validator()->remove('imagem');
            }

            $errors = $this->EcmInstrutor->validator()->errors($this->request->data);
            if(empty($errors)) {
                $ecmInstrutor = $this->EcmInstrutor->patchEntity($ecmInstrutor, $this->request->data);
                if ($ecmInstrutor = $this->EcmInstrutor->save($ecmInstrutor)) {

                    $imagemSalva =  !isset($this->request->data['imagem'])? true : $this->salvarImagem($ecmInstrutor);
                    if ($imagemSalva) {
                        $this->Flash->success(__('Instrutor salvo com sucesso'));
                        return $this->redirect(['action' => 'index']);
                    }else{
                        $this->Flash->error(__('Ocorreu um erro ao salvar o instrutor!'));
                    }
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar o instrutor!'));
                }
            }else{
                $ecmInstrutor->errors($errors);
            }
        }
        $ecmProduto = $this->EcmInstrutor->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla']);

        $ecmInstrutorArea = $this->EcmInstrutor->EcmInstrutorArea->find('list',
            ['keyField' => 'id', 'valueField' => 'descricao']);

        $this->set(compact('ecmInstrutor', 'mdlUser', 'ecmImagem', 'ecmProduto', 'ecmInstrutorArea'));
        $this->set('_serialize', ['ecmInstrutor']);
        $this->set('requireImagem', false);
        $this->set('titulo', __('Editar Instrutor'));
        $this->render('add');
    }

    private function salvarImagem(EcmInstrutor $ecmInstrutor){
        $src = str_replace('webroot/upload/','',$ecmInstrutor->imagem_dir);
        $ecmImagemData = [
            'nome' => $ecmInstrutor->imagem,
            'src' => $src.'/'.$ecmInstrutor->imagem,
            'descricao' => 'Instrutor'
        ];

        if(isset($ecmInstrutor->ecm_imagem_id)){
            $ecmImagemData['id'] = $ecmInstrutor->ecm_imagem_id;
        }

        $ecmImagem = $this->EcmInstrutor->EcmImagem->newEntity();
        $ecmImagem = $this->EcmInstrutor->EcmImagem->patchEntity($ecmImagem, $ecmImagemData);

        $ecmImagem = $this->EcmInstrutor->EcmImagem->save($ecmImagem);
        $ecmInstrutor->ecm_imagem_id = $ecmImagem->id;

        return $this->EcmInstrutor->save($ecmInstrutor);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Instrutor id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmInstrutor = $this->EcmInstrutor->get($id, ['contain' => 'EcmImagem']);
        if ($this->EcmInstrutor->delete($ecmInstrutor)) {
            $this->Flash->success(__('Intrutor excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluír o instrutor!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
