<?php
namespace Produto\Controller;
use Cake\Event\Event;

/**
 * EcmProduto Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class PrazoExtraController extends ProdutoController
{

    public function beforeFilter(Event $event)
    {
        $this->tipoProduto = 16;

        if($this->request->action != 'index' AND $this->request->action != 'add') {
            $id = $this->request->params['id'];

            $ecmProduto = $this->EcmProduto->find()
                ->matching('EcmTipoProduto', function($q){
                    return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
                })->where(['EcmProduto.id' => $id])
                ->group('EcmProduto.id')->first();

            if(is_null($ecmProduto)){
                $this->Flash->error(__('Este produto não pertence a essa categoria.'));
                return $this->redirect(['plugin' => 'Produto', 'controller' => 'EcmProduto', 'action' => 'index']);
            }
        }
        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        parent::index();

        $ecmProduto = $this->EcmProduto->find()
            ->matching('EcmTipoProduto', function($q) {
                return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
            })->where($this->conditions)
            ->group('EcmProduto.id');

        $sigla = $this->EcmProduto->find('list', ['keyField' => 'sigla', 'valueField' => 'sigla'])
            ->matching('EcmTipoProduto', function($q) {
                return $q->where(['EcmTipoProduto.id' => $this->tipoProduto]);
            });

        $ecmProduto = $this->paginate($ecmProduto);

        $sigla = $sigla->toArray();
        array_unshift($sigla, "Todas as siglas");

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        $this->set(compact('ecmProduto', 'sigla'));
        $this->set('_serialize', ['ecmProduto']);

        parent::index();
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
            if($this->request->data["mdl_course"]["_ids"] == "0"){
                $this->Flash->error(__('Favor, selecione um curso.'));
            } else {
                $tipoproduto = array("16");
                foreach ($this->request->data as $key => $value) {
                    if (strpos($key, "selectTipo") !== false) {
                        if ($value == "1") {
                            $id = explode("_", $key);
                            array_shift($id);
                            $tipoproduto = array_merge($tipoproduto, $id);
                        }
                        unset($this->request->data[$key]);
                    }
                }
                $tipoproduto = array_unique($tipoproduto);

                $this->request->data["ecm_tipo_produto"] = array("_ids" => $tipoproduto);
                $this->request->data["refcurso"] = "true";

                $this->loadModel('Imagem.EcmImagem');
                $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

                $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

                if (empty($ecmProduto->get('referencia')))
                    $ecmProduto->set('referencia', null);

                if ($this->EcmProduto->save($ecmProduto)) {

                    $ecmProdutoPrazoExtra = $this->EcmProduto->EcmProdutoPrazoExtra->newEntity();
                    $ecmProdutoPrazoExtra->timecreated = time();
                    $ecmProdutoPrazoExtra->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProdutoPrazoExtra->ecm_produto_id = $ecmProduto->id;
                    $this->EcmProduto->EcmProdutoPrazoExtra->save($ecmProdutoPrazoExtra);

                    $this->Flash->success(__('The ecm produto has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The ecm produto could not be saved. Please, try again.'));
                }
            }
        }
        $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200])
            ->where(['habilitado' => 'true',
                'OR' => [
                    'id IN' => [45,29,39,
                        $this->tipoProduto
                    ], 'ecm_tipo_produto_id' => 45
                ]
            ])
            ->order(array('EcmTipoProduto.ordem' => 'ASC'));

        $ecmProduto->refcurso = true;

        $this->set(compact('ecmProduto', 'ecmTipoproduto'));
        $this->set('_serialize', ['ecmProduto']);

        parent::add();

        $this->render('Produto.Pacote/add');
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
            'contain' => ['EcmTipoProduto', 'MdlCourse', 'EcmImagem', 'EcmProdutoPrazoExtra']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if($this->request->data["mdl_course"]["_ids"] == "0"){
                $this->Flash->error(__('Favor, selecione um curso.'));
            } else {
                $tipoproduto = array("16");
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
                $this->request->data["refcurso"] = "true";

                $this->loadModel('Imagem.EcmImagem');
                $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

                $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

                if(empty($ecmProduto->get('referencia')))
                    $ecmProduto->set('referencia', null);

                if ($this->EcmProduto->save($ecmProduto)) {

                    $ecmProduto->ecm_produto_prazo_extra->timecreated = time();
                    $ecmProduto->ecm_produto_prazo_extra->enrolperiod = $this->request->data['enrolperiod'];
                    $this->EcmProduto->EcmProdutoPrazoExtra->save($ecmProduto->ecm_produto_prazo_extra);

                    $this->Flash->success(__('The ecm produto has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The ecm produto could not be saved. Please, try again.'));
                }
            }
        }

        $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200,
            'fields' => ['id','nome','ecm_tipo_produto_id','EcmProdutoTipoproduto.id'],
        ])->leftJoin(['EcmProdutoTipoproduto' => 'ecm_produto_ecm_tipo_produto'],
            'EcmProdutoTipoproduto.ecm_produto_id = '.$id.'
              AND EcmProdutoTipoproduto.ecm_tipo_produto_id = EcmTipoProduto.id')
            ->order(array('EcmTipoProduto.ordem' => 'ASC'))
            ->where(['habilitado' => 'true',
                'OR' => [
                    'EcmTipoProduto.id IN' => [45,29,39,
                        $this->tipoProduto
                    ],
                    'EcmTipoProduto.ecm_tipo_produto_id' => 45
                ]
            ]);

        $ecmProduto->enrolperiod = $ecmProduto->ecm_produto_pacote->enrolperiod;

        $this->set(compact('ecmProduto', 'ecmTipoproduto'));
        $this->set('_serialize', ['ecmProduto']);

        parent::edit();

        $this->render('Produto.Pacote/edit');
    }
}
