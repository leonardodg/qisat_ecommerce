<?php
namespace Convenio\Controller;

use App\Auth\AESPasswordHasher;
use Cake\Datasource\Exception\RecordNotFoundException;
use Convenio\Controller\AppController;

/**
 * EcmConvenio Controller
 *
 * @property \Convenio\Model\Table\EcmConvenioTable $EcmConvenio */
class EcmConvenioController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->helpers = array('WebService.Cidade');

        $condition = [];
        $listaCidade = null;
        $cidadeDefault = null;

        if(!empty($this->request->query)){
            $nome = $this->request->query('nome');
            $situacao = $this->request->query('situacao');
            $cidade = $this->request->query('cidade');


            if (strlen(trim($nome)) > 0) {
                $condition['EcmConvenio.nome_instituicao LIKE'] = '%'.$nome.'%';
            }

            if(strlen(trim($situacao)) > 0){
                if($situacao == 1){
                    $condition['EcmConvenioContrato.contrato_ativo'] = 'true';
                    $condition['EcmConvenioContrato.contrato_assinado'] = 'true';
                }else{
                    $condition['OR'] = ['EcmConvenioContrato.contrato_ativo' => 'false', 'EcmConvenioContrato.contrato_assinado' => 'false'];
                }
            }

            if(strlen(trim($cidade)) > 0){
                $cidadeDefault = $this->EcmConvenio->MdlCidade->get($cidade);

                if($cidadeDefault) {
                    $condition['EcmConvenio.mdl_cidade_id'] = $cidade;
                    $listaCidade = $this->EcmConvenio->MdlCidade->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
                        ->where(['MdlCidade.uf' => $cidadeDefault->get('uf')])
                        ->orderAsc('nome');
                }
            }

            $this->request->data = $this->request->query;
        }

        $this->paginate = [
            'contain' => ['EcmConvenioTipoInstituicao', 'EcmConvenioContrato', 'MdlCidade'],
            'conditions' => $condition,
            'order' => ['EcmConvenio.data_registro' => 'desc']
        ];
        $ecmConvenio = $this->paginate($this->EcmConvenio);

        $convenio = $this->EcmConvenio->newEntity();
        $listaEstado = $this->EcmConvenio->MdlCidade->MdlEstado->find('list', ['keyField' => 'id', 'valueField' => 'nome']);

        $this->loadModel('Publicidade.EcmPublicidade');

        $this->set(compact('ecmConvenio', 'convenio', 'listaEstado', 'listaCidade', 'cidadeDefault'));
        $this->set('_serialize', ['ecmConvenio']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $this->helpers = array('WebService.Cidade');
        $ecmConvenio = $this->EcmConvenio->newEntity();
        if ($this->request->is('post')) {
            $ecmConvenio = $this->EcmConvenio->patchEntity($ecmConvenio, $this->request->data);

            if ($this->EcmConvenio->save($ecmConvenio)) {
                $this->Flash->success(__('Convênio registrado com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o convênio. Por favor tente novamente!'));
            }
        }
        $ecmConvenioTipoInstituicao = $this->EcmConvenio->EcmConvenioTipoInstituicao->find('list', ['keyField' => 'id', 'valueField' => 'descricao']);
        $mdlEstado = $this->EcmConvenio->MdlCidade->MdlEstado->find('list', ['keyField' => 'id', 'valueField' => 'nome']);
        $this->set(compact('ecmConvenio', 'ecmConvenioTipoInstituicao', 'mdlEstado'));
        $this->set('_serialize', ['ecmConvenio']);
        $this->set('titulo',__('Novo Convênio'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ecm Convenio id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $this->helpers = array('WebService.Cidade');
        $ecmConvenio = $this->EcmConvenio->get($id, [
            'contain' => ['MdlCidade' => ['MdlEstado']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ecmConvenio = $this->EcmConvenio->patchEntity($ecmConvenio, $this->request->data);
            if ($this->EcmConvenio->save($ecmConvenio)) {
                $this->Flash->success(__('Convênio alterado com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar o convênio. Por favor tente novamente!'));
            }
        }
        
        if(!is_null($ecmConvenio->get('mdl_cidade'))) {
            $listaCidadesEstado = $this->EcmConvenio->MdlCidade->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
                                  ->where(['uf' => $ecmConvenio->get('mdl_cidade')->get('uf')]);
        }

        $ecmConvenioTipoInstituicao = $this->EcmConvenio->EcmConvenioTipoInstituicao->find('list', ['keyField' => 'id', 'valueField' => 'descricao']);

        $mdlEstado = $this->EcmConvenio->MdlCidade->MdlEstado->find('list', ['keyField' => 'id', 'valueField' => 'nome']);
        $this->set(compact('ecmConvenio', 'ecmConvenioTipoInstituicao', 'mdlEstado', 'listaCidadesEstado'));
        $this->set('_serialize', ['ecmConvenio']);
        $this->set('titulo',__('Editar Convênio'));
        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ecm Convenio id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ecmConvenio = $this->EcmConvenio->get($id);
        if ($this->EcmConvenio->delete($ecmConvenio)) {
            $this->Flash->success(__('Convênio excluído com sucesso'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluír o convênio!'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Gerar Contrato method
     *
     * @param string|null $id Ecm Convenio id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function gerarContrato($id = null)
    {
        $convenio = $this->EcmConvenio->get($id, [
            'contain' => ['EcmConvenioTipoInstituicao']
        ]);

        $this->set('convenio', $convenio);
        $this->set('_serialize', ['convenio']);

        $this->render()->type('application/pdf');
    }

    public function listaInteresse($idConvenio = null)
    {
        $convenio = null;

        try {
            $convenio = $this->EcmConvenio->get($idConvenio);
        }catch(RecordNotFoundException $e){}

        if(!is_null($convenio)) {
            $this->loadModel('Convenio.EcmConvenioInteresse');

            $this->paginate = [
                'contain' => ['EcmConvenio'],
                'conditions' => ['EcmConvenio.id' => $idConvenio],
                'order' => [
                    'EcmConvenioInteresse.data_registro' => 'DESC'
                ]
            ];
            $ecmConvenioInteresse = $this->paginate($this->EcmConvenioInteresse);

            $this->set(compact('ecmConvenioInteresse', 'convenio'));
            $this->set('_serialize', ['ecmConvenioInteresse', 'convenio']);
        }else{
            $this->Flash->error(__('Convênio não encontrado!'));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Gerar Contrato
     *
     * @param string $id criptografado de EcmConvenio id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function contrato($id = null)
    {
        $aesHash = new AESPasswordHasher();
        $id = $aesHash->decrypt(base64_decode($id));

        $convenio = $this->EcmConvenio->get($id, [
            'contain' => ['EcmConvenioTipoInstituicao']
        ]);

        $this->set('convenio', $convenio);
        $this->set('_serialize', ['convenio']);


        $this->render('gerar-contrato')->type('application/pdf');

    }
}
