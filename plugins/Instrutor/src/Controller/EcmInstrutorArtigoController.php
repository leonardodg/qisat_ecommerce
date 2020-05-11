<?php
namespace Instrutor\Controller;

use Instrutor\Controller\AppController;

require_once ROOT . DS . 'Vendor' . DS . 'simplehtmldom' . DS . 'simple_html_dom.php';

/**
 * EcmInstrutorArtigo Controller
 *
 * @property \Instrutor\Model\Table\EcmInstrutorArtigoTable $EcmInstrutorArtigo */
class EcmInstrutorArtigoController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers = array('JqueryMask');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id = null)
    {
        if(!is_null($id)){
            $existsInstrutor = $this->EcmInstrutorArtigo->EcmInstrutor->exists(['EcmInstrutor.id' => $id]);

            if($existsInstrutor) {
                $ecmInstrutor = $this->EcmInstrutorArtigo->EcmInstrutor->get($id, ['contain' => ['MdlUser']]);

                $conditions = ['EcmInstrutorArtigo.ecm_instrutor_id' => $id];
                if(count($this->request->query)){
                    if(isset($this->request->query['titulo']) && !empty($this->request->query['titulo'])){
                        array_push($conditions, 'EcmInstrutorArtigo.titulo LIKE "%'.$this->request->query['titulo'].'%"');
                    }
                }

                $this->paginate = [
                    'contain' => ['EcmInstrutor' => ['MdlUser']],
                    'conditions' => $conditions
                ];

                $ecmInstrutorArtigo = $this->paginate($this->EcmInstrutorArtigo);

                if($this->request->is('get'))
                    $this->request->data = $this->request->query;

                $this->set(compact('ecmInstrutorArtigo','ecmInstrutor'));
                $this->set('_serialize', ['ecmInstrutorArtigo']);
            }else{
                $this->Flash->error(__('Instrutor não encontrado!'));
                return $this->redirect(['controller' => '','action' => 'index']);
            }
        }else{
            $this->Flash->error(__('Instrutor não encontrado!'));
            return $this->redirect(['controller' => '','action' => 'index']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Instrutor Artigo id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->get($id, [
            'contain' => ['EcmInstrutor'=>['MdlUser', 'EcmImagem']]
        ]);

        $this->set('ecmInstrutorArtigo', $ecmInstrutorArtigo);
        $this->set('_serialize', ['ecmInstrutorArtigo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        if(!is_null($id)){

            $existsInstrutor = $this->EcmInstrutorArtigo->EcmInstrutor->exists(['EcmInstrutor.id'=>$id]);

            if($existsInstrutor) {
                $ecmInstrutor = $this->EcmInstrutorArtigo->EcmInstrutor->get($id,['contain' => ['MdlUser']]);

                $this->request->data['ecm_instrutor_id'] = $ecmInstrutor->id;

                $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->newEntity();
                if ($this->request->is('post')) {
                    $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->patchEntity($ecmInstrutorArtigo, $this->request->data);
                    if ($this->EcmInstrutorArtigo->save($ecmInstrutorArtigo)) {
                        $this->Flash->success(__('Artigo salvo com suceso'));
                        return $this->redirect(['controller' => 'artigo', 'action' => 'index', $ecmInstrutor->id]);
                    } else {
                        $this->Flash->error(__('Ocorreu um erro ao salvar o artigo!'));
                    }
                }
                $this->set(compact('ecmInstrutorArtigo', 'ecmInstrutor', 'ecmInstrutor'));
                $this->set('_serialize', ['ecmInstrutorArtigo']);
                $this->set('titulo', __('Cadastrar artigo para o instrutor').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname);
            }else{
                $this->Flash->error(__('Instrutor não encontrado!'));
                return $this->redirect(['controller' => '','action' => 'index']);
            }
        }else{
            $this->Flash->error(__('Instrutor não encontrado!'));
            return $this->redirect(['controller' => '','action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Instrutor Artigo id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->get($id, [
            'contain' => ['EcmInstrutor' => ['MdlUser']]
        ]);

        $ecmInstrutor = $ecmInstrutorArtigo->ecm_instrutor;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->patchEntity($ecmInstrutorArtigo, $this->request->data);
            if ($this->EcmInstrutorArtigo->save($ecmInstrutorArtigo)) {
                $this->Flash->success(__('Artigo salvo com suceso'));
                return $this->redirect(['controller' => 'artigo', 'action' => 'index', $ecmInstrutor->id]);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o artigo!'));
            }
        }else{
            if(!is_null($ecmInstrutorArtigo->get('data_publicacao')))
                $ecmInstrutorArtigo->set('data_publicacao', $ecmInstrutorArtigo->get('data_publicacao')->format('d/m/Y H:i'));

            if(!is_null($ecmInstrutorArtigo->get('data_modificacao')))
                $ecmInstrutorArtigo->set('data_modificacao', $ecmInstrutorArtigo->get('data_modificacao')->format('d/m/Y H:i'));
        }
        $this->set(compact('ecmInstrutorArtigo', 'ecmInstrutor'));
        $this->set('_serialize', ['ecmInstrutorArtigo']);
        $this->set('titulo', __('Editar artigo do instrutor').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname);
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Instrutor Artigo id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmInstrutorArtigo = $this->EcmInstrutorArtigo->get($id,['contain' => ['EcmInstrutor']]);
        $ecmInstrutor = $ecmInstrutorArtigo->ecm_instrutor;

        if ($this->EcmInstrutorArtigo->delete($ecmInstrutorArtigo)) {
            $this->Flash->success(__('Artigo excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir o artigo!'));
        }
        return $this->redirect(['controller' => 'artigo', 'action' => 'index', $ecmInstrutor->id]);
    }

    public function buscaInformacoesArtigo(){
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        $url = $this->request->data('url');

        $html = $this->solicitarDados($url);

        $data = new \stdClass();
        $data->descricao = $this->getElemento($html, 'meta[property=og:description]', 'content');
        $data->titulo = $this->getElemento($html, 'meta[property=og:title]', 'content');
        $data->imagem = $this->getElemento($html, 'meta[property=og:image]', 'content');
        $data->url = $this->getElemento($html, 'meta[property=og:url]', 'content');
        $data->tag = $this->getElemento($html, 'meta[property=article:tag]', 'content');
        $data->data_publicacao = $this->getElemento($html, 'meta[property=article:published_time]', 'content');
        $data->data_modificacao = $this->getElemento($html, 'meta[property=article:modified_time]', 'content');

        if(!empty($data->data_publicacao)){
            $dataPublicacao = new \DateTime($data->data_publicacao);
            $data->data_publicacao = $dataPublicacao->format('d/m/Y H:i:s');
        }

        if(!empty($data->data_modificacao)){
            $dataModificacao = new \DateTime($data->data_modificacao);
            $data->data_modificacao = $dataModificacao->format('d/m/Y H:i:s');
        }

        $this->set(compact('data'));
    }

    private function solicitarDados($url){
        $options = array("http" =>
            array(
                "method" => "GET",
                "header" => array("Content-Type: application/x-www-form-urlencoded")
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return str_get_html($response);
    }

    private function getElemento($html, $elemento, $atributo){
        $objeto = $html->find($elemento, 0);
        if(isset($objeto->{$atributo}))
            return $objeto->{$atributo};

        return '';
    }
}
