<?php
namespace Newsletter\Controller;

use Newsletter\Controller\AppController;

/**
 * EcmNewsletter Controller
 *
 * @property \Newsletter\Model\Table\EcmNewsletterTable $EcmNewsletter */
class EcmNewsletterController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ecmNewsletter = $this->paginate($this->EcmNewsletter);

        $this->set(compact('ecmNewsletter'));
        $this->set('_serialize', ['ecmNewsletter']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmNewsletter = $this->EcmNewsletter->newEntity();
        if ($this->request->is('post')) {
            $ecmNewsletter = $this->EcmNewsletter->patchEntity($ecmNewsletter, $this->request->data);
            if ($this->EcmNewsletter->save($ecmNewsletter)) {
                $this->Flash->success(__('E-mail salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('E-mail não pode ser salvo, por favor tente novamente'));
            }
        }
        $titulo = __('Cadastrar E-mail');

        $this->set(compact('ecmNewsletter', 'titulo'));
        $this->set('_serialize', ['ecmNewsletter']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Newsletter id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmNewsletter = $this->EcmNewsletter->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmNewsletter = $this->EcmNewsletter->patchEntity($ecmNewsletter, $this->request->data);
            if ($this->EcmNewsletter->save($ecmNewsletter)) {
                $this->Flash->success(__('E-mail salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('E-mail não pode ser salvo, por favor tente novamente.'));
            }
        }
        $titulo = __('Alterar E-mail');

        $this->set(compact('ecmNewsletter', 'titulo'));
        $this->set('_serialize', ['ecmNewsletter']);
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Newsletter id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmNewsletter = $this->EcmNewsletter->get($id);
        if ($this->EcmNewsletter->delete($ecmNewsletter)) {
            $this->Flash->success(__('E-mail deletado com sucesso..'));
        } else {
            $this->Flash->error(__('E-mail não pode ser deletado, por favor tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
