<?php
namespace Produto\Controller;

/**
 * EcmProduto Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class EcmProdutoController extends AppController
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
        $conditions = [];
        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($conditions, 'nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['sigla']) && !empty($this->request->query['sigla'])){
                array_push($conditions, 'sigla LIKE "'.$this->request->query['sigla'].'"');
            }
            if(isset($this->request->query['habilitado']) && !empty($this->request->query['habilitado'])){
                array_push($conditions, 'habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
            if(isset($this->request->query['visivel']) && !empty($this->request->query['visivel'])){
                array_push($conditions, 'visivel LIKE "'.$this->request->query['visivel'].'"');
            }
            if(isset($this->request->query['idtop']) && !empty($this->request->query['idtop'])){
                array_push($conditions, 'idtop LIKE "%'.$this->request->query['idtop'].'%"');
            }
        }

        $this->paginate = ['conditions' => $conditions];

        $ecmProduto = $this->paginate($this->EcmProduto);

        $sigla = $this->EcmProduto->find('list', ['keyField' => 'sigla', 'valueField' => 'sigla'])->toArray();
        array_unshift($sigla, "Todas as siglas");

        $ecmProdutoEcmProduto = $this->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'id'
        ])->matching('EcmTipoProduto', function($q){
            return $q->where(['EcmTipoProduto.id' => 47]);
        })->toList();

        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não', 'excluido' => 'Excluido'];
        $visivel = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmProduto', 'sigla', 'habilitado', 'visivel', 'ecmProdutoEcmProduto'));
        $this->set('_serialize', ['ecmProduto']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmProduto = $this->EcmProduto->get($id, [
            'contain' => ['EcmTipoProduto' => ['sort' => ['EcmTipoProduto.ordem' => 'ASC']],
                'MdlCourse', 'MdlFase',
                'EcmProdutoInfo' => ['EcmProdutoInfoArquivos', 'EcmProdutoInfoConteudo'],
                'EcmProdutoEcmProduto' => ['EcmProduto' => ['EcmTipoProduto' => ['conditions' => ['EcmTipoProduto.id' => 48]]]]
            ]
        ]);

        $this->set('ecmProduto', $ecmProduto);
        $this->set('_serialize', ['ecmProduto']);

        $ecmTipoProdutoAll = $this->EcmProduto->EcmTipoProduto->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => 'nome'])->toArray();

        $this->set('ecmTipoProdutoAll', $ecmTipoProdutoAll);
        $this->set('_serialize', ['ecmTipoProdutoAll']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmProduto = $this->EcmProduto->newEntity();
        if ($this->request->is('post')) {
            $tipoproduto = array();
            foreach ($this->request->data as $key => $value){
                if(strpos($key, "selectTipo") !== false){
                    if($value == "1"){
                        $id = explode("_", $key);
                        array_shift($id);
                        $tipoproduto = array_merge($tipoproduto,$id);
                    }
                    unset($this->request->data[$key]);
                }
            }
            $tipoproduto = array_unique($tipoproduto);
            $this->request->data["ecm_tipo_produto"] = array("_ids" => $tipoproduto);
            if($this->request->data["refcurso"] == "1"){
                $this->request->data["refcurso"] = "true";
            }else{
                $this->request->data["refcurso"] = "false";
            }

            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

            $optionSave = [];
            if(array_search(16, $tipoproduto) === false && array_search(17, $tipoproduto)  === false) {
                unset($this->request->data['enrolperiod']);
                $optionSave = ['validate' => 'removeValidationEnrolperiod'];
            }

            $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, $optionSave);

            if(empty($ecmProduto->get('referencia')))
                $ecmProduto->set('referencia', null);

            if ($this->EcmProduto->save($ecmProduto)) {
                if(array_search(16, $tipoproduto)){
                    $ecmProdutoPrazoExtra = $this->EcmProduto->EcmProdutoPrazoExtra->newEntity();
                    $ecmProdutoPrazoExtra->timecreated = time();
                    $ecmProdutoPrazoExtra->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProdutoPrazoExtra->ecm_produto_id = $ecmProduto->id;
                    $this->EcmProduto->EcmProdutoPrazoExtra->save($ecmProdutoPrazoExtra);
                } else if(array_search(17, $tipoproduto)){
                    $ecmProdutoPacote = $this->EcmProduto->EcmProdutoPacote->newEntity();
                    $ecmProdutoPacote->fullname = $ecmProduto->nome;
                    $ecmProdutoPacote->shortname = $ecmProduto->sigla;
                    $ecmProdutoPacote->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProdutoPacote->timecreated = time();
                    $ecmProdutoPacote->ecm_produto_id = $ecmProduto->id;
                    $this->EcmProduto->EcmProdutoPacote->save($ecmProdutoPacote);
                }
                $this->Flash->success(__('The ecm produto has been saved.'));
                $controller = array_search(47, $tipoproduto) === false?'produto-info':'fase';
                return $this->redirect(['controller' => $controller, 'action' => 'edit', $ecmProduto->id]);
            } else {
                $this->Flash->error(__('The ecm produto could not be saved. Please, try again.'));
            }
        }
        $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200])->where(['habilitado' => 'true']);
        $mdlCourse = $this->EcmProduto->MdlCourse->find('list', ['limit' => 200,
            'keyField' => 'id',
            'valueField' => 'shortname'
        ]);
        $this->set(compact('ecmProduto', 'ecmTipoproduto', 'mdlCourse'));
        $this->set('_serialize', ['ecmProduto']);

        $ecmTipoproduto->order(array('ordem' => 'ASC'));
        $mdlCourse->where(['MdlCourse.id !=' => 1]);

        $moeda = array('real' => 'Real R$', 'dolar' => 'Dolar US$');
        $this->set('moeda', $moeda);

        $parcelas = array(0 => 'Utilizar parcela mínima de R$ 100,00', 1 => 'Em até 1 Parcela');
        for($i = 2;$i<=12;$i++){
            $parcelas[$i] = "Em até $i Parcelas";
        }
        $this->set('parcela', $parcelas);

        $habilitado = array('true' => 'ativo', 'false' => 'desativo');
        $this->set('habilitado', $habilitado);

        $visivel = array('true' => 'Sim', 'false' => 'Não');
        $this->set('visivel', $visivel);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmProduto = $this->EcmProduto->get($id, [
            'contain' => ['EcmTipoProduto', 'MdlCourse', 'EcmImagem', 'EcmProdutoPrazoExtra', 'EcmProdutoPacote']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tipoproduto = array();
            foreach ($this->request->data as $key => $value){
                if(strpos($key, "selectTipo") !== false){
                    if($value == "1"){
                        $idTipo = explode("_", $key);
                        array_shift($idTipo);
                        $tipoproduto = array_merge($tipoproduto,$idTipo);
                    }
                    unset($this->request->data[$key]);
                }
            }
            $tipoproduto = array_unique($tipoproduto);
            $this->request->data["ecm_tipo_produto"] = array("_ids" => $tipoproduto);
            if($this->request->data["refcurso"] == "1"){
                $this->request->data["refcurso"] = "true";
            }else{
                $this->request->data["refcurso"] = "false";
            }

            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

            $optionSave = [];
            if(array_search(16, $tipoproduto) === false && array_search(17, $tipoproduto)  === false) {
                unset($this->request->data['enrolperiod']);
                $optionSave = ['validate' => 'removeValidationEnrolperiod'];
            }

            $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, $optionSave);

            if(empty($ecmProduto->get('referencia')))
                $ecmProduto->set('referencia', null);

            if ($this->EcmProduto->save($ecmProduto)) {
                if(in_array(16, $tipoproduto)){
                    if(!isset($ecmProduto->ecm_produto_prazo_extra)){
                        $ecmProduto->ecm_produto_prazo_extra = $this->EcmProduto->EcmProdutoPrazoExtra->newEntity();
                        $ecmProduto->ecm_produto_prazo_extra->ecm_produto_id = $ecmProduto->id;
                    }
                    $ecmProduto->ecm_produto_prazo_extra->timecreated = time();
                    $ecmProduto->ecm_produto_prazo_extra->enrolperiod = $this->request->data['enrolperiod'];
                    $this->EcmProduto->EcmProdutoPrazoExtra->save($ecmProduto->ecm_produto_prazo_extra);
                } else if(isset($ecmProduto->ecm_produto_prazo_extra)){
                    $this->EcmProduto->EcmProdutoPrazoExtra->delete($ecmProduto->ecm_produto_prazo_extra);
                }
                if(in_array(17, $tipoproduto)){
                    if(!isset($ecmProduto->ecm_produto_pacote)){
                        $ecmProduto->ecm_produto_pacote = $this->EcmProduto->EcmProdutoPacote->newEntity();
                        $ecmProduto->ecm_produto_pacote->ecm_produto_id = $ecmProduto->id;
                    }
                    $ecmProduto->ecm_produto_pacote->fullname = $this->request->data['nome'];
                    $ecmProduto->ecm_produto_pacote->shortname = $this->request->data['sigla'];
                    $ecmProduto->ecm_produto_pacote->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProduto->ecm_produto_pacote->timecreated = time();
                    $this->EcmProduto->EcmProdutoPacote->save($ecmProduto->ecm_produto_pacote);
                } else if(isset($ecmProduto->ecm_produto_pacote)){
                    $this->EcmProduto->EcmProdutoPacote->delete($ecmProduto->ecm_produto_pacote);
                }

                $this->Flash->success(__('The ecm produto has been saved.'));
                $controller = array_search(47, $tipoproduto) === false?'produto-info':'fase';
                return $this->redirect(['controller' => $controller, 'action' => 'edit', $ecmProduto->id]);
            } else {
                $this->Flash->error(__('The ecm produto could not be saved. Please, try again.'));
            }
        }

        $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200,
            'fields' => ['id','nome','ecm_tipo_produto_id','EcmProdutoTipoproduto.id'],
        ])->leftJoin(['EcmProdutoTipoproduto' => 'ecm_produto_ecm_tipo_produto'],
            'EcmProdutoTipoproduto.ecm_produto_id = '.$id.'
              AND EcmProdutoTipoproduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->where(['habilitado' => 'true']);

        $mdlCourse = $this->EcmProduto->MdlCourse->find('list', ['limit' => 200,
            'keyField' => 'id',
            'valueField' => 'shortname']);
        $this->set(compact('ecmProduto', 'ecmTipoproduto', 'mdlCourse'));
        $this->set('_serialize', ['ecmProduto']);

        $ecmTipoproduto->order(array('EcmTipoProduto.ordem' => 'ASC'))->where(['habilitado' => 'true']);
        $mdlCourse->where(['MdlCourse.id !=' => 1]);

        $moeda = array('real' => 'Real R$', 'dolar' => 'Dolar US$');
        $this->set('moeda', $moeda);

        $parcelas = array(0 => 'Utilizar parcela mínima de R$ 100,00', 1 => 'Em até 1 Parcela');
        for($i = 2;$i<=12;$i++){
            $parcelas[$i] = "Em até $i Parcelas";
        }
        $this->set('parcela', $parcelas);

        $habilitado = array('true' => 'ativo', 'false' => 'desativo');
        $this->set('habilitado', $habilitado);

        $visivel = array('true' => 'Sim', 'false' => 'Não');
        $this->set('visivel', $visivel);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmProduto = $this->EcmProduto->get($id);
        try {
            if ($this->EcmProduto->delete($ecmProduto)) {
                $this->Flash->success(__('O produto foi deletado.'));
                return $this->redirect(['action' => 'index']);
            }
        } catch(\PDOException $e) {
            //$this->Flash->error($e->getMessage());
        }
        $ecmProduto->habilitado = "excluido";
        $ecmProduto->visivel = "false";
        if ($this->EcmProduto->save($ecmProduto)) {
            $this->Flash->success(__('O produto foi deletado de forma lógica.'));
        } else {
            $this->Flash->error(__('Não foi possivel deletar o produto. Favor, tente novamente.'));
        };
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Alterar Status method
     *
     * @param Integer|null $id Ecm Produto id.
     * @param string|null $status Ecm Produto habilitado.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function alterarStatus($id = null, $parametro = null, $status = null)
    {
        $ecmProduto = $this->EcmProduto->get($id);
        $ecmProduto->$parametro = $status != "true" ? "true" : "false";
        if ($this->EcmProduto->save($ecmProduto)) {
            $this->Flash->success(__('O status '.$parametro.' do produto foi alterado com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possivel alterar o status '.$parametro.' do produto. Favor, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
