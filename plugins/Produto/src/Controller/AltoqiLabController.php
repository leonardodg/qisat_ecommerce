<?php
namespace Produto\Controller;
use Cake\Event\Event;

/**
 * EcmProduto Controller
 *
 * @property \Produto\Model\Table\EcmProdutoTable $EcmProduto */
class AltoqiLabController extends ProdutoController
{

    public function beforeFilter(Event $event)
    {
        $id = $this->request->params['id'];

        if($this->request->action == 'edit'){
            $ecmProduto = $this->EcmProduto->find()
                ->matching('EcmTipoProduto', function($q){
                    return $q->where(['EcmTipoProduto.id' => 51]);
                })->where(['EcmProduto.id' => $id])
                ->group('EcmProduto.id')->first();
            if(!is_null($ecmProduto)){
                return $this->redirect(['plugin' => 'Produto', 'controller' => 'AltoqiLab', 'action' => 'prova', $id]);
            }
        }

        $this->tipoProduto = 47;

        if($this->request->action != 'index' AND $this->request->action != 'edit'
                AND $this->request->action != 'addCourseOrdem' AND $this->request->action != 'prova') {

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
     * Edit method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if($this->EcmProduto->exists(['id' => $id])){
            $ecmProduto = $this->EcmProduto->get($id, [
                'contain' => [
                    'EcmTipoProduto', 'EcmImagem', 'MdlCourse',
                    'MdlFase' => ['MdlCourseMdlFase'],
                    'EcmProdutoEcmProduto' => [
                        'EcmProduto' => [
                            'EcmTipoProduto' => function($q){
                                return $q->where([
                                    'EcmTipoProduto.id in' => [48, 51]
                                ]);
                            }
                        ]
                    ]
                ]
            ]);

            $valuesSoftware = [];
            $valuesProvas= [];
            foreach($ecmProduto['ecm_produto_ecm_produto'] as $produto){
                $tipo = current($produto->get('ecm_produto')->get('ecm_tipo_produto'));

                if($tipo->get('id') == 48)
                    $valuesSoftware[] = $produto->get('ecm_produto');
                elseif($tipo->get('id') == 51)
                    $valuesProvas[] = $produto->get('ecm_produto');
            }

            $ecmProduto->set('ecm_produto_prova', $valuesProvas);
            $ecmProduto->set('ecm_produto_altoqi', $valuesSoftware);
        }else{
            $ecmProduto = $this->EcmProduto->newEntity();
            if(is_numeric($this->tipoProduto)){
                $tipoProduto = $this->tipoProduto;
                $this->set(compact('tipoProduto'));
            }
        }

        $this->loadModel('Produto.MdlFase');
        if ($this->request->is(['patch', 'post', 'put'])) {
            if($this->request->data["mdl_course"]["_ids"] == "0"){
                $this->Flash->error(__('Favor, selecione um curso.'));
            } else {
                $tipoproduto = array("47");
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
                $this->request->data["refcurso"] = "false";

                $this->loadModel('Imagem.EcmImagem');
                $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

                $mdl_fase = $this->request->data['mdl_fase'];
                if(!$mdl_fase['mdl_fase_id']) unset($mdl_fase['mdl_fase_id']);
                unset($this->request->data['mdl_fase']);
                $ecm_produto = $this->request->data['ecm_produto_altoqi'];
                unset($this->request->data['ecm_produto_altoqi']);
                $mdl_course_id = $this->request->data['mdl_course_id'];
                unset($this->request->data['mdl_course_id']);
                $mdl_course_conclusion_id = $this->request->data('mdl_course_conclusion_id');
                unset($this->request->data['mdl_course_conclusion_id']);
                $mdl_course = explode(',', $this->request->data['mdl_course_ordem']);
                unset($this->request->data['mdl_course_ordem']);

                if(!empty($this->request->data['ecm_produto_prova']['_ids'])){
                    if(!empty($ecm_produto['_ids']))
                        $ecm_produto['_ids'] = array_merge($ecm_produto['_ids'], $this->request->data['ecm_produto_prova']['_ids']);
                    else
                        $ecm_produto = $this->request->data['ecm_produto_prova'];
                }

                $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

                if(empty($ecmProduto->get('referencia')))
                    $ecmProduto->set('referencia', null);

                if ($this->EcmProduto->save($ecmProduto)) {
                    $mdl_fase['ecm_produto_id'] = $ecmProduto->id;


                    if(empty($mdl_fase['id'])){
                        $mdlFase = $this->MdlFase->newEntity($mdl_fase);
                    } else {
                        $mdlFase = $this->MdlFase->get($mdl_fase['id']);
                        $mdlFase = $this->MdlFase->patchEntity($mdlFase, $mdl_fase);
                    }
                    if ($this->MdlFase->save($mdlFase)) {
                        //Corrigindo Ordenação dos cursos
                        $ecmProdutoMdlCourse = $this->MdlFase->EcmProduto->EcmProdutoMdlCourse->find()
                            ->where(['ecm_produto_id' => $id])->toArray();
                        foreach($ecmProdutoMdlCourse as $value){
                            $value->ordem = array_search($value->mdl_course_id,$mdl_course);
                            $this->MdlFase->EcmProduto->EcmProdutoMdlCourse->save($value);
                        }
                        //Permanecendo os produtos AltoQi
                        if(is_array($ecm_produto['_ids'])){
                            foreach($ecm_produto['_ids'] as $value){
                                $ecmProdutoEcmProduto = $this->MdlFase->EcmProduto->EcmProdutoEcmProduto->newEntity();
                                $ecmProdutoEcmProduto->ecm_produto_id = $ecmProduto->get('id');
                                $ecmProdutoEcmProduto->ecm_produto_relacionamento_id = $value;
                                $this->MdlFase->EcmProduto->EcmProdutoEcmProduto->save($ecmProdutoEcmProduto);
                            }
                        }
                        $ecmProdutoEcmProduto = $this->MdlFase->EcmProduto->EcmProdutoEcmProduto->find()
                            ->where(['ecm_produto_id' => $id])->toArray();
                        foreach($ecmProdutoEcmProduto as $key => $value){
                            if(is_array($ecm_produto['_ids']) &&
                                array_search($value->ecm_produto_relacionamento_id,$ecm_produto['_ids']) !== false)
                                unset($ecmProdutoEcmProduto[$key]);
                        }
                        foreach($ecmProdutoEcmProduto as $value)
                            $this->MdlFase->EcmProduto->EcmProdutoEcmProduto->delete($value);
                        //Permanecendo as dependencias
                        if(!is_null($mdl_course_conclusion_id)) {
                            foreach ($mdl_course_conclusion_id as $id => $value) {
                                if ($mdl_course_id[$id] != 0 && $value != 0) {
                                    $mdlCourseMdlFase = $this->MdlFase->newEntity();
                                    $mdlCourseMdlFase->mdl_fase_id = $mdlFase->id;
                                    $mdlCourseMdlFase->mdl_course_id = $mdl_course_id[$id];
                                    $mdlCourseMdlFase->mdl_course_conclusion_id = $value;
                                    $this->MdlFase->MdlCourseMdlFase->save($mdlCourseMdlFase);
                                }
                            }
                        }
                        $mdlCourseMdlFase = $this->MdlFase->MdlCourseMdlFase->find()
                            ->where(['mdl_fase_id' => $mdlFase->id])->toArray();
                        foreach($mdlCourseMdlFase as $key => $value){
                            if(array_search($value->mdl_course_id,$mdl_course_id) !== false
                                && array_search($value->mdl_course_conclusion_id,$mdl_course_conclusion_id) !== false)
                                unset($mdlCourseMdlFase[$key]);
                        }
                        foreach($mdlCourseMdlFase as $value)
                            $this->MdlFase->MdlCourseMdlFase->delete($value);

                        $this->Flash->success(__('A fase da trilha foi salva com sucesso.'));
                        return $this->redirect(['controller' => 'produto-info', 'action' => 'edit', $id]);
                    } else {
                        $this->Flash->error(__('Não foi possivel salvar a fase da trilha. Favor, tente novamente.'));
                    }
                } else {
                    $this->Flash->error(__('Não foi possivel salvar o produto. Favor, tente novamente.'));
                }
            }
        }

        if($this->EcmProduto->exists(['id' => $id])){
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200,
                'fields' => ['id','nome','ecm_tipo_produto_id','EcmProdutoTipoproduto.id'],
            ])->leftJoin(['EcmProdutoTipoproduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoTipoproduto.ecm_produto_id = '.$id.'
              AND EcmProdutoTipoproduto.ecm_tipo_produto_id = EcmTipoProduto.id');
        }else{
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200]);
        }
        $ecmTipoproduto->where(['habilitado' => 'true',
                'OR' => [
                    'EcmTipoProduto.id IN' => [45,29,39,
                        $this->tipoProduto
                    ], 'EcmTipoProduto.ecm_tipo_produto_id' => 45
                ]
            ])
            ->order(array('EcmTipoProduto.ordem' => 'ASC'));

