<?php
namespace Entidade\Controller;

use Entidade\Controller\AppController;

/**
 * MdlShopUser Controller
 *
 * @property \Entidade\Model\Table\MdlShopUserTable $MdlShopUser */
class MdlShopUserController extends AppController
{

    public $helpers = ['JqueryMask'];
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('MdlUser');
        $this->loadModel('EcmAlternativeHost');

        $wherequeryUser = [];
        $where = ['MdlShopUser.chave_altoqi is null'];
        $registrado = null;
        if(!empty($this->request->query)){
            $nome = $this->request->query('nome');
            $cpfCnpj = $this->request->query('cpf');
            $crea = $this->request->query('numero');
            $adimplente = $this->request->query('adimplente');
            $confirmado = $this->request->query('confirmado');
            $entidade = $this->request->query('entidade');
            $registrado = $this->request->query('registrado');

            if(!is_null($nome) && trim($nome) != ''){
                $where['MdlShopUser.nome LIKE'] = '%'.$nome.'%';
                $wherequeryUser['concat(MdlUser.firstname, " ", MdlUser.lastname) LIKE'] = '%'.$nome.'%';
            }

            if(!is_null($cpfCnpj) && trim($cpfCnpj) != ''){
                $cpfCnpj = preg_replace("/[^0-9]/", "", $cpfCnpj);
                $where['MdlShopUser.cpf_cnpj'] = $cpfCnpj;
                $wherequeryUser['MdlUserDados.numero'] = $cpfCnpj;
            }

            if(!is_null($crea) && trim($crea) != ''){
                $where['MdlShopUser.crea'] = $crea;
                $wherequeryUser['MdlUserEcmAlternativeHost.numero'] = $crea;
            }

            if(!is_null($adimplente) && trim($adimplente) != '' &&
                ($adimplente == 1 || $adimplente == 0)){
                $where['MdlShopUser.adimplente'] = $adimplente;
                $wherequeryUser['MdlUserEcmAlternativeHost.adimplente'] = $adimplente;
            }

            if(!is_null($confirmado) && trim($confirmado) != '' &&
                ($confirmado == 1 || $confirmado == 0)){
                $where['MdlShopUser.confirmado'] = $confirmado;
                $wherequeryUser['MdlUserEcmAlternativeHost.confirmado'] = $confirmado;
            }

            if(!is_null($entidade) && trim($entidade) != ''){
                $where['EcmAlternativeHost.id'] = $entidade;
                $wherequeryUser['EcmAlternativeHost.id'] = $entidade;
            }

            if(!is_null($registrado) && trim($registrado) != ''){
                $registrado = $registrado == 1? true:false;
            }else{
                $registrado = null;
            }

            $this->request->data = $this->request->query;
        }

        $queryUser = $this->MdlUser
            ->find()
            ->contain(['MdlUserDados'])
            ->select([
                'MdlUser.id', 'nome' => 'concat(MdlUser.firstname, " ", MdlUser.lastname)',
                'MdlUserEcmAlternativeHost.numero','chave_altoqi' => 'MdlUser.idnumber',
                'EcmAlternativeHost.id', 'EcmAlternativeHost.shortname',
                'MdlUserEcmAlternativeHost.adimplente', 'MdlUserEcmAlternativeHost.confirmado',
                'cpf' => 'MdlUserDados.numero'
            ])
        ->innerJoinWith(
            'MdlUserEcmAlternativeHost', function ($q) {
                return $q->innerJoinWith(
                    'EcmAlternativeHost'
                );
            }
        )->where($wherequeryUser);

        $query = $this->MdlShopUser
            ->find()
            ->select([
                'MdlShopUser.id', 'MdlShopUser.nome', 'numero' => 'MdlShopUser.crea',
                'MdlShopUser.chave_altoqi', 'EcmAlternativeHost.id',
                'EcmAlternativeHost.shortname', 'MdlShopUser.adimplente',
                'MdlShopUser.confirmado', 'cpf' => 'MdlShopUser.cpf_cnpj'
            ])->where($where);

        if(is_null($registrado)){
            $query->unionAll($queryUser);
        }

        if(!is_null($this->request->query('sort'))){
            if($this->request->query('direction') == 'asc'){
                $query->orderAsc($this->request->query('sort'));
            }else{
                $query->orderDesc($this->request->query('sort'));
            }
        }else {
            $query->orderAsc('nome');
        }

        $listaUsuarios = null;
        if($registrado == true) {
            $listaUsuarios = $this->paginate($queryUser);
        }else{
            $this->paginate = [
                'contain' => ['EcmAlternativeHost'],
                'sortWhitelist'=>['EcmAlternativeHost.shortname']
            ];

            $listaUsuarios = $this->paginate($query);
        }

        $optionsEntidade = $this->EcmAlternativeHost
            ->find('list', ['keyField' => 'id', 'valueField' => 'shortname'])
            ->where(['id <> 1']);

        $this->set(compact('listaUsuarios', 'optionsEntidade'));
        $this->set('_serialize', ['listaUsuarios']);
    }
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mdlShopUser = $this->MdlShopUser->newEntity();
        if ($this->request->is('post')) {
            $mdlShopUser = $this->MdlShopUser->patchEntity($mdlShopUser, $this->request->data);
            if ($this->MdlShopUser->save($mdlShopUser)) {
                $this->Flash->success(__('Registro salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar. Por favor tente novamente.'));
            }
        }
        $ecmAlternativeHost = $this->MdlShopUser->EcmAlternativeHost->find('list', ['keyField' => 'id', 'valueField' => 'shortname']);
        $this->set('titulo', __('Cadastrar usuário de entidade'));
        $this->set(compact('mdlShopUser', 'ecmAlternativeHost'));
        $this->set('_serialize', ['mdlShopUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Mdl Shop User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mdlShopUser = $this->MdlShopUser->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mdlShopUser = $this->MdlShopUser->patchEntity($mdlShopUser, $this->request->data);
            if ($this->MdlShopUser->save($mdlShopUser)) {
                $this->Flash->success(__('Registro salvo com sucesso'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ocorreu um erro ao salvar. Por favor tente novamente.'));
            }
        }
        $ecmAlternativeHost = $this->MdlShopUser->EcmAlternativeHost->find('list', ['keyField' => 'id', 'valueField' => 'shortname']);
        $this->set('titulo', __('Editar usuário de entidade'));
        $this->set(compact('mdlShopUser', 'ecmAlternativeHost'));
        $this->set('_serialize', ['mdlShopUser']);
        $this->render('add');
    }

    public function alterarStatus($id = null, $entidade = null, $status = null){
        if(!is_null($id) && !is_null($entidade) && !is_null($status)
            && ($status == 'adimplente' || $status == 'confirmado')){

            $this->loadModel('MdlUserEcmAlternativeHost');

            $usuarioEntidade = $this->MdlUserEcmAlternativeHost
                ->find()
                ->where(['mdl_user_id' => $id, 'ecm_alternative_host_id' => $entidade])->first();

            $model = $this->MdlUserEcmAlternativeHost;

            if(is_null($usuarioEntidade)){
                $usuarioEntidade = $this->MdlShopUser->get($id);
                $model = $this->MdlShopUser;
            }

            $adimplente = $usuarioEntidade->get($status) == 1? 0 : 1;
            $usuarioEntidade->set($status, $adimplente);

            if($model->save($usuarioEntidade))
                $this->Flash->success(__('Registro alterado com sucesso'));
            else
                $this->Flash->error(__('Ocorreu um erro ao alterar o registro'));
        }else{
            $this->Flash->error(__('Parâmentros não informados'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
