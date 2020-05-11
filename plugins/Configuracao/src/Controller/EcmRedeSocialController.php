<?php
namespace Configuracao\Controller;

use Configuracao\Controller\AppController;
use Configuracao\Model\Entity\EcmRedeSocial;

/**
 * EcmRedeSocial Controller
 *
 * @property \Configuracao\Model\Table\EcmRedeSocialTable $EcmRedeSocial */
class EcmRedeSocialController extends AppController
{

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
                array_push($conditions, 'EcmRedeSocial.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
        }

        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['EcmImagem']
        ];
        $ecmRedeSocial = $this->paginate($this->EcmRedeSocial);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmRedeSocial'));
        $this->set('_serialize', ['ecmRedeSocial']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Rede Social id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmRedeSocial = $this->EcmRedeSocial->get($id, [
            'contain' => ['EcmImagem', 'EcmInstrutorRedeSocial']
        ]);

        $this->set('ecmRedeSocial', $ecmRedeSocial);
        $this->set('_serialize', ['ecmRedeSocial']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmRedeSocial = $this->EcmRedeSocial->newEntity();
        if ($this->request->is('post')) {
            $errors = $this->EcmRedeSocial->validator()->errors($this->request->data);

            if(empty($errors)) {
                $ecmRedeSocial = $this->EcmRedeSocial->patchEntity($ecmRedeSocial, $this->request->data);
                if ($ecmRedeSocial = $this->EcmRedeSocial->save($ecmRedeSocial)) {
                    if ($this->salvarImagem($ecmRedeSocial)) {
                        $this->Flash->success(__('Rede social salva com sucesso'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
                    }
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
                }
            }else{
                $ecmRedeSocial->errors($errors);
            }
        }
        $this->set(compact('ecmRedeSocial'));
        $this->set('requireImagem', true);
        $this->set('_serialize', ['ecmRedeSocial']);
        $this->set('titulo', __('Nova Rede Social'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Rede Social id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmRedeSocial = $this->EcmRedeSocial->get($id, [
            'contain' => ['EcmImagem']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            if(empty($this->request->data['imagem']['name'])){
                unset($this->request->data['imagem']);
                $this->EcmRedeSocial->validator()->remove('imagem');
            }

            $this->EcmRedeSocial->validator()->remove('nome', 'unique');
            $errors = $this->EcmRedeSocial->validator()->errors($this->request->data);
            if(empty($errors)) {
                $ecmRedeSocial = $this->EcmRedeSocial->patchEntity($ecmRedeSocial, $this->request->data);
                if ($ecmRedeSocial = $this->EcmRedeSocial->save($ecmRedeSocial)) {

                    $imagemSalva =  !isset($this->request->data['imagem'])? true : $this->salvarImagem($ecmRedeSocial);
                    if ($imagemSalva) {
                        $this->Flash->success(__('Rede social salva com sucesso'));
                        return $this->redirect(['action' => 'index']);
                    }else{
                        $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
                    }

                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar a rede social!'));
                }
            }else{
                $ecmRedeSocial->errors($errors);
            }
        }
        $this->set(compact('ecmRedeSocial'));
        $this->set('requireImagem', false);
        $this->set('_serialize', ['ecmRedeSocial']);
        $this->set('titulo', __('Editar Rede Social'));
        $this->render('add');
    }

    private function salvarImagem(EcmRedeSocial $ecmRedeSocial){
        $src = str_replace('webroot/upload/','',$ecmRedeSocial->imagem_dir);
        $ecmImagemData = [
            'nome' => $ecmRedeSocial->imagem,
            'src' => $src.'/'.$ecmRedeSocial->imagem,
            'descricao' => 'Rede Social'
        ];

        if(isset($ecmRedeSocial->ecm_imagem_id)){
            $ecmImagemData['id'] = $ecmRedeSocial->ecm_imagem_id;
        }

        $ecmImagem = $this->EcmRedeSocial->EcmImagem->newEntity();
        $ecmImagem = $this->EcmRedeSocial->EcmImagem->patchEntity($ecmImagem, $ecmImagemData);

        $ecmImagem = $this->EcmRedeSocial->EcmImagem->save($ecmImagem);
        $ecmRedeSocial->ecm_imagem_id = $ecmImagem->id;

        return $this->EcmRedeSocial->save($ecmRedeSocial);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Rede Social id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmRedeSocial = $this->EcmRedeSocial->get($id,[
            'contain' => ['EcmImagem']
        ]);
        if ($this->EcmRedeSocial->delete($ecmRedeSocial)) {
            $this->Flash->success(__('Rede social excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir a rede social!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
