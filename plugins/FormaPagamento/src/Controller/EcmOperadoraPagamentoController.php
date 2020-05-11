<?php
namespace FormaPagamento\Controller;

use Cake\Validation\Validator;
use FormaPagamento\Controller\AppController;
use FormaPagamento\Model\Entity\EcmOperadoraPagamento;

/**
 * EcmOperadoraPagamento Controller
 *
 * @property \FormaPagamento\Model\Table\EcmOperadoraPagamentoTable $EcmOperadoraPagamento */
class EcmOperadoraPagamentoController extends AppController
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
                array_push($conditions, 'EcmOperadoraPagamento.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['descricao']) && !empty($this->request->query['descricao'])){
                array_push($conditions, 'EcmOperadoraPagamento.descricao LIKE "%'.$this->request->query['descricao'].'%"');
            }
            if(isset($this->request->query['habilitado']) && !is_numeric($this->request->query['habilitado'])){
                array_push($conditions, 'EcmOperadoraPagamento.habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
        }

        $this->paginate = [
            'contain' => ['EcmImagem', 'EcmFormaPagamento'],
            'sortWhitelist'=>['EcmFormaPagamento.nome'],
            'conditions' => $conditions
        ];
        $ecmOperadoraPagamento = $this->paginate($this->EcmOperadoraPagamento);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmOperadoraPagamento', 'habilitado'));
        $this->set('_serialize', ['ecmOperadoraPagamento']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Operadora Pagamento id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->get($id, [
            'contain' => ['EcmImagem', 'EcmFormaPagamento']
        ]);

        $this->set('ecmOperadoraPagamento', $ecmOperadoraPagamento);
        $this->set('_serialize', ['ecmOperadoraPagamento']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->newEntity();
        if ($this->request->is('post')) {
            $errors = $this->EcmOperadoraPagamento->validator()->errors($this->request->data);

            if(empty($errors)) {
                $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->patchEntity($ecmOperadoraPagamento, $this->request->data);
                if ($ecmOperadoraPagamento = $this->EcmOperadoraPagamento->save($ecmOperadoraPagamento)) {

                    if ($this->salvarImagem($ecmOperadoraPagamento)) {
                        $this->Flash->success(__('Operadora de pagamento salva com sucesso'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('Ocorreu um erro ao salvar a operadora de pagamento!'));
                    }
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar a operadora de pagamento!'));
                }
            }else{
                $ecmOperadoraPagamento->errors($errors);
            }
        }
        $ecmFormaPagamento = $this->EcmOperadoraPagamento->EcmFormaPagamento->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);

        $this->set(compact('ecmOperadoraPagamento', 'ecmFormaPagamento'));
        $this->set('_serialize', ['ecmOperadoraPagamento']);
        $this->set('titulo', __('Nova Operadora de Pagamento'));
        $this->set('requireImagem', true);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Operadora Pagamento id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->get($id, [
            'contain' => ['EcmImagem']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['imagem']['name'])){
                unset($this->request->data['imagem']);
                $this->EcmOperadoraPagamento->validator()->remove('imagem');
            }

            $this->EcmOperadoraPagamento->validator()->remove('nome', 'unique');
            $errors = $this->EcmOperadoraPagamento->validator()->errors($this->request->data);

            if(empty($errors)) {
                $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->patchEntity($ecmOperadoraPagamento, $this->request->data);
                if ($ecmOperadoraPagamento = $this->EcmOperadoraPagamento->save($ecmOperadoraPagamento)) {

                    $imagemSalva =  !isset($this->request->data['imagem'])? true : $this->salvarImagem($ecmOperadoraPagamento);

                    if ($imagemSalva) {
                        $this->Flash->success(__('Operadora de pagamento salva com sucesso'));
                        return $this->redirect(['action' => 'index']);
                    }else{
                        $this->Flash->error(__('Ocorreu um erro ao salvar a operadora de pagamento!'));
                    }
                } else {
                    $this->Flash->error(__('Ocorreu um erro ao salvar a operadora de pagamento!'));
                }
            }else{
                $ecmOperadoraPagamento->errors($errors);
            }
        }
        $ecmFormaPagamento = $this->EcmOperadoraPagamento->EcmFormaPagamento->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);
        $this->set(compact('ecmOperadoraPagamento', 'ecmFormaPagamento'));
        $this->set('_serialize', ['ecmOperadoraPagamento']);
        $this->set('titulo', __('Editar Operadora de Pagamento'));
        $this->set('requireImagem', false);
        $this->render('add');
    }

    private function salvarImagem(EcmOperadoraPagamento $ecmOperadoraPagamento){
        $src = str_replace('webroot/upload/','',$ecmOperadoraPagamento->imagem_dir);
        $ecmImagemData = [
            'nome' => $ecmOperadoraPagamento->imagem,
            'src' => $src.'/'.$ecmOperadoraPagamento->imagem,
            'descricao' => 'Operadora de Pagamento'
        ];

        if(isset($ecmOperadoraPagamento->ecm_imagem_id)){
            $ecmImagemData['id'] = $ecmOperadoraPagamento->ecm_imagem_id;
        }

        $ecmImagem = $this->EcmOperadoraPagamento->EcmImagem->newEntity();
        $ecmImagem = $this->EcmOperadoraPagamento->EcmImagem->patchEntity($ecmImagem, $ecmImagemData);

        $ecmImagem = $this->EcmOperadoraPagamento->EcmImagem->save($ecmImagem);
        $ecmOperadoraPagamento->ecm_imagem_id = $ecmImagem->id;

        return $this->EcmOperadoraPagamento->save($ecmOperadoraPagamento);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Operadora Pagamento id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmOperadoraPagamento = $this->EcmOperadoraPagamento->get($id,[
            'contain' => ['EcmImagem']
        ]);

        if ($this->EcmOperadoraPagamento->delete($ecmOperadoraPagamento)) {
            $this->EcmOperadoraPagamento->EcmImagem->newEntity();
            $this->EcmOperadoraPagamento->EcmImagem->delete($ecmOperadoraPagamento->ecm_imagem);

            $this->Flash->success(__('Operadora de pagamento excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir a operadora de pagemento!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
