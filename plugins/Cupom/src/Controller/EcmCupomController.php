<?php
namespace Cupom\Controller;

use Cupom\Controller\AppController;

/**
 * EcmCupom Controller
 *
 * @property \Cupom\Model\Table\EcmCupomTable $EcmCupom */
class EcmCupomController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->helpers = array('JqueryUI','JqueryMask', 'UserScript');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $conditions = [];

        $ecmCupom = $this->EcmCupom->find('all');

        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($conditions, 'EcmCupom.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['datainicio']) && !empty($this->request->query['datainicio'])){
                $datainicio = \DateTime::createFromFormat('j/m/Y', $this->request->query['datainicio']);
                array_push($conditions, 'datainicio >= "'.$datainicio->format("Y-m-d").' 00:00:00"');
            }
            if(isset($this->request->query['datafim']) && !empty($this->request->query['datafim'])){
                $datafim = \DateTime::createFromFormat('j/m/Y', $this->request->query['datafim']);
                array_push($conditions, 'datafim <= "'.$datafim->format("Y-m-d").' 23:59:59"');
            }
            if(isset($this->request->query['habilitado']) && !is_numeric($this->request->query['habilitado'])){
                array_push($conditions, 'EcmCupom.habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
            if(isset($this->request->query['produto']) && $this->request->query['produto'] != "0"){
                array_push($conditions, 'ecm_produto.id = '.$this->request->query['produto']);

                $ecmCupom->leftJoin('ecm_cupom_ecm_produto',
                            [
                                'ecm_cupom_ecm_produto.ecm_cupom_id = EcmCupom.id'
                            ]
                        )
                        ->leftJoin('ecm_produto',
                            [
                                'ecm_produto.id = ecm_cupom_ecm_produto.ecm_produto_id'
                            ]
                        );
            }
        }

        $this->paginate = [
            'conditions' => $conditions,
            //,'contain' => ['EcmAlternativeHost']
            'order' => [ 'id' => 'DESC' ]
        ];

        $ecmCupom->where($conditions)
                 ->group('EcmCupom.id ASC');
        $ecmCupom = $this->paginate($ecmCupom);

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não'];
        $produto = $this->EcmCupom->EcmProduto->find('list', ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $produto[0] = 'Todos os produtos';
        ksort($produto);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmCupom', 'habilitado', 'produto'));
        $this->set('_serialize', ['ecmCupom']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Cupom id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmCupom = $this->EcmCupom->get($id, [
            'contain' => ['EcmAlternativeHost', 'EcmProduto', 'EcmTipoProduto']
        ]);

        $this->set('ecmCupom', $ecmCupom);
        $this->set('_serialize', ['ecmCupom']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmCupom = $this->EcmCupom->newEntity();
        if ($this->request->is('post')) {
            $emails = [];
            if(array_key_exists('email', $this->request->data)){
                $emails = explode(',', $this->request->data['email']);
                unset($this->request->data['email']);
            }

            $ecmCupom = $this->EcmCupom->patchEntity($ecmCupom, $this->request->data);

            if ($this->EcmCupom->save($ecmCupom)) {
                foreach($emails as $email){
                    $ecmCupomCampanha = $this->EcmCupom->EcmCupomCampanha->newEntity();
                    $ecmCupomCampanha->ecm_cupom_id = $ecmCupom->id;
                    $ecmCupomCampanha->email = $email;
                    $ecmCupomCampanha->datacriacao = time();
                    $this->EcmCupom->EcmCupomCampanha->save($ecmCupomCampanha);
                }

                $this->Flash->success(__('Cupom salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar!'));
            }
        }
        $ecmAlternativeHost = $this->EcmCupom->EcmAlternativeHost->find('list',
            ['keyField' => 'id', 'valueField' => 'shortname']);
        $ecmProduto = $this->EcmCupom->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla']);
        $ecmTipoProduto = $this->EcmCupom->EcmTipoProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);

        $this->set(compact('ecmCupom', 'ecmAlternativeHost', 'ecmProduto', 'ecmTipoProduto'));
        $this->set('_serialize', ['ecmCupom']);
        $this->set('titulo', __('Novo Cupom'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Cupom id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmCupom = $this->EcmCupom->get($id, [
            'contain' => ['EcmProduto', 'EcmTipoProduto', 'MdlUser', 'EcmCupomCampanha']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if(!isset($this->request->data['descontoporcentagem'])){
                $this->request->data['descontoporcentagem'] = '';
            }

            if(!isset($this->request->data['descontovalor'])){
                $this->request->data['descontovalor'] = '';
            }

            $emails = [];
            if(array_key_exists('email', $this->request->data)){
                $emails = explode(',', $this->request->data['email']);
                unset($this->request->data['email']);
                foreach($ecmCupom->ecm_cupom_campanha as $key => $campanha){
                    if(in_array($campanha->email, $emails)){
                        unset($emails[array_search($campanha->email, $emails)]);
                    } else {
                        $this->EcmCupom->EcmCupomCampanha->delete($campanha);
                    }
                }
            }

            $ecmCupom = $this->EcmCupom->patchEntity($ecmCupom, $this->request->data);
            if ($this->EcmCupom->save($ecmCupom)) {
                foreach($emails as $email){
                    $ecmCupomCampanha = $this->EcmCupom->EcmCupomCampanha->newEntity();
                    $ecmCupomCampanha->ecm_cupom_id = $ecmCupom->id;
                    $ecmCupomCampanha->email = $email;
                    $ecmCupomCampanha->datacriacao = time();
                    $this->EcmCupom->EcmCupomCampanha->save($ecmCupomCampanha);
                }
                $this->Flash->success(__('Cupom salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar!'));
            }
        }elseif($this->request->is('get')){
            $ecmCupom->descontovalor = $ecmCupom->descontovalor == 0?'' : number_format($ecmCupom->descontovalor, 2,',','.');
            $ecmCupom->descontoporcentagem  = $ecmCupom->descontoporcentagem == 0?'' :  number_format($ecmCupom->descontoporcentagem, 2,',','.');
        }

        $ecmAlternativeHost = $this->EcmCupom->EcmAlternativeHost->find('list',
            ['keyField' => 'id', 'valueField' => 'shortname']);
        $ecmProduto = $this->EcmCupom->EcmProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'sigla']);
        $ecmTipoProduto = $this->EcmCupom->EcmTipoProduto->find('list',
            ['keyField' => 'id', 'valueField' => 'nome']);

        $usuariosSelecionados = [];

        if(!is_null($ecmCupom->get('mdl_user'))){
            foreach($ecmCupom->get('mdl_user') as $usuario){
                $usuariosSelecionados[$usuario->get('id')] = $usuario->get('firstname').' '.$usuario->get('lastname');
            }
        }

        $ecmCupom->email = '';
        foreach($ecmCupom->ecm_cupom_campanha as $campanha){
            if(!empty($ecmCupom->email))
                $ecmCupom->email .= ',';
            $ecmCupom->email .= $campanha->email;
        }

        $this->set(compact('ecmCupom', 'ecmAlternativeHost', 'ecmProduto', 'ecmTipoProduto', 'usuariosSelecionados'));
        $this->set('_serialize', ['ecmCupom']);
        $this->set('titulo', __('Editar Cupom'));
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Cupom id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmCupom = $this->EcmCupom->get($id);
        if ($this->EcmCupom->delete($ecmCupom)) {
            $this->Flash->success(__('Cupom excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro excluir o cupom!'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