        $ecmProdutoAltoqi = $this->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ])->matching('EcmTipoProduto', function($q){
            return $q->where(['EcmTipoProduto.id' => 48]);
        });

        $ecmProdutoProva = $this->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ])->matching('EcmTipoProduto', function($q){
            return $q->where(['EcmTipoProduto.id' => 51]);
        });

        $mdlFaseDependente = $this->MdlFase->find('list', [
            'keyField' => 'id', 'valueField' => 'descricao'
        ])->where(['ecm_produto_id !=' => $id])->toArray();
        $mdlFaseDependente[0] = "Selecione uma fase";
        ksort($mdlFaseDependente);

        $this->set(compact('ecmProduto', 'ecmTipoproduto', 'ecmProdutoAltoqi', 'ecmProdutoProva', 'mdlFaseDependente'));
        $this->set('_serialize', ['ecmProduto']);

        parent::edit();
    }

    /**
     * Prova method
     *
     * @param string|null $id Ecm Produto id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function prova($id = null)
    {
        if($this->EcmProduto->exists(['id' => $id])){
            $ecmProduto = $this->EcmProduto->get($id, [
                'contain' => [
                    'EcmTipoProduto', 'EcmImagem',
                    'EcmProdutoEcmProduto' => [
                        'EcmProduto'
                    ]
                ]
            ]);
        }else{
            $ecmProduto = $this->EcmProduto->newEntity();
            if(is_numeric($this->tipoProduto)){
                $tipoProduto = $this->tipoProduto;
                $this->set(compact('tipoProduto'));
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $mdl_fase = $this->request->data['mdl_fase'];
            unset($this->request->data['mdl_fase']);

            $tipoproduto = array("47", "51");

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
            $this->request->data["refcurso"] = "false";

            $this->loadModel('Imagem.EcmImagem');
            $this->request->data['ecm_imagem'] = $this->EcmImagem->enviarImagem($this->request->data['ecm_imagem'], 'produto');

            $ecmProduto = $this->EcmProduto->patchEntity($ecmProduto, $this->request->data, ['validate' => 'removeValidationEnrolperiod']);

            if(empty($ecmProduto->get('referencia')))
                $ecmProduto->set('referencia', null);

            if ($this->EcmProduto->save($ecmProduto)) {

                $ecmProdutoEcmProduto = $this->EcmProduto->EcmProdutoEcmProduto->newEntity();
                $ecmProdutoEcmProduto->ecm_produto_relacionamento_id = $ecmProduto->id;
                $ecmProdutoEcmProduto->ecm_produto_id = $mdl_fase;
                $this->EcmProduto->EcmProdutoEcmProduto->save($ecmProdutoEcmProduto);

                $ecmProdutoEcmProduto = $this->EcmProduto->EcmProdutoEcmProduto->find()
                    ->where(['ecm_produto_relacionamento_id' => $ecmProduto->id, 'ecm_produto_id !=' => $mdl_fase])
                    ->toArray();
                foreach($ecmProdutoEcmProduto as $value){
                    $this->EcmProduto->EcmProdutoEcmProduto->delete($value);
                }

                $this->Flash->success(__('A prova da fase da trilha foi salva com sucesso.'));
                return $this->redirect(['controller' => 'produto-info', 'action' => 'edit', $id]);
            } else {
                $this->Flash->error(__('Não foi possivel salvar a prova da fase. Favor, tente novamente.'));
            }
        }

        if($this->EcmProduto->exists(['id' => $id])){
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200,
                'fields' => ['id','nome','ecm_tipo_produto_id','EcmProdutoTipoproduto.id'],
            ])->leftJoin(['EcmProdutoTipoproduto' => 'ecm_produto_ecm_tipo_produto'],
                'EcmProdutoTipoproduto.ecm_produto_id = '.$id.'
              AND EcmProdutoTipoproduto.ecm_tipo_produto_id = EcmTipoProduto.id');
        }else{
            $ecmTipoproduto = $this->EcmProduto->EcmTipoProduto->find('all', ['limit' => 200]);
        }
        $ecmTipoproduto->where(['habilitado' => 'true',
            'OR' => [
                'EcmTipoProduto.id IN' => [45,29,39,
                    $this->tipoProduto
                ], 'EcmTipoProduto.ecm_tipo_produto_id IN' => [45,
                    $this->tipoProduto
                ]
            ]
        ])
        ->order(array('EcmTipoProduto.ordem' => 'ASC'));

        $mdlFase = $this->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ])->matching('MdlFase')->toArray();
        $mdlFase[NULL] = "Selecione uma fase";
        ksort($mdlFase);

        $this->set(compact('ecmProduto', 'ecmTipoproduto', 'mdlFase'));
        $this->set('_serialize', ['ecmProduto']);

        parent::edit();
    }

    public function addCourseOrdem()
    {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = $this->request->data['id'];
            $ecmProduto = $this->EcmProduto->find()->select(['id'])
                ->innerJoinWith('MdlCourse', function($q)use($id){
                    return $q->where(['MdlCourse.id' => $id]);
                })
                ->contain(['MdlCourse', 'EcmImagem'])
                ->where(['refcurso' => 'true']);

            $this->loadModel('WebService.MdlCourseModules');
            $modules = $this->MdlCourseModules->find()->where(['course' => $id])->count();

            if($ecmProduto = $ecmProduto->first()){
                echo json_encode(['ecm_produto' => $ecmProduto, 'modules' => $modules]);
            } else {
                $mdlCourse = $this->EcmProduto->MdlCourse->get($id);
                echo json_encode(['ecm_produto' => ['ecm_imagem' => [], 'mdl_course' => [$mdlCourse]],
                    'modules' => $modules]);
            }
        }
    }

    /**
     * deleteDependencia method
     *
     * @param string|null $id Mdl Course Mdl Fase id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function deleteDependencia($mdl_fase_id = null, $mdl_course_id = null, $mdl_course_conclusion_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mdlFase = $this->MdlFase->find()->where(['mdl_fase_id' => $mdl_fase_id,
            'mdl_course_id' => $mdl_course_id, 'mdl_course_conclusion_id' => $mdl_course_conclusion_id]);
        $fasesDeletadas = $this->MdlFase->delete($mdlFase);

        if ($fasesDeletadas)
            $this->Flash->success(__('A dependencia foi deletada.'));
        else
            $this->Flash->error(__('Não foi possivel deletar a dependencia.'));
    }
}
