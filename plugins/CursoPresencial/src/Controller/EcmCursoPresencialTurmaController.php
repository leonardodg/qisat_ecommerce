<?php
namespace CursoPresencial\Controller;

use CursoPresencial\Controller\AppController;

/**
 * EcmCursoPresencialTurma Controller
 *
 * @property \CursoPresencial\Model\Table\EcmCursoPresencialTurmaTable $EcmCursoPresencialTurma */
class EcmCursoPresencialTurmaController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = (int)$this->request->data['id'];
            $valor = $this->request->data['valor'];
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id);
            $ecmCursoPresencialTurma['status'] = $valor=='Ativo'?'Cancelado':'Ativo';
            if ($this->EcmCursoPresencialTurma->save($ecmCursoPresencialTurma)) {
                echo json_encode($ecmCursoPresencialTurma['status']);
            }
        }

        $conditions = [];
        $where = [];
        if(count($this->request->query)){
            if(isset($this->request->query['produto']) && intval($this->request->query['produto'])){
                array_push($conditions, 'EcmProduto.id='.$this->request->query['produto']);
            }
            if(isset($this->request->query['usuario']) && trim($this->request->query['usuario']) != ''){
                array_push($where, 'MdlUser.username like "%' . $this->request->query['usuario'] .
                    '%" or MdlUser.firstname like "%' . $this->request->query['usuario'] .
                    '%" or MdlUser.lastname like "%' . $this->request->query['usuario'] .
                    '%" or CONCAT_WS(" ", "MdlUser.firstname", "MdlUser.lastname") like "%' . $this->request->query['usuario'] . '%"');
            }

            if(isset($this->request->query['status'])) {
                if ($this->request->query['status'] == '1') {
                    array_push($where, 'EcmCursoPresencialData.datainicio > NOW()');
                } else if ($this->request->query['status'] == '2') {
                    array_push($where, 'EcmCursoPresencialData.datafim <= NOW()');
                }
            }
        }
        $this->paginate = [
            'contain' => ['EcmProduto', 'EcmInstrutor'=>['MdlUser'], 'EcmCursoPresencialData'=>['EcmCursoPresencialLocal']],
            'conditions' => $conditions
        ];

        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->find('all')
            ->leftJoin(['EcmCursoPresencialData' => 'ecm_curso_presencial_data'],
                'EcmCursoPresencialData.ecm_curso_presencial_turma_id = EcmCursoPresencialTurma.id')
            ->leftJoin(['EcmCursoPresencialTurmaEcmInstrutor' => 'ecm_curso_presencial_turma_ecm_instrutor'],
                'EcmCursoPresencialTurmaEcmInstrutor.ecm_curso_presencial_turma_id = EcmCursoPresencialTurma.id')
            ->leftJoin(['EcmInstrutor' => 'ecm_instrutor'],
                'EcmInstrutor.id = EcmCursoPresencialTurmaEcmInstrutor.ecm_instrutor_id')
            ->leftJoin(['MdlUser' => 'mdl_user'],
                'MdlUser.id = EcmInstrutor.mdl_user_id')
            ->where($where)->group('EcmCursoPresencialTurma.id ASC');
        $ecmCursoPresencialTurma = $this->paginate($ecmCursoPresencialTurma);

        $cursos = $this->EcmCursoPresencialTurma->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'sigla'])
            ->leftJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->leftJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
            ->where(['EcmTipoProduto.id' => 10])->toArray();
        $cursos[0] = "Selecione um curso presencial";
        ksort($cursos);

        $status = array(1 => 'Não Iniciado', 0 => 'Todos', 2 => 'Iniciado');

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmCursoPresencialTurma', 'cursos', 'status'));
        $this->set('_serialize', ['ecmCursoPresencialTurma']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Curso Presencial Turma id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id, [
            'contain' => ['EcmProduto', 'EcmInstrutor'=>['MdlUser'], 'EcmCursoPresencialData'=>['EcmCursoPresencialLocal']]
        ]);

        $this->set('ecmCursoPresencialTurma', $ecmCursoPresencialTurma);
        $this->set('_serialize', ['ecmCursoPresencialTurma']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->newEntity();
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            if(isset($this->request->query['produto'])){
                $produto = (int)$this->request->query['produto'];
                $ecmProduto = $this->EcmCursoPresencialTurma->EcmProduto->find('all', ['limit' => 200,
                    'fields' => 'preco', 'conditions' => ['id = ' . $produto]]);

                echo json_encode(compact('ecmProduto'));
            }else{
                $ecmCursoPresencialLocal = $this->EcmCursoPresencialTurma->EcmCursoPresencialData->EcmCursoPresencialLocal
                    ->find('list', ['limit' => 200, 'keyField' => 'id', 'valueField' => 'nome']);

                echo json_encode(compact('ecmCursoPresencialLocal'));
            };
        } else if ($this->request->is('post')) {
            $this->request->data["ecm_instrutor"] = ["_ids" => $this->request->data["ecm_instrutor_id"]];
            $this->request->data["ecm_curso_presencial_data"] = [];
            $data = [];
            for ($i = 1; $i <= (int)$this->request->data["row"]; $i++) {
                if(isset($this->request->data["local".$i])){
                    $datainicio = date_create_from_format('j/m/Y', $this->request->data["datepicker".$i."1"]);
                    $datafim = date_create_from_format('j/m/Y', $this->request->data["datepicker".$i."2"]);
                    $intervalo = $datainicio->diff($datafim);
                    $data['ecm_curso_presencial_local_id'] = $this->request->data["local".$i];
                    for ($j = 0; $j <= $intervalo->d; $j++) {
                        $data['datainicio'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."1"]);
                        date_modify($data['datainicio'], '+'.$j.' day');
                        $data['saidaintervalo'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."2"]);
                        date_modify($data['saidaintervalo'], '+'.$j.' day');
                        $data['voltaintervalo'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."3"]);
                        date_modify($data['voltaintervalo'], '+'.$j.' day');
                        $data['datafim'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."4"]);
                        date_modify($data['datafim'], '+'.$j.' day');
                        array_push($this->request->data["ecm_curso_presencial_data"], $data);
                    }
                }
            }
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->patchEntity($ecmCursoPresencialTurma, $this->request->data);
            if($ecmCursoPresencialTurma['valor_produto'] == "true"){
                unset($ecmCursoPresencialTurma['valor']);
            }
            if ($this->EcmCursoPresencialTurma->save($ecmCursoPresencialTurma)) {
                $this->Flash->success(__('The ecm curso presencial turma has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm curso presencial turma could not be saved. Please, try again.'));
            }
        }
        $ecmProduto = $this->EcmCursoPresencialTurma->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'sigla'])
            ->leftJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->leftJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
            ->where(['EcmTipoProduto.id' => 10])->toArray();
        $ecmProduto[0] = "Selecione um produto";
        ksort($ecmProduto);

        $ecmInstrutor = $this->EcmCursoPresencialTurma->EcmInstrutor->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => function ($e) {
                return $e->mdl_user->get('firstname') . ' ' . $e->mdl_user->get('lastname');
            }])->contain(['MdlUser']);

        $this->set(compact('ecmCursoPresencialTurma', 'ecmProduto', 'ecmInstrutor'));
        $this->set('_serialize', ['ecmCursoPresencialTurma']);

        $valor_produto = array('true' => 'Sim', 'false' => 'Não');
        $this->set('valor_produto', $valor_produto);

        $status = array('Ativo' => 'Ativo', 'Cancelado' => 'Cancelado');
        $this->set('status', $status);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Curso Presencial Turma id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id, [
            'contain' => ['EcmProduto']
        ]);

        if($ecmCursoPresencialTurma->get('valor_produto') == 'true')
            $ecmCursoPresencialTurma['valor'] = $ecmCursoPresencialTurma["ecm_produto"]["preco"];

        $ecmCursoPresencialData = $this->EcmCursoPresencialTurma->EcmCursoPresencialData->find('all', ['limit' => 200])
            ->where('ecm_curso_presencial_turma_id='.$id);

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $ecmProduto = $this->EcmCursoPresencialTurma->EcmProduto->find('all', ['limit' => 200,
                'fields' => 'preco', 'conditions' => ['id = ' . $id]]);

            echo json_encode(compact('ecmProduto'));
        } else if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data["ecm_instrutor"] = ["_ids" => $this->request->data["ecm_instrutor_id"]];
            $this->request->data["ecm_curso_presencial_data"] = [];
            $data = [];
            $ecmCursoPresencialData = $ecmCursoPresencialData->toArray();
            for ($i = 1; $i <= (int)$this->request->data["row"]; $i++) {
                if(isset($this->request->data["local".$i])){
                    $datainicio = date_create_from_format('j/m/Y', $this->request->data["datepicker".$i."1"]);
                    $datafim = date_create_from_format('j/m/Y', $this->request->data["datepicker".$i."2"]);
                    $intervalo = $datainicio->diff($datafim);
                    $data['ecm_curso_presencial_local_id'] = $this->request->data["local".$i];
                    for ($j = 0; $j <= $intervalo->d; $j++) {
                        foreach($ecmCursoPresencialData as $key => $data){
                            if($data->id == $i){
                                unset($ecmCursoPresencialData[$key]);
                                break;
                            }
                        }
                        $data['id'] = $i;
                        $data['datainicio'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."1"]);
                        date_modify($data['datainicio'], '+'.$j.' day');
                        $data['saidaintervalo'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."2"]);
                        date_modify($data['saidaintervalo'], '+'.$j.' day');
                        $data['voltaintervalo'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."3"]);
                        date_modify($data['voltaintervalo'], '+'.$j.' day');
                        $data['datafim'] = date_create_from_format('j/m/Y H:i', $this->request->data["datepicker".$i."1"] .
                            ' ' . $this->request->data["timepicker".$i."4"]);
                        date_modify($data['datafim'], '+'.$j.' day');
                        array_push($this->request->data["ecm_curso_presencial_data"], $data);
                    }
                }
            }

            foreach($ecmCursoPresencialData as $data){
                $this->EcmCursoPresencialTurma->EcmCursoPresencialData->delete($data);
            }
            
            $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->patchEntity($ecmCursoPresencialTurma, $this->request->data);
            if($ecmCursoPresencialTurma['valor_produto'] == 'true'){
                unset($ecmCursoPresencialTurma['valor']);
            }

            if ($this->EcmCursoPresencialTurma->save($ecmCursoPresencialTurma)) {
                $this->Flash->success(__('The ecm curso presencial turma has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ecm curso presencial turma could not be saved. Please, try again.'));
            }
        }
        $ecmProduto = $this->EcmCursoPresencialTurma->EcmProduto->find('list', [
            'keyField' => 'id','valueField' => 'sigla'])
            ->leftJoin(['EcmProdutoEcmTipoProduto' => 'ecm_produto_ecm_tipo_produto'],
            'EcmProdutoEcmTipoProduto.ecm_produto_id = EcmProduto.id')
            ->leftJoin(['EcmTipoProduto' => 'ecm_tipo_produto'],
                'EcmTipoProduto.id = EcmProdutoEcmTipoProduto.ecm_tipo_produto_id')
            ->where(['EcmTipoProduto.id' => 10])->toArray();
        $ecmProduto[0] = "Selecione um produto";
        ksort($ecmProduto);

        $ecmInstrutor = $this->EcmCursoPresencialTurma->EcmInstrutor->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => function ($e) {
                return $e->mdl_user->get('firstname') . ' ' . $e->mdl_user->get('lastname');
            }])->contain(['MdlUser'])->order(['firstname'=>'ASC', 'lastname'=>'ASC']);

        $ecmCursoPresencialTurmaEcmInstrutor = $this->EcmCursoPresencialTurma->EcmCursoPresencialTurmaEcmInstrutor->find('all', ['limit' => 200,
            'fields' => 'ecm_instrutor_id'])
            ->leftJoin(['EcmInstrutor' => 'ecm_instrutor'],
                'EcmInstrutor.id = EcmCursoPresencialTurmaEcmInstrutor.ecm_instrutor_id')
            ->leftJoin(['MdlUser' => 'mdl_user'],
                'MdlUser.id = EcmInstrutor.mdl_user_id')
            ->where('ecm_curso_presencial_turma_id='.$id)
            ->order(['firstname'=>'ASC', 'lastname'=>'ASC'])->toArray();

        $ecmInstrutorSelected = [];
        foreach ($ecmCursoPresencialTurmaEcmInstrutor as $key => $value){
            array_push($ecmInstrutorSelected, $value->ecm_instrutor_id);
        }

        $ecmCursoPresencialLocal = $this->EcmCursoPresencialTurma->EcmCursoPresencialData->EcmCursoPresencialLocal
            ->find('list', ['limit' => 200,'keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $ecmCursoPresencialLocal[0] = "Selecione um Local";
        ksort($ecmCursoPresencialLocal);

        $this->set(compact('ecmCursoPresencialTurma', 'ecmProduto', 'ecmInstrutor', 'ecmInstrutorSelected',
            'ecmCursoPresencialData', 'ecmCursoPresencialLocal'));
        $this->set('_serialize', ['ecmCursoPresencialTurma']);

        $valor_produto = array('true' => 'Sim', 'false' => 'Não');
        $this->set('valor_produto', $valor_produto);

        $status = array('Ativo' => 'Ativo', 'Cancelado' => 'Cancelado');
        $this->set('status', $status);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Curso Presencial Turma id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmCursoPresencialTurma = $this->EcmCursoPresencialTurma->get($id);
        if ($this->EcmCursoPresencialTurma->delete($ecmCursoPresencialTurma)) {
            $this->Flash->success(__('The ecm curso presencial turma has been deleted.'));
        } else {
            $this->Flash->error(__('The ecm curso presencial turma could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
