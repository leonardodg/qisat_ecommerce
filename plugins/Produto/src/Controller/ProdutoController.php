<?php
namespace Produto\Controller;
use Cake\Event\Event;

/**
 * EcmProduto Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class ProdutoController extends AppController
{

    protected $tipoProduto;
    protected $conditions = [];

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('TinyMCE');
        $this->loadModel('Produto.EcmProduto');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    protected function filtroIndex()
    {
        if(count($this->request->query)){
            if(isset($this->request->query['nome']) && !empty($this->request->query['nome'])){
                array_push($this->conditions, 'EcmProduto.nome LIKE "%'.$this->request->query['nome'].'%"');
            }
            if(isset($this->request->query['sigla']) && !empty($this->request->query['sigla'])){
                array_push($this->conditions, 'EcmProduto.sigla LIKE "'.$this->request->query['sigla'].'"');
            }
            if(isset($this->request->query['habilitado']) && !empty($this->request->query['habilitado'])){
                array_push($this->conditions, 'EcmProduto.habilitado LIKE "'.$this->request->query['habilitado'].'"');
            }
            if(isset($this->request->query['visivel']) && !empty($this->request->query['visivel'])){
                array_push($this->conditions, 'EcmProduto.visivel LIKE "'.$this->request->query['visivel'].'"');
            }
            if(isset($this->request->query['idtop']) && !empty($this->request->query['idtop'])){
                array_push($this->conditions, 'EcmProduto.idtop LIKE "%'.$this->request->query['idtop'].'%"');
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $habilitado = ['Todos', 'true' => 'Sim', 'false' => 'Não', 'excluido' => 'Excluido'];
           $visivel = ['Todos', 'true' => 'Sim', 'false' => 'Não'];

        $this->set(compact('habilitado', 'visivel'));

        $this->render('Produto.Produto/index');
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
            'contain' => ['EcmTipoProduto' => ['sort' => ['EcmTipoProduto.ordem' => 'ASC']], 'MdlCourse',
                'EcmProdutoInfo' => ['EcmProdutoInfoConteudo', 'EcmProdutoInfoFaq', 'EcmProdutoInfoArquivos' => [
                    'EcmProdutoInfoArquivosTipos'
                ]], 'EcmProdutoPacote', 'EcmProdutoPrazoExtra',
                'EcmProdutoEcmProduto' => ['EcmProduto']
            ]
        ]);

        $ecmTipoProdutoAll = $this->EcmProduto->EcmTipoProduto->find('list', ['limit' => 200,
            'keyField' => 'id', 'valueField' => 'nome'])->toArray();

        $this->loadModel('Configuracao.EcmConfig');

        $dominioAcessoSite = $this->EcmConfig->find('all', ['fields' => 'valor'])
            ->where(['nome' => 'dominio_acesso_site'])->first()->valor;

        $this->set(compact('ecmProduto', 'ecmTipoProdutoAll', 'dominioAcessoSite'));
        $this->set('_serialize', ['ecmProduto']);

        $this->render('Produto.Produto/view');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mdlCourse = $this->EcmProduto->MdlCourse->find('list', [
            'keyField' => 'id',
            'valueField' => 'shortname'
        ])->where(['MdlCourse.id !=' => 1])->toArray();

        $moeda = array('real' => 'Real R$', 'dolar' => 'Dolar US$');

        $parcela = array(0 => 'Utilizar parcela mínima de R$ 100,00', 1 => 'Em até 1 Parcela');
        for($i = 2;$i<=12;$i++){
            $parcela[$i] = "Em até $i Parcelas";
        }

        $habilitado = array('true' => 'ativo', 'false' => 'desativo');

        $visivel = array('true' => 'Sim', 'false' => 'Não');

        if(is_numeric($this->tipoProduto)){
            $tipoProduto = $this->tipoProduto;
            $this->set(compact('tipoProduto'));
        }

        $this->set(compact('mdlCourse', 'moeda', 'parcela', 'habilitado', 'visivel'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $mdlCourse = $this->EcmProduto->MdlCourse->find('list', [
            'keyField' => 'id',
            'valueField' => 'shortname'])
            ->where(['MdlCourse.id !=' => 1])->toArray();

        $moeda = array('real' => 'Real R$', 'dolar' => 'Dolar US$');

        $parcela = array(0 => 'Utilizar parcela mínima de R$ 100,00', 1 => 'Em até 1 Parcela');
        for($i = 2;$i<=12;$i++){
            $parcela[$i] = "Em até $i Parcelas";
        }

        $habilitado = array('true' => 'ativo', 'false' => 'desativo');

        $visivel = array('true' => 'Sim', 'false' => 'Não');

        if(is_numeric($this->tipoProduto)){
            $tipoProduto = $this->tipoProduto;
            $this->set(compact('tipoProduto'));
        }

        $this->set(compact('mdlCourse', 'moeda', 'parcela', 'habilitado', 'visivel'));
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
