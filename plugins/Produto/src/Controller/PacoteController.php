<?php
namespace Produto\Controller;
use Cake\Event\Event;

/**
 * EcmProduto Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class PacoteController extends ProdutoController
{

    public function beforeFilter(Event $event)
    {
        $this->tipoProduto = 17;

        if($this->request->action != 'index' AND 
        $this->request->action != 'add' AND 
        $this->request->action != 'getCourses') {
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
        $this->filtroIndex();

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
                $tipoproduto = array("17");
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

                $this->loadModel('Imagem.EcmImagem');
                $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

                unset($this->request->data["mdl_course"]);

                $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

                if (empty($ecmProduto->get('referencia')))
                    $ecmProduto->set('referencia', null);

                if ($this->EcmProduto->save($ecmProduto)) {

                    foreach ($this->request->data['valores'] as $key => $value) {
                        $ecmProdutoMdlCourse = $this->EcmProduto->ecmProdutoMdlCourse->newEntity();
                        $ecmProdutoMdlCourse->ecm_produto_id = $ecmProduto->id;
                        $ecmProdutoMdlCourse->mdl_course_id = $key;
                        $ecmProdutoMdlCourse->preco = $value;
                        $this->EcmProduto->ecmProdutoMdlCourse->save($ecmProdutoMdlCourse);
                    }

                    $ecmProdutoPacote = $this->EcmProduto->EcmProdutoPacote->newEntity();
                    $ecmProdutoPacote->fullname = $ecmProduto->nome;
                    $ecmProdutoPacote->shortname = $ecmProduto->sigla;
                    $ecmProdutoPacote->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProdutoPacote->timecreated = time();
                    $ecmProdutoPacote->ecm_produto_id = $ecmProduto->id;
                    $this->EcmProduto->EcmProdutoPacote->save($ecmProdutoPacote);

                    $this->Flash->success(__('The ecm produto has been saved.'));
                    return $this->redirect(['controller' => 'produto-info', 'action' => 'edit', $ecmProduto->id]);
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
                    ], 
                    'ecm_tipo_produto_id IN ' => [45,
                        $this->tipoProduto
                    ]
                ]
            ])
            ->order(array('EcmTipoProduto.ordem' => 'ASC'));

        $this->set(compact('ecmProduto', 'ecmTipoproduto'));
        $this->set('_serialize', ['ecmProduto']);

        parent::add();
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
            'contain' => [
                'EcmTipoProduto', 'EcmImagem', 'EcmProdutoPacote', 'MdlCourse', 
                'EcmProdutoMdlCourse' => ['MdlCourse']
            ] 
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if($this->request->data["mdl_course"]["_ids"] == "0"){
                $this->Flash->error(__('Favor, selecione um curso.'));
            } else {
                $tipoproduto = array("17");
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
                
                $this->loadModel('Imagem.EcmImagem');
                $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

                unset($this->request->data["mdl_course"]);

                $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

                if(empty($ecmProduto->get('referencia')))
                    $ecmProduto->set('referencia', null);

                if ($this->EcmProduto->save($ecmProduto)) {

                    $this->EcmProduto->ecmProdutoMdlCourse->deleteAll([
                        'ecm_produto_id' => $ecmProduto->id, 'mdl_course_id NOT IN' => array_keys($this->request->data['valores'])
                    ]);
                    foreach ($this->request->data['valores'] as $key => $value) {
                        if(!$ecmProdutoMdlCourse = $this->EcmProduto->ecmProdutoMdlCourse->find()->where([
                            'ecm_produto_id' => $ecmProduto->id, 'mdl_course_id' => $key
                        ])->first()){
                            $ecmProdutoMdlCourse = $this->EcmProduto->ecmProdutoMdlCourse->newEntity();
                            $ecmProdutoMdlCourse->ecm_produto_id = $ecmProduto->id;
                            $ecmProdutoMdlCourse->mdl_course_id = $key;
                        }
                        $ecmProdutoMdlCourse->preco = $value;
                        $this->EcmProduto->ecmProdutoMdlCourse->save($ecmProdutoMdlCourse);
                    }

                    $ecmProduto->ecm_produto_pacote->fullname = $this->request->data['nome'];
                    $ecmProduto->ecm_produto_pacote->shortname = $this->request->data['sigla'];
                    $ecmProduto->ecm_produto_pacote->enrolperiod = $this->request->data['enrolperiod'];
                    $ecmProduto->ecm_produto_pacote->timecreated = time();
                    $this->EcmProduto->EcmProdutoPacote->save($ecmProduto->ecm_produto_pacote);

                    $this->Flash->success(__('The ecm produto has been saved.'));
                    return $this->redirect(['controller' => 'produto-info', 'action' => 'edit', $ecmProduto->id]);
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
    }

    public function getCourses()
    {
        $this->autoRender = false;
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        if($this->request->is('get') && !empty($this->request->query) && !is_null($this->request->query('cursos'))){
            $idCursos = $this->request->query('cursos');

            $this->loadModel('Produto.EcmProduto');
            $modulos = $this->EcmProduto->find()->select(['id' => 'MdlCourse.id', 'preco', 'shortname' => 'MdlCourse.shortname'])
                ->matching('MdlCourse', function ($q) use ($idCursos) {
                    return $q->where(['MdlCourse.id IN' => $idCursos]);
                })
                ->contain(['MdlCourse'])->where(['refcurso' => 'true'])->toArray();

            echo json_encode($modulos);
        }
    }
}
