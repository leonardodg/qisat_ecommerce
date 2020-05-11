<?php
namespace Promocao\Controller;

use Promocao\Controller\AppController;

/**
 * EcmPromocao Controller
 *
 * @property \Promocao\Model\Table\EcmPromocaoTable $EcmPromocao */
class EcmPromocaoController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('JqueryUI','JqueryMask');
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
            if(isset($this->request->query['datainicio']) && !empty($this->request->query['datainicio'])){
                $inicio = \DateTime::createFromFormat('j/m/Y', $this->request->query['datainicio']);
                array_push($conditions, 'datainicio >= "'.$inicio->format("Y-m-d").' 00:00:00"');
            }
            if(isset($this->request->query['datafim']) && !empty($this->request->query['datafim'])){
                $fim = \DateTime::createFromFormat('j/m/Y', $this->request->query['datafim']);
                array_push($conditions, 'datafim <= "'.$fim->format("Y-m-d").' 23:59:59"');
            }
            if(isset($this->request->query['descricao']) && !empty($this->request->query['descricao'])){
                array_push($conditions, 'descricao LIKE "%'.$this->request->query['descricao'].'%"');
            }
            if(isset($this->request->query['habilitado']) && $this->request->query['habilitado'] != "0"){
                array_push($conditions, 'habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
        }

        $this->paginate = ['conditions' => $conditions];

        $ecmPromocao = $this->paginate($this->EcmPromocao);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmPromocao', 'habilitado'));
        $this->set('_serialize', ['ecmPromocao']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Promocao id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmPromocao = $this->EcmPromocao->get($id, [
            'contain' => ['EcmAlternativeHost', 'EcmProduto']
        ]);

        $this->set('ecmPromocao', $ecmPromocao);
        $this->set('_serialize', ['ecmPromocao']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $ecmPromocao = $this->EcmPromocao->newEntity();
        if ($this->request->is(['post','put'])) {
            $ecmPromocao = $this->EcmPromocao->patchEntity($ecmPromocao, $this->request->data);
            if ($this->EcmPromocao->save($ecmPromocao)) {
                $this->Flash->success(__('Promoção salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar'));
            }
        }elseif($this->request->is('get') && is_numeric($id)){
            $ecmPromocao = $this->EcmPromocao->get($id, [
                'contain' => ['EcmAlternativeHost', 'EcmProduto']
            ]);
        }
        $ecmAlternativeHost = $this->EcmPromocao->EcmAlternativeHost->find('list',
            ['keyField' => 'id', 'valueField' => 'shortname']);
        $ecmProduto = $this->EcmPromocao->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla'])
            ->where(['habilitado' => 'true']);
        $this->set(compact('ecmPromocao', 'ecmAlternativeHost', 'ecmProduto'));
        $this->set('_serialize', ['ecmPromocao']);
        $this->set('titulo',__('Nova Promoção'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Promocao id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmPromocao = $this->EcmPromocao->get($id, [
            'contain' => ['EcmAlternativeHost', 'EcmProduto']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(!isset($this->request->data['descontoporcentagem'])){
                $this->request->data['descontoporcentagem'] = '';
            }

            if(!isset($this->request->data['descontovalor'])){
                $this->request->data['descontovalor'] = '';
            }

            $ecmPromocao = $this->EcmPromocao->patchEntity($ecmPromocao, $this->request->data);
            if ($this->EcmPromocao->save($ecmPromocao)) {
                $this->Flash->success(__('Promoção salva com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar'));
            }
        }elseif($this->request->is('get')){
            $ecmPromocao->descontovalor = $ecmPromocao->descontovalor == 0?'' : number_format($ecmPromocao->descontovalor, 2,',','.');
            $ecmPromocao->descontoporcentagem  = $ecmPromocao->descontoporcentagem == 0?'' :  number_format($ecmPromocao->descontoporcentagem, 2,',','.');
        }

        $ecmAlternativeHost = $this->EcmPromocao->EcmAlternativeHost->find('list',
            ['keyField' => 'id', 'valueField' => 'shortname']);
        $ecmProduto = $this->EcmPromocao->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla']);
        $this->set(compact('ecmPromocao', 'ecmAlternativeHost', 'ecmProduto'));
        $this->set('_serialize', ['ecmPromocao']);
        $this->set('titulo',__('Editar Promoção'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Promocao id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmPromocao = $this->EcmPromocao->get($id);
        if ($this->EcmPromocao->delete($ecmPromocao)) {
            $this->Flash->success(__('Promoção excluída com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
