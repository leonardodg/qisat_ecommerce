<?php
namespace CursoPresencial\Controller;

use CursoPresencial\Controller\AppController;

/**
 * EcmCursoPresencialLocal Controller
 *
 * @property \CursoPresencial\Model\Table\EcmCursoPresencialLocalTable $EcmCursoPresencialLocal */
class EcmCursoPresencialLocalController extends AppController
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
            $mdlCidade = $this->EcmCursoPresencialLocal->MdlCidade->find('list', ['keyField' => 'id',
                'valueField' => 'nome'])->where(['uf' => $this->request->data['id']])->toArray();
            array_unshift($mdlCidade, 'Todas as Cidades');
            echo json_encode($mdlCidade);
        }

        $conditions = [];
        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($conditions, 'nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['cidade']) && $this->request->query['cidade'] != "0"){
                array_push($conditions, 'MdlCidade.id = '.$this->request->query['cidade']);
            } else if(isset($this->request->query['estado']) && $this->request->query['estado'] != "0"){
                array_push($conditions, 'MdlEstado.id = '.$this->request->query['estado']);
            }
            if(isset($this->request->query['endereco']) && !empty($this->request->query['endereco'])){
                array_push($conditions, 'endereco LIKE "%'.$this->request->query['endereco'].'%"');
            }
        }

        $this->paginate = [
            'contain' => ['MdlCidade' => ['MdlEstado']],
            'conditions' => $conditions
        ];
        $ecmCursoPresencialLocal = $this->paginate($this->EcmCursoPresencialLocal);

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $mdlEstado = $this->EcmCursoPresencialLocal->MdlCidade->MdlEstado->
                find('list', ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        array_unshift($mdlEstado, 'Todos os Estados');

        $this->set(compact('ecmCursoPresencialLocal', 'mdlEstado'));
        $this->set('_serialize', ['ecmCursoPresencialLocal']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Curso Presencial Local id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->get($id, [
            'contain' => ['MdlCidade'=>['MdlEstado']]
        ]);
        $this->set('ecmCursoPresencialLocal', $ecmCursoPresencialLocal);
        $this->set('_serialize', ['ecmCursoPresencialLocal']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->newEntity();
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $estado = (int)$this->request->query['estado'];
            $mdlCidade = $this->EcmCursoPresencialLocal->MdlCidade->find('list',
                ['keyField' => 'id','valueField' => 'nome'])
                ->innerJoin(['MdlEstado'=>'mdl_estado'],
                    'MdlEstado.id = MdlCidade.uf')
                ->where(['MdlEstado.id'=>$estado]);

            echo json_encode(compact('mdlCidade'));
        } else if ($this->request->is('post')) {
            $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->patchEntity($ecmCursoPresencialLocal, $this->request->data);
            if ($this->EcmCursoPresencialLocal->save($ecmCursoPresencialLocal)) {
                $this->Flash->success(__('The ecm curso presencial local has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm curso presencial local could not be saved. Please, try again.'));
            }
        }
        $mdlEstado = $this->EcmCursoPresencialLocal->MdlCidade->MdlEstado->find('list', ['limit' => 200,
            'keyField' => 'id','valueField' => 'nome'])->toArray();
        $mdlEstado[0] = "Selecione um estado";
        ksort($mdlEstado);
        $this->set(compact('ecmCursoPresencialLocal', 'mdlEstado'));
        $this->set('_serialize', ['ecmCursoPresencialLocal']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Curso Presencial Local id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->get($id);
        $mdlCidadeUf = $this->EcmCursoPresencialLocal->MdlCidade->find(
            'list', ['limit' => 200,'valueField' => 'uf'])
            ->where('id='.$ecmCursoPresencialLocal['mdl_cidade_id'])->toArray();
        $ecmCursoPresencialLocal['mdl_estado_id'] = array_pop($mdlCidadeUf);

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $estado = (int)$this->request->query['estado'];
            $mdlCidade = $this->EcmCursoPresencialLocal->MdlCidade->find('list', ['limit' => 200,
                'keyField' => 'id','valueField' => 'nome'])
                ->innerJoin(['MdlEstado'=>'mdl_estado'],
                    'MdlEstado.id = MdlCidade.uf')
                ->where(['MdlEstado.id'=>$estado]);

            echo json_encode(compact('mdlCidade'));
        } else if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->patchEntity($ecmCursoPresencialLocal, $this->request->data);
            if ($this->EcmCursoPresencialLocal->save($ecmCursoPresencialLocal)) {
                $this->Flash->success(__('The ecm curso presencial local has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm curso presencial local could not be saved. Please, try again.'));
            }
        }
        $mdlEstado = $this->EcmCursoPresencialLocal->MdlCidade->MdlEstado->find('list', ['limit' => 200,
            'keyField' => 'id','valueField' => 'nome'])->toArray();
        $mdlEstado[0] = "Selecione um estado";
        ksort($mdlEstado);

        $mdlCidade = $this->EcmCursoPresencialLocal->MdlCidade->find('list',
            ['keyField' => 'id','valueField' => 'nome'])
            ->where('uf='.$ecmCursoPresencialLocal['mdl_estado_id'])->toArray();
        $mdlCidade[0] = "Selecione uma cidade";
        ksort($mdlCidade);

        $this->set(compact('ecmCursoPresencialLocal', 'mdlEstado', 'mdlCidade'));
        $this->set('_serialize', ['ecmCursoPresencialLocal']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Curso Presencial Local id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmCursoPresencialLocal = $this->EcmCursoPresencialLocal->get($id);
        if ($this->EcmCursoPresencialLocal->delete($ecmCursoPresencialLocal)) {
            $this->Flash->success(__('The ecm curso presencial local has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm curso presencial local could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
