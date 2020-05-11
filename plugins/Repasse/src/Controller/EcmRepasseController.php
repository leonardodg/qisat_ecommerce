<?php
namespace Repasse\Controller;

use App\Model\Entity\MdlUser;
use Cake\Network\Http\Client;
use Cake\Validation\Validator;
use Repasse\Model\Entity\EcmRepasse;
use WebService\Util\WscAltoQi;

/**
 * EcmRepasse Controller
 *
 * @property \Repasse\Model\Table\EcmRepasseTable $EcmRepasse */
class EcmRepasseController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $condition = [];

        $repasse = $this->EcmRepasse->newEntity();

        if($this->request->is('ajax'))
            $this->viewBuilder()->layout('ajax');

        if($this->request->is('get')) {
            $condition['status IN'] = [EcmRepasse::STATUS_EM_ATENDIMENTO, EcmRepasse::STATUS_NAO_ATENDIDO];
        }

        if($this->request->is('post')){

            $validator = $this->validarDados();
            $errors = $validator->errors($this->request->data());

            if(empty($errors)) {
                $status                    = $this->request->data('status_pesquisa');
                $responsavel               = $this->request->data('responsavel');
                $dataInicio                = $this->request->data('data_inicio');
                $dataFim                   = $this->request->data('data_fim');
                $ecm_alternative_host_id   = $this->request->data('ecm_alternative_host_id');
                $ecm_repasse_categorias_id = $this->request->data('ecm_repasse_categorias_id');
                $ecm_repasse_origem_id     = $this->request->data('ecm_repasse_origem_id');

                if ($status == '1') {
                    $condition['status IN'] = [EcmRepasse::STATUS_EM_ATENDIMENTO, EcmRepasse::STATUS_NAO_ATENDIDO];
                } elseif ($status != '0') {
                    $condition['status'] = $status;
                }

                if (strlen(trim($responsavel)) > 0)
                    $condition['mdl_user_id'] = $responsavel;

                if (strlen(trim($dataInicio)) == 10) {
                    $dataInicio = \DateTime::createFromFormat('d/m/Y', $dataInicio);
                    $dataInicio->setTime(0, 0, 0);

                    $condition['data_registro >='] = $dataInicio->format('Y-m-d H:i:s');
                }

                if (strlen(trim($dataFim)) == 10) {
                    $dataFim = \DateTime::createFromFormat('d/m/Y', $dataFim);
                    $dataFim->setTime(23, 59, 59);

                    $condition['data_registro <='] = $dataFim->format('Y-m-d H:i:s');
                }

                if($ecm_alternative_host_id != 0)
                    $condition['ecm_alternative_host_id'] = $ecm_alternative_host_id;

                if($ecm_repasse_categorias_id != 0)
                    $condition['ecm_repasse_categorias_id'] = $ecm_repasse_categorias_id;

                if($ecm_repasse_origem_id != 0)
                    $condition['ecm_repasse_origem_id'] = $ecm_repasse_origem_id;

            }else{
                $repasse->errors($errors);
            }
        }

        $permissoes = $this->Auth->user('permissoes');
        $permissaoAlterarStatus = MdlUser::verificarPermissao('alterarStatus', $this->request->controller,
            $this->request->plugin, $permissoes);
        $permissaoAlterarResponsavel = MdlUser::verificarPermissao('atribuirResponsavel', $this->request->controller,
            $this->request->plugin, $permissoes);

        $this->paginate = [
            'contain' => ['MdlUser', 'EcmAlternativeHost', 'EcmRepasseCategorias', 'EcmRepasseOrigem', 'MdlUserCliente'],
            'conditions' => $condition,
            'order' => [ 'id' => 'DESC' ]
        ];
        $ecmRepasse = $this->paginate($this->EcmRepasse);

        $listaFuncionarios = $this->buscaListaFuncionarios();

        $repasseEmAtendimento = $this->verificaRepasseEmAtendimento($this->Auth->user('id'));

        $idUsuario = $this->Auth->user('id');

        $ecmAlternativeHost = $this->EcmRepasse->EcmAlternativeHost->find('list', [
            'keyField' => 'id', 'valueField' => 'shortname'
        ])->where(['OR' => [['shortname'=>'QiSat'], ['shortname'=>'AltoQi']]])->toArray();
        $ecmAlternativeHost[0] = 'Todos';
        ksort($ecmAlternativeHost);

        $ecmRepasseCategorias = $this->EcmRepasse->EcmRepasseCategorias->find('list', [
            'keyField' => 'id', 'valueField' => 'categoria'
        ])->toArray();
        $ecmRepasseCategorias[0] = 'Todos';
        ksort($ecmRepasseCategorias);

        $ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find('list', [
            'keyField' => 'id', 'valueField' => 'origem'
        ])->toArray();
        $ecmRepasseOrigem[0] = 'Todos';
        ksort($ecmRepasseOrigem);

        $this->set(compact('ecmRepasse', 'repasse', 'listaFuncionarios',
            'permissaoAlterarStatus', 'permissaoAlterarResponsavel', 'repasseEmAtendimento', 'idUsuario',
            'ecmAlternativeHost', 'ecmRepasseCategorias', 'ecmRepasseOrigem'));
        $this->set('_serialize', ['ecmRepasse']);

    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ecmRepasse = $this->EcmRepasse->newEntity();

        if ($this->request->is('post')) {
            $ecmRepasse = $this->EcmRepasse->patchEntity($ecmRepasse, $this->request->data);
            $ecmRepasse->data_registro = new \DateTime();
            $ecmRepasse->status = "Não Atendido";

            if ((array_key_exists('mdl_user_cliente_id', $this->request->data) ||
                    !empty($this->request->data['observacao'])) && $this->EcmRepasse->save($ecmRepasse)) {
                $this->Flash->success(__('O repasse foi salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possivel salvar o repasse. Por favor, tente novamente.'));
            }
        }

        $ecmRepasseCategorias = $this->EcmRepasse->EcmRepasseCategorias->find('list', [
            'keyField' => 'id', 'valueField' => 'categoria'
        ])->where(['visivel' => 1])->toArray();

        $ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find('list', [
            'keyField' => 'id', 'valueField' => 'origem'
        ])->where(['visivel' => 1])->toArray();

        $equipe = ['QiSat'=>'QiSat','Pré 1'=>'Pré 1','Pré 2'=>'Pré 2','Pré 3'=>'Pré 3'];

        $ecmAlternativeHost = $this->EcmRepasse->EcmAlternativeHost->find('list', [
            'keyField' => 'id', 'valueField' => 'shortname'
        ])->where(['OR' => [['shortname'=>'QiSat'],['shortname'=>'AltoQi']]]);

        $this->set(compact('ecmRepasse', 'ecmRepasseCategorias', 'ecmRepasseOrigem', 'equipe',
            'ecmAlternativeHost'));
        $this->set('_serialize', ['ecmRepasse']);
    }


    private function buscaListaFuncionarios(){

        $this->loadModel('MdlUser');

        $listaFuncionarios = $this->MdlUser->find('list',
            ['keyField' => 'id', 'valueField' => function ($e) {
                                                    return strtoupper($e->get('firstname')).' '.strtoupper($e->get('lastname'));
                                                }, 
                                    'groupField' => function ($e) {
                                                    return ($e->get('Empresa'))==1 ? 'QiSat' : 'AltoQi';
                                                }

            ])
            ->contain(['MdlUserDados' => ['fields' => [ 'Empresa' => 'MdlUserDados.ecm_alternative_host_id'],
                                            'queryBuilder' => function ($q) {
                                                return $q->where( ['MdlUserDados.ecm_alternative_host_id IS NOT NULL']);
                                            }
                                         ]
                        ])
            ->where(['MdlUserDados.funcionarioqisat' => 1])
            ->order(['MdlUserDados.ecm_alternative_host_id' => 'ASC', 'firstname' => 'ASC', 'lastname' => 'ASC']);

        return $listaFuncionarios->toArray();
    }

    private function validarDados(){
        $validator = new Validator();

        $validator
            ->date('data_inicio',['dmy'])->requirePresence('data_inicio', 'create')->allowEmpty('data_inicio');
        $validator
            ->date('data_fim',['dmy'])->requirePresence('data_fim', 'create')->allowEmpty('data_fim');

        return $validator;
    }

    public function alterarStatus(){
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        $id = $this->request->data('id');
        $status = $this->request->data('status');

        $retorno = ['sucesso' => false, 'mensagem' => __('Repasse não encontrado!')];

        if(!is_null($id) && $this->request->is('post')){
            $listaStatus = ['Em Atendimento', 'Finalizado', 'Não Atendido'];

            if(in_array($status, $listaStatus)){
                $repasse = $this->EcmRepasse->get($id);
                $repasse->set('status', $status);

                if($this->EcmRepasse->save($repasse)){
                    $retorno = ['sucesso' => true, 'mensagem' => __('Status do repasse alterado com sucesso')];
                }else{
                    $retorno['mensagem'] =__('Ocorreu um erro ao salvar o repasse!');
                }
            }else{
                $retorno['mensagem'] =__('Repasse não encontrado!');
            }
        }

        $this->set(compact('retorno'));
    }


    public function alterarEquipe(){
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        $id = $this->request->data('id');
        $equipe = $this->request->data('equipe');

        $retorno = ['sucesso' => false, 'mensagem' => __('Repasse não encontrado!')];

        if(!is_null($id) && $this->request->is('post')){
            $listaEquipe = ['QiSat', 'Pré 1','Pré 2','Pré 3'];

            if(in_array($equipe, $listaEquipe)){
                $repasse = $this->EcmRepasse->get($id);
                $repasse->set('equipe', $equipe);

                if($this->EcmRepasse->save($repasse)){
                    $retorno = ['sucesso' => true, 'mensagem' => __('Equipe do repasse alterado com sucesso')];
                }else{
                    $retorno['mensagem'] =__('Ocorreu um erro ao salvar o repasse!');
                }
            }else{
                $retorno['mensagem'] =__('Repasse não encontrado!');
            }
        }

        $this->set(compact('retorno'));
    }

    public function alterarDados(){
        $this->loadModel('MdlUser');

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        $id = $this->request->data('id');
        $userid = $this->request->data('userid');
        $obs = $this->request->data('obs');

        $retorno = ['sucesso' => false, 'mensagem' => __('Repasse não encontrado!')];

        if(!is_null($id) && $this->request->is('post')){

                $repasse = $this->EcmRepasse->get($id);
                if(!empty($obs)){
                    $repasse->set('observacao', $obs);
                }

                if(!empty($userid) && !is_null($userid)){
                    if($clientId = $this->MdlUser->get($userid)){
                        $clientId = $clientId->get('id');
                        $repasse->set('mdl_user_cliente_id', $clientId);
                    }
                }

                if($this->EcmRepasse->save($repasse)){
                    $retorno = ['sucesso' => true, 'mensagem' => __('Dados do repasse alterado com sucesso')];
                }else{
                    $retorno['mensagem'] =__('Ocorreu um erro ao salvar o repasse!');
                }
        }

        $this->set(compact('retorno'));
    }

    public function alterarResponsavel(){
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        $id = $this->request->data('id');
        $idResponsavel = $this->request->data('funcionario');

        $retorno = ['sucesso' => false, 'mensagem' => __('Repasse não encontrado!')];

        $permissoes = $this->Auth->user('permissoes');
        $permissaoAtribuirResponsavel = MdlUser::verificarPermissao('atribuirResponsavel', $this->request->controller,
            $this->request->plugin, $permissoes);

        $alterarStatus = false;
        if(!$permissaoAtribuirResponsavel || $idResponsavel == 'atribuir-logado'){
            $idResponsavel = $this->Auth->user('id');
            $alterarStatus = true;

            $repasseEmAtendimento = $this->verificaRepasseEmAtendimento($this->Auth->user('id'));
            if(!$permissaoAtribuirResponsavel && $repasseEmAtendimento){
                $retorno['mensagem'] =__('Você já está responsável por um repasse!');
                $this->set(compact('retorno'));
                return;
            }
        }

        if(!is_null($id) && $this->request->is('post')){
            $this->loadModel('MdlUser');

            $repasse = null;
            $responsavel = null;

            try {
                $repasse = $this->EcmRepasse->get($id);

                if(is_numeric($idResponsavel)) {
                    $responsavel = $this->MdlUser->get($idResponsavel);
                    $responsavel = $responsavel->get('id');
                }
            }catch(RecordNotFoundException $e){}

            if(!is_null($repasse)){
                $repasse->set('mdl_user_id', $responsavel);
                $repasse->set('mdl_usermodified_id', $this->Auth->user('id'));

                if($alterarStatus){
                    $repasse->set('status', EcmRepasse::STATUS_EM_ATENDIMENTO);
                }

                if($this->EcmRepasse->save($repasse)){
                    $retorno = ['sucesso' => true, 'mensagem' => __('Responsável pelo repasse alterado com sucesso')];
                }else{
                    $retorno['mensagem'] =__('Ocorreu um erro ao salvar o repasse!');
                }
            }else{
                $retorno['mensagem'] =__('Repasse não encontrado!');
            }
        }

        $this->set(compact('retorno'));
    }

    private function verificaRepasseEmAtendimento($idUsuario){
        $repasse = $this->EcmRepasse->find('all', ['fields' => 'id'])
            ->where([
                'status <>' => EcmRepasse::STATUS_FINALIZADO,
                'mdl_user_id' => $idUsuario
            ])->count();

        return $repasse > 0;
    }
}
