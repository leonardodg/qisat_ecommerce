<?php
namespace Produto\Controller;

use Produto\Controller\AppController;

/**
 * MdlFase Controller
 *
 * @property \Produto\Model\Table\MdlFaseTable $MdlFase */
class MdlFaseController extends AppController
{

    /**
     * Edit method
     *
     * @param string|null $id Mdl Fase id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mdlFase = $this->MdlFase->find('all')
            ->where(['ecm_produto_id' => $id])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {

            $this->request->data['ecm_produto_id'] = $id;
            $mdl_course_id = $this->request->data['mdl_course_id'];
            unset($this->request->data['mdl_course_id']);
            $mdl_course_conclusion_id = [];
            if(isset($this->request->data['mdl_course_conclusion_id'])){
                $mdl_course_conclusion_id = $this->request->data['mdl_course_conclusion_id'];
                unset($this->request->data['mdl_course_conclusion_id']);
            }
            $ecm_produto = $this->request->data['ecm_produto'];
            unset($this->request->data['ecm_produto']);
            $mdl_course = explode(',',$this->request->data['mdl_course']);
            unset($this->request->data['mdl_course']);
            if(empty($mdlFase))
                $mdlFase = $this->MdlFase->newEntity($this->request->data);
            else
                $mdlFase = $this->MdlFase->patchEntity($mdlFase, $this->request->data);
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
                        $ecmProdutoEcmProduto->ecm_produto_id = $id;
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
                foreach($mdl_course_conclusion_id as $id => $value){
                    if($mdl_course_id[$id] != 0 && $value != 0){
                        $mdlCourseMdlFase = $this->MdlFase->newEntity();
                        $mdlCourseMdlFase->mdl_fase_id = $mdlFase->id;
                        $mdlCourseMdlFase->mdl_course_id = $mdl_course_id[$id];
                        $mdlCourseMdlFase->mdl_course_conclusion_id = $value;
                        $this->MdlFase->MdlCourseMdlFase->save($mdlCourseMdlFase);
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
        }

        $ecmProduto = $this->MdlFase->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ])->matching('EcmTipoProduto', function($q){
            return $q->where(['EcmTipoProduto.id' => 48]);
        });

        $mdlCourse = $this->MdlFase->MdlCourse->find('all')
            ->select(['id','fullname','shortname'])
            ->contain(['EcmProduto' => function($q){
                return $q->matching('EcmTipoProduto', function($q){
                    return $q->where(['EcmTipoProduto.id' => 2]);
                })->contain(['EcmImagem' => function($q){
                    return $q->select(['src', 'EcmProdutoEcmImagem.ecm_produto_id'])
                        ->where(['descricao' => 'Imagens - Capa']);
                }])->select(['id']);
            }])
            ->matching('EcmProduto', function($q)use($id){
                return $q->where(['EcmProduto.id' => $id]);
            })->orderAsc('EcmProdutoMdlCourse.ordem')->toArray();
        $mdlCourseOrdem[0] = "Selecione um curso";
        foreach($mdlCourse as $course){
            $mdlCourseOrdem[$course->id] = $course->shortname;
            foreach($course->ecm_produto as $produto){
                if(!empty($produto->ecm_imagem))
                    $course->src = $produto->ecm_imagem[0]->src;
            }
            unset($course->ecm_produto);
        }
        ksort($mdlCourseOrdem);

        if(!is_null($mdlFase))
            $dependencias = $this->MdlFase->MdlCourseMdlFase->find()->where(['mdl_fase_id' => $mdlFase->id]);

        $ecmProdutoEcmProduto = $this->MdlFase->EcmProduto->EcmProdutoEcmProduto->find('all', [
            'fields' => ['ecm_produto_relacionamento_id']
        ])->where(['ecm_produto_id' => $id])->toArray();

        $this->set(compact('mdlFase', 'ecmProduto', 'mdlCourse', 'mdlCourseOrdem', 'dependencias', 'ecmProdutoEcmProduto'));
        $this->set('_serialize', ['mdlFase']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mdl Course Mdl Fase id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($mdl_fase_id = null, $mdl_course_id = null, $mdl_course_conclusion_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mdlFase = $this->MdlFase->find()->where(['mdl_fase_id' => $mdl_fase_id,
            'mdl_course_id' => $mdl_course_id, 'mdl_course_conclusion_id' => $mdl_course_conclusion_id]);
        /*$fasesDeletadas = true;
        if(is_array($mdlFase)){
            foreach($mdlFase as $fase){
                if($this->MdlFase->delete($fase))
                    $fasesDeletadas = false;
            }
        } else if(!empty($mdlFase))*/
        $fasesDeletadas = $this->MdlFase->delete($mdlFase);

        if ($fasesDeletadas)
            $this->Flash->success(__('A dependencia foi deletada.'));
        else
            $this->Flash->error(__('Não foi possivel deletar a dependencia.'));
    }
}
