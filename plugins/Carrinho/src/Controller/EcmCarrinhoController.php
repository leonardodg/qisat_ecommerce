<?php
namespace Carrinho\Controller;

use App\Model\Entity\MdlUser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Utility\Security;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmVendaStatus;
use Firebase\JWT\JWT;
use WebService\Util\WscAltoQi;

/**
 * EcmCarrinho Controller
 *
 * @property \Carrinho\Model\Table\EcmCarrinhoTable $EcmCarrinho */
class EcmCarrinhoController extends CarrinhoController
{

    public function beforeFilter(Event $event)
    {
        if($this->request->action != 'index' && $this->request->action != 'visualizarCarrinhos' &&
                $this->request->action != 'view' && $this->request->action != 'agendamento') {
            $verificaUsuario = $this->verificaUsuarioSelecionado();
            if ($verificaUsuario === true)
                $verificaUsuario;
        }

        return parent::beforeFilter($event);
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($idUsuario = 0) {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = $this->request->data['entidade'];

            $ecmAlternativeHost = $this->EcmCarrinho->MdlUser->MdlUserEcmAlternativeHost
                ->find()->where(['mdl_user_id' => $idUsuario, 'ecm_alternative_host_id' => $id])->first();

            $this->loadModel('Configuracao.EcmConfig');
            $ecmConfig = $this->EcmConfig->find('list', ['keyField' => 'nome', 'valueField' => 'valor'])
                ->where(['nome LIKE "vender_crea_%"'])->toArray();

            $adimplente = 0;
            $confirmado = 0;
            if ((isset($ecmConfig['vender_crea_inadimplente']) && $ecmConfig['vender_crea_inadimplente'] == 1) ||
                (isset($ecmAlternativeHost->adimplente) && $ecmAlternativeHost->get('adimplente') == 1)){
                    $adimplente = 1;
            }
            if ((isset($ecmConfig['vender_crea_nao_confirmado']) && $ecmConfig['vender_crea_nao_confirmado'] == 1) ||
                (isset($ecmAlternativeHost->confirmado) && $ecmAlternativeHost->get('confirmado') == 1)){
                    $confirmado = 1;
            }
            if (!$adimplente) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'O usuario encontra-se inadimplemente neste CREA.']);
            } else if (!$confirmado) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'O usuario não foi confirmado neste CREA.']);
            } else {
                echo json_encode(['sucesso' => true]);
            }
        } else {
            $ecmAlternativeHost = null;
            if($this->EcmCarrinho->MdlUser->exists(['id' => $idUsuario])) {

                $usuario = $this->EcmCarrinho->MdlUser->get($idUsuario,
                    ['contain' => ['MdlUserEcmAlternativeHost' => ['EcmAlternativeHost']],
                        'fields' => ['id', 'idnumber', 'firstname', 'lastname', 'email', 'username']]);

                $ecmAlternativeHost = $this->EcmCarrinho->MdlUser->MdlUserEcmAlternativeHost
                    ->EcmAlternativeHost->find('list', ['keyField' => 'id', 'valueField' => 'fullname'])
                    ->where(['OR' => [
                        ['EcmAlternativeHost.shortname' => 'QiSat'],
                        ['EcmAlternativeHost.shortname' => 'AltoQi']
                    ]])->toArray();

                foreach($usuario->mdl_user_ecm_alternative_host as $mdlUserEcmAlternativeHost){
                    $host = $mdlUserEcmAlternativeHost->ecm_alternative_host;
                    $ecmAlternativeHost[$host->id] = $host->fullname;
                }
            }else{
                $this->Flash->error(__('Usuário não encontrado!'));
                return $this->redirect(['plugin' => false, 'controller' => 'usuario','action' => 'listar-usuario']);
            }

            if ($this->request->is('post')) {
                if(!is_null($this->request->data) && array_key_exists('edit', $this->request->data)) {
                    $edit = $this->request->data['edit'];
                    $ecmCarrinho = $this->EcmCarrinho->get($edit, ['contain' => [
                        'MdlUser' => [
                            'MdlUserEcmAlternativeHost' => [
                                'EcmAlternativeHost'
                            ]
                        ],
                        'EcmCarrinhoItem' => [
                            'EcmProduto' => [
                                'EcmTipoProduto', 'EcmImagem'
                            ],
                            'EcmCursoPresencialTurma' => [
                                'EcmProduto' => [
                                    'EcmTipoProduto', 'EcmImagem'
                                ]
                            ],
                            'EcmPromocao', 'EcmCupom',
                            'EcmCarrinhoItemEcmProdutoAplicacao' => [
                                'EcmProdutoEcmAplicacao' => [
                                    'EcmProdutoAplicacao'
                                ],
                            ],
                            'EcmCarrinhoItemMdlCourse' => [
                                'MdlCourse'
                            ]
                        ]
                    ]]);

                    foreach($ecmCarrinho->ecm_carrinho_item as $item){
                        if(!empty($item->ecm_carrinho_item_ecm_produto_aplicacao)){
                            $item->modulos = [];
                            $item->aplicacao = (object) array('vl_sugerido' => -1);
                            foreach($item->ecm_carrinho_item_ecm_produto_aplicacao as $key => $aplicacao){
                                $aplicacao->ecm_produto_ecm_aplicacao->vl_sugerido = $aplicacao->valor;
                                $aplicacao->ecm_produto_ecm_aplicacao->sugerido = $aplicacao->valor - $aplicacao->frete;
                                if($aplicacao->ecm_produto_ecm_aplicacao->vl_sugerido > $item->aplicacao->vl_sugerido){
                                    if(isset($item->aplicacao->id))
                                        array_push($item->modulos, $item->aplicacao);
                                    $item->aplicacao = $aplicacao->ecm_produto_ecm_aplicacao;
                                } else {
                                    array_push($item->modulos, $aplicacao->ecm_produto_ecm_aplicacao);
                                }
                            }
                            unset($item->ecm_carrinho_item_ecm_produto_aplicacao);
                        }
                        if(!empty($item->ecm_carrinho_item_mdl_course)){
                            if(!isset($item->modulos))
                                $item->modulos = [];
                            foreach($item->ecm_carrinho_item_mdl_course as $course){
                                array_push($item->modulos, $course);
                            }
                            unset($item->ecm_carrinho_item_mdl_course);
                        }
                    }

                    $itens = [];
                    foreach($ecmCarrinho->ecm_carrinho_item as $item){
                        if(!empty($item->aplicacao)){
                            $itens[$item->ecm_produto_id.'-A'.$item->aplicacao->id] = $item;
                        }else{
                            $itens[$item->ecm_produto_id] = $item;
                        }
                    }

                    $ecmCarrinho->ecm_carrinho_item = $itens;

                    if($ecmCarrinho->get('status') == EcmCarrinho::STATUS_CANCELADO)
                        $ecmCarrinho->set('status', EcmCarrinho::STATUS_EM_ABERTO);
                     
                    $ecmCarrinho->set('mdl_user_modified_id', $this->Auth->user('id'));
                }else{
                    $ecmCarrinho = $this->EcmCarrinho->newEntity();
                    $ecmCarrinho->set('ecm_carrinho_item', array());
                    $ecmCarrinho->set('status', EcmCarrinho::STATUS_EM_ABERTO);
                    $ecmCarrinho->set('ecm_alternative_host_id', $this->request->data["ecm_alternative_host_id"]);
                    $ecmCarrinho->set('mdl_user', $usuario);
                    $ecmCarrinho->set('mdl_user_modified_id', $this->Auth->user('id'));
                }

                $this->request->session()->write('carrinho', $ecmCarrinho);
                $this->EcmCarrinho->save($ecmCarrinho); 

                return $this->redirect(['controller' => '', 'action' => 'listaprodutos']);
            }

            $listaAtendimentosAltoQi = WscAltoQi::userListCalls($usuario->idnumber);
            $listaProdutosAltoQi = WscAltoQi::userListProduts($usuario->idnumber);

            if($listaProdutosAltoQi && count($listaProdutosAltoQi) > 0)
                $listaProdutosAltoQi = array_filter($listaProdutosAltoQi, function($prod){ return ( array_key_exists('area', $prod) && $prod['area'] != "CUR") || ( array_key_exists('aplicacao', $prod) && $prod['aplicacao'] != "CURSOS") ; });
            $userid = $usuario->id;
            // --------------------------- MATRICULAS ---------------------------------------
            $this->loadModel('MdlUser');
            $listaCursos = [];
            if ($this->MdlUser->exists(['MdlUser.id' => $userid])) {
                $mdlUser = $this->MdlUser->find('all')
                    ->contain(['MdlUserEnrolments' => function($q) use ($userid) {
                        return $q->contain(['MdlEnrol' => function ($q) use ($userid) {
                            return $q->contain(['MdlCourse' => function ($q) {
                                return $q->contain(['EcmProduto' => function ($q) {
                                    return $q->contain([ 'EcmTipoProduto' => function ($q) {
                                        return $q->select(['EcmTipoProduto.id', 'EcmTipoProduto.nome']);
                                    }])->select(['id', 'nome', 'sigla', 'refcurso'])->where(['refcurso' => 'true']);
                                }])->select(['id', 'curso' => 'fullname', 'category']);
                            }])->select(['roleid', 'enrolperiod', 'MdlEnrol.courseid'])
                            ->contain(['MdlGroups' => function($q) use ($userid) {
                                return $q->contain(['MdlGroupsMembers' => function($q) use ($userid) {
                                    return $q->select(['id', 'groupid', 'MdlGroupsMembers.userid'])
                                        ->where(['MdlGroupsMembers.userid' => $userid]);
                                }])->select(['id', 'courseid', 'mdl_fase_id']);
                            }]);
                        }])->select(['id', 'status', 'userid', 'timestart', 'timeend']);
                    }])
                    ->contain(['MdlUserEcmAlternativeHost' => function($q){
                        return $q->contain(['EcmAlternativeHost' => function($q){
                            return $q->select(['id', 'MdlUserEcmAlternativeHost.mdl_user_id']);
                        }]);
                    }])
                    ->select(['id'])->where(['id' => $userid ])->first();

                $this->loadModel('WebService.MdlCertificate');
                if(array_key_exists('mdl_user_enrolments', $mdlUser)){
                    foreach($mdlUser['mdl_user_enrolments'] as $user_enrolments){
                        $cursoIndividual = false;
                        if(array_key_exists('mdl_enrol', $user_enrolments) && array_key_exists('mdl_groups', $user_enrolments['mdl_enrol']) ){
                            foreach($user_enrolments['mdl_enrol']['mdl_groups'] as $MdlGroups){
                                if(!empty($MdlGroups['mdl_groups_members'])){
                                    if((empty($MdlGroups['mdl_fase_id']))){
                                        $cursoIndividual = true;
                                        break;
                                    }
                                    if((!empty($MdlGroups['mdl_fase_id']) && !isset($cursoIndividual))){
                                        $cursoIndividual = false;
                                    }
                                }
                            }
                        }
                        if(!isset($cursoIndividual) || $cursoIndividual){
                            $statusCurso = $this->MdlUser->verificaStatusCurso($user_enrolments, $usuario->id);
                            $user_enrolments['roleid'] = $statusCurso['roleid'];
                            $user_enrolments['status'] = empty($user_enrolments['status']) ? $statusCurso['status'] : "Curso Bloqueado";
                            unset($user_enrolments['userid']);
                            $user_enrolments['cursoid'] = $user_enrolments['mdl_enrol']['mdl_course']['id'];
                            $user_enrolments['category'] = $user_enrolments['mdl_enrol']['mdl_course']['category'];
                            $user_enrolments['alternativehostid'] = $mdlUser['ecm_alternative_host'];

                            if(is_array($user_enrolments['mdl_enrol']['mdl_course']['ecm_produto'])){
                                $ecm_produto = array_shift($user_enrolments['mdl_enrol']['mdl_course']['ecm_produto']);
                                unset($ecm_produto['_joinData']);
                                $user_enrolments['produto'] = $ecm_produto;
                                if(!empty($ecm_produto['ecm_tipo_produto'])) {
                                    $user_enrolments['produto']['categorias'] = $ecm_produto['ecm_tipo_produto'];
                                    unset($user_enrolments['produto']['ecm_tipo_produto']);
                                }
                            }

                            if(!is_null($user_enrolments['produto']['categorias'])){
                                $user_enrolments['produto']['categorias'] = array_map(function($tipo){
                                    return (object)['nome' => $tipo->nome, 'id' => $tipo->id];
                                },  $user_enrolments['produto']['categorias']);
                            }

                            $certificado = $this->MdlCertificate->find('all',[
                                'fields' => ['MdlCertificateIssues.timecreated']
                            ])
                                ->contain(['MdlCertificateIssues'])
                                ->where([
                                    'MdlCertificate.course' => $user_enrolments['cursoid'],
                                    'MdlCertificateIssues.userid' => $usuario->id
                                ])->first();

                            $user_enrolments['data_conclusao'] = null;

                            if($certificado)
                                $user_enrolments['data_conclusao'] = $certificado->MdlCertificateIssues->timecreated;

                            unset($user_enrolments['mdl_enrol']);
                        }
                    }
                }

                $listaCursos = $mdlUser['mdl_user_enrolments'] ;
            }

            $listaCarrinho = $this->EcmCarrinho->find()->contain(['MdlUser' => ['joinType' => 'LEFT'],
                'EcmCarrinhoItem' => [
                    'EcmPromocao',
                    'EcmCupom',
                    'EcmProduto' => ['EcmTipoProduto'],
                    'EcmCursoPresencialTurma' => [
                        'EcmCursoPresencialData' => [
                            'EcmCursoPresencialLocal' => [
                                'MdlCidade' => ['MdlEstado']
                            ]
                        ]
                    ]
                ]
            ])->where(['mdl_user_id' => $usuario->id ])
            ->orderDESC('EcmCarrinho.id');

            $this->set(compact('ecmAlternativeHost', 'usuario', 'listaCarrinho', 'listaProdutosAltoQi', 'listaCursos', 'listaAtendimentosAltoQi'));
            $this->set('_serialize', ['ecmAlternativeHost', 'usuario', 'listaCarrinho', 'listaProdutosAltoQi', 'listaCursos', 'listaAtendimentosAltoQi']);
        }
    }

    private function verificaUsuarioSelecionado(){
        $ecmCarrinho = $this->getCarrinho();
        $usuario = $ecmCarrinho->get('mdl_user');
        if(is_null($usuario)){
            $this->Flash->error(__('Usuário não selecionado!'));
            return $this->redirect(['plugin' => false, 'controller' => 'usuario','action' => 'listar-usuario']);
        }elseif(is_null($ecmCarrinho->get('ecm_alternative_host_id'))){
            $this->Flash->error(__('Entidade não selecionada!'));
            return $this->redirect(['plugin' => false, 'controller' => 'carrinho','action' => 'index', $usuario->id]);
        }
        return true;
    }

    /**
     * Função em desuso
     *
     * @return \Cake\Network\Response|null
     *
    public function validarCupom(){
        parent::validarCupomCarrinho();

        return $this->redirect(['controller' => '', 'action' => 'listaprodutos']);
    }*/

    public function listaCupom($id = null){

        $ecmCarrinho = $this->getCarrinho();
        $usuario = $ecmCarrinho->get('mdl_user');

        if(is_null($usuario)){
            $this->Flash->error(__('Usuário não selecionado!'));
            return $this->redirect(['plugin' => false, 'controller' => 'usuario','action' => 'listar-usuario']);
        }

        if ($this->request->is('post') && is_numeric($id)) {
            $cupom = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->get($id, ['contain' => 'MdlUser']);
            if (!is_null($cupom)) {
                $listaUsuarios = ['mdl_user' => ['_ids' => []]];

                foreach ($cupom->mdl_user as $user) {
                    $listaUsuarios['mdl_user']['_ids'][] = $user->id;
                }

                $listaUsuarios['mdl_user']['_ids'][] = $usuario->id;

                $ecmCupom = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->patchEntity($cupom, $listaUsuarios);

                $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->save($ecmCupom);
            }
        }

        $alternativeHost = $this->EcmCarrinho->EcmAlternativeHost->get($ecmCarrinho->get('ecm_alternative_host_id'));

        $listaCuponsQiSat = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom
            ->buscarCupons(
                $usuario,
                $alternativeHost,
                'qisat'
            );

        $listaCuponsAltoQi = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom
            ->buscarCupons(
                $usuario,
                $alternativeHost,
                'altoqi'
            );

        $listaCupons = $this->EcmCarrinho->EcmCarrinhoItem->EcmCupom->buscarCupons(null);

        $this->set(compact('listaCuponsQiSat', 'listaCuponsAltoQi', 'listaCupons', 'usuario'));
        $this->set('_serialize', ['listaCuponsQiSat', 'listaCuponsAltoQi', 'listaCupons', 'usuario']);
    }

    /**
     * ListaProdutos method
     *
     * @return \Cake\Network\Response|null
     */
    public function listaprodutos() {
        if(isset($this->request->params["id"])){
            $this->request->data[$this->request->params["id"]] = "1";
        }

        if(isset($this->request->data["presencial"]) && $this->request->data["presencial"] == "1"){

            $where = ['EcmProduto.habilitado' => 'true', 'EcmProduto.preco IS NOT NULL', 'datafim >' => date("Y-m-d") ];
            if(isset($this->request->data["produto"]) && $this->request->data["produto"])
                $where['EcmProduto.nome like'] = '%'.$this->request->data["produto"].'%';

            $ecmProduto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->find('all', ['fields' =>
                ['EcmProduto.id','EcmProduto.nome', 'EcmProduto.sigla' ,'EcmProduto.preco']])
                ->contain(['EcmTipoProduto' => function ($q) {
                       return $q->select(['id','nome'])->where(['EcmTipoProduto.id' => '10']);
                   }])
                ->innerJoin(['EcmCursoPresencialTurma' => 'ecm_curso_presencial_turma'],
                    'EcmCursoPresencialTurma.ecm_produto_id = EcmProduto.id')
                ->innerJoin(['EcmCursoPresencialData' => 'ecm_curso_presencial_data'],
                    'EcmCursoPresencialData.ecm_curso_presencial_turma_id = EcmCursoPresencialTurma.id')
                ->contain(['EcmCursoPresencialTurma' => function ($q) {
                    return $q->autoFields(false)
                        ->select(['id','vagas_total','vagas_preenchidas','valor','valor_produto','ecm_produto_id'])
                        ->innerJoinWith('EcmCursoPresencialData', function ($q) {
                            return $q->autoFields(false)
                                ->select(['id','datainicio'])
                                ->contain(['EcmCursoPresencialLocal' => function ($q) {
                                    return $q->autoFields(false)
                                        ->select(['id','mdl_cidade_id'])
                                        ->contain(['MdlCidade' => function ($q) {
                                            return $q->contain(['MdlEstado']);
                                        }]);
                                }])
                                ->where(['datafim >' => date("Y-m-d")]);
                        })
                        ->where(['status' => 'Ativo'])
                        ->group(['EcmCursoPresencialTurma.id']);
                }])
                ->where($where)
                ->group(['EcmProduto.id'])
                ->order(['EcmProduto.id']);
        }else{
            $where = ['EcmProduto.habilitado' => 'true', 'EcmProduto.preco IS NOT NULL'];
            $tipoProduto = $this->request->data('ecm_tipo_produto');
            $contain = ['EcmTipoProduto'];

            if(isset($this->request->data["produto"]) && $this->request->data["produto"] != "")
                $where['EcmProduto.nome like'] = '%'.$this->request->data["produto"].'%';
            if(isset($this->request->data["sigla"]) && $this->request->data["sigla"] != "")
                $where['EcmProduto.sigla like'] = '%'.$this->request->data["sigla"].'%';

            $ecmProduto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->find('all', ['fields' =>
                    ['EcmProduto.id','EcmProduto.nome', 'EcmProduto.sigla' ,'EcmProduto.preco']])
                            ->contain($contain)
                            ->where($where);

            if(!is_null($tipoProduto) && $tipoProduto["_ids"] != ""){
                $ecmProduto->matching('EcmTipoProduto', function($q) use ($tipoProduto) {
                    return $q->where(['EcmTipoProduto.id IN' => $tipoProduto['_ids']]);
                });
            }
        }

        $ecmProduto = $this->paginate($ecmProduto);
        $ecmCarrinho = $this->getCarrinho();

        $ecmPromocoes = $this->EcmCarrinho->EcmCarrinhoItem->EcmPromocao->find('all',['fields'=>['EcmPromocaoEcmProduto.ecm_produto_id',
            'id','datainicio','datafim','descontovalor','descontoporcentagem','arredondamento']])
            ->matching('EcmAlternativeHost')
            ->leftJoin(['EcmPromocaoEcmProduto'=>'ecm_promocao_ecm_produto'],
                'EcmPromocaoEcmProduto.ecm_promocao_id = EcmPromocao.id')
            ->where(['EcmPromocao.habilitado' => 'true',
                'EcmPromocao.datainicio <=' => date("Y-m-d"),
                'EcmPromocao.datafim >=' => date("Y-m-d"),
                'EcmAlternativeHost.id' => $ecmCarrinho->ecm_alternative_host_id])->toArray();

        $cupom = $this->request->session()->read('cupom');

        $ecmPromocao = [];
        foreach($ecmPromocoes as $promocao){
            if(!isset($ecmPromocao[$promocao->EcmPromocaoEcmProduto["ecm_produto_id"]])){
                $ecmPromocao[$promocao->EcmPromocaoEcmProduto["ecm_produto_id"]] = [];
            }
            array_push($ecmPromocao[$promocao->EcmPromocaoEcmProduto["ecm_produto_id"]], $promocao);
            unset($promocao->EcmPromocaoEcmProduto);
        }
        foreach($ecmProduto as $produto){
            $promocaoProduto = isset($ecmPromocao[$produto->id]) ? $ecmPromocao[$produto->id] : [];
            $ecmPromocoes = EcmCarrinho::verificarDesconto($produto, $promocaoProduto, $cupom);
            if(isset($ecmPromocoes)){
                if(isset($ecmPromocoes["promocao"])){
                    $produto->datafim = $ecmPromocoes["promocao"]->datafim->format("d/m/y");
                }
                if(isset($ecmPromocoes["cupom"])){
                    if(!isset($produto->datafim) || $produto->datafim > $ecmPromocoes["cupom"]->datafim->format("d/m/y")){
                        $produto->datafim = $ecmPromocoes["cupom"]->datafim->format("d/m/y");
                    }
                }
                $produto->descontoTotal = $ecmPromocoes["descontoTotal"];
                $produto->valorTotal = $ecmPromocoes["valorTotal"];
            }
        }

        $usuario = $ecmCarrinho->get('mdl_user');

        $this->set(compact('ecmProduto', 'usuario'));
        $this->set('_serialize', ['ecmProduto', 'usuario']);

        if(isset($this->request->data["presencial"]) && $this->request->data["presencial"] == "1"){
            $this->render("listaprodutospresenciais");
        } else {

            $optionsTipoProduto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->EcmTipoProduto->find('list',
                    ['keyField' => 'id', 'valueField' => 'nome'])
                ->where(['habilitado' => 'true', 'id !=' => '10']);

            $this->set(compact('optionsTipoProduto'));
            $this->set('_serialize', ['optionsTipoProduto']);
        }
    }

    /**
     * MontarCarrinho method
     *
     * @return \Cake\Network\Response|null
     */
    public function montarcarrinho() {
        $this->loadModel('Configuracao.EcmConfig');
        $limit_desconto_altoqi = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'limite_desconto_produto_altoqi'])->first();
        $limit_desconto_qisat = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'limite_desconto_produto_qisat'])->first();

        $ecmCarrinho = $this->getCarrinho();

        if(is_null($ecmCarrinho->ecm_carrinho_item)){
            $ecmCarrinho->ecm_carrinho_item = [];
        } else {
            foreach($ecmCarrinho->ecm_carrinho_item as $item){
                if(isset($item->ecm_promocao) && isset($item->ecm_cupom)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[$item->ecm_promocao],$item->ecm_cupom);
                } else if(isset($item->ecm_promocao)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[$item->ecm_promocao]);
                } else if(isset($item->ecm_cupom)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[],$item->ecm_cupom);
                }
                if(isset($descontos)){
                    if(isset($item->ecm_promocao) && is_null($item->ecm_promocao->descontovalor) && isset($descontos["descontoPromocao"])){
                        $item->ecm_promocao->descontovalor = $descontos["descontoPromocao"];
                    }
                    if(isset($item->ecm_cupom) && is_null($item->ecm_cupom->descontovalor) && isset($descontos["descontoCupom"])){
                        $item->ecm_cupom->descontovalor = $descontos["descontoCupom"];
                    }
                }
            }
        }

        $usuario = $ecmCarrinho->get('mdl_user');

        $prazoExtra = MdlUser::verificarPermissao('prazoExtra', $this->request->controller,
                $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));

        $editValor = MdlUser::verificarPermissao('edit', $this->request->controller,
                $this->request->plugin, $this->request->session()->read('Auth.User.permissoes'));

        foreach($ecmCarrinho->ecm_carrinho_item as $item) {


            if($editValor) 
                $item->editValor = true;
            else
                $item->editValor = false;

            foreach ($item->ecm_produto->ecm_tipo_produto as $tipoproduto) {
                if ($tipoproduto->id == 16 ) { // "Prazo Extra"
                    $item->prazoExtra = $prazoExtra;
                    $item->isExtratime = true;
                }

                if ($tipoproduto->id == 48 ) { // Produto AltoQI
                    $item->isAltoqi = true;
                    $item->limit_desconto_altoqi = ($limit_desconto_altoqi) ? $limit_desconto_altoqi->get('valor') : false;
                }else{
                    $item->limit_desconto_qisat = ($limit_desconto_qisat) ? $limit_desconto_qisat->get('valor') : false;
                }

                switch ($tipoproduto->id) {
                    case 48:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 47:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 32:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 17:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 40:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 12:
                        $item->categoria = $tipoproduto->nome;
                        break; 
                    case 10:
                        $item->categoria = $tipoproduto->nome;
                        break;
                    case 2:
                        $item->categoria = $tipoproduto->nome;
                        break;           
                }
            }
        }

        $this->set(compact('ecmCarrinho','usuario'));
        $this->set('_serialize', ['ecmCarrinho','usuario']);
    }

    /**
     * ConfirmarDados method
     *
     * @return \Cake\Network\Response|null
     */
    public function confirmardados() {
        $operadora = [];
        $valorParcelas = [];
        $valorParcelas[0] = 'Selecione o Tipo de Pagamento';
        $tipoPagamento = [];

        $this->loadModel('FormaPagamento.EcmFormaPagamento');

        if($this->request->is('post')){

            if(is_null($this->request->data('avancar'))) {
                $this->autoRender = false;
                $forma = $this->request->data('formaPagamento');
                if (!is_null($forma) && is_numeric($forma) && $forma) {
                    if (!is_null($parcelas = $this->calcularValorParcelas($forma))) {
                        $valorParcelas = $parcelas;
                        $valorParcelas[0] = 'Selecione';
                        ksort($valorParcelas);
                    }

                    $this->loadModel('FormaPagamento.EcmOperadoraPagamento');
                    $this->loadModel('FormaPagamento.EcmTipoPagamento');

                    $operadora = $this->EcmOperadoraPagamento->find('list', [
                        'keyField' => 'id', 'valueField' => 'ecm_imagem.src',
                        'conditions' => ['habilitado' => 'true', 'ecm_forma_pagamento_id' => $forma]])
                        ->contain(['EcmImagem'])->toArray();

                    $tipoPagamento = $this->EcmTipoPagamento->find('list', ['keyField' => 'id', 'valueField' => 'nome',
                        'conditions' => ['habilitado' => 'true', 'ecm_forma_pagamento_id' => $forma]])->toArray();
                    $tipoPagamento[0] = 'Selecione';
                    ksort($tipoPagamento);
                }
                echo json_encode(['operadora' => $operadora, 'valorParcelas' => $valorParcelas, 'tipoPagamento' => $tipoPagamento]);
            }else{
                 echo json_encode(['operadora' => 'result' ]);
                
                $carrinho = $this->getCarrinho();
                $venda = $this->EcmCarrinho->EcmVenda
                              ->find()->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();

                if(!$venda) {
                    $venda = $this->EcmCarrinho->EcmVenda->newEntity();
                }

                if($this->request->data('contrato') != 1){
                    $this->Flash->error(__('Para continuar com o processo de compra você deve aceitar o contrato'));
                }

                if(is_null($this->request->data('operadora')) || is_null($this->request->data('tipoPagamento'))
                    || empty($this->request->data('valorParcelas'))){
                    $formaPagamento = $this->EcmFormaPagamento->find()->contain(['EcmTipoPagamento'])
                        ->where(['nome' => $this->request->data('formaPagamento')])->first();
                    if(isset($formaPagamento) && ($formaPagamento->dataname == "checkout" ||
                    $formaPagamento->dataname == "api")){
                        $vendaStatus = $this->EcmCarrinho->EcmVenda->EcmVendaStatus
                            ->find()->where(['status' => EcmVendaStatus::STATUS_ANDAMENTO])->first();

                        $total = $carrinho->calcularTotal();
                        if(array_key_exists('valorParcelas', $this->request->data) && $this->request->data('valorParcelas')){
                            $parcelas = $this->request->data('valorParcelas');
                            $valorParcelas = number_format($total / $parcelas, 2);
                            $venda->set('valor_parcelas', $valorParcelas);
                            $venda->set('numero_parcelas', $parcelas);
                        } else {
                            $venda->set('valor_parcelas', $total);
                            $venda->set('numero_parcelas', 1);
                            $this->request->data['valorparcelas'] = 1;
                        }
                        $venda->set('ecm_venda_status', $vendaStatus);
                        $venda->set('mdl_user_id', $carrinho->get('mdl_user_id'));
                        $venda->set('ecm_tipo_pagamento', $formaPagamento->get('ecm_tipo_pagamento')[0]);
                        $venda->set('ecm_carrinho_id', $carrinho->get('id'));

                        $this->EcmCarrinho->EcmVenda->save($venda);

                        $this->loadModel('EcmLogContrato');
                        if(!$this->EcmLogContrato->exists(['ecm_venda_id' => $venda->id])){
                            $ecmLogContrato = $this->EcmLogContrato->newEntity();
                            $ecmLogContrato->ecm_venda_id = $venda->id;
                            $ecmLogContrato->timecreated = time();
                            $this->EcmLogContrato->save($ecmLogContrato);
                        }

                        $this->request->session()->write('compraConfirmada', true);

                        $controllerFormaPagamento = $formaPagamento->get('controller');
                        $plugin = 'FormaPagamento'.$controllerFormaPagamento;

                        if(array_key_exists('valorParcelas', $this->request->data)){
                            $valorParcelas = JWT::encode(
                                $this->request->data
                                , Security::salt());
                            $this->request->session()->write('info', $valorParcelas);
                        }

                        return $this->redirect(['plugin' => $plugin,'controller' => '','action' => 'requisicao']);
                    }
                    $this->Flash->error(__('Por favor selecione todos os dados para pagamento!'));
                }else{

                    $total = $carrinho->calcularTotal();
                    $parcelas = $this->request->data('valorParcelas');
                    if(empty($parcelas))
                         $this->Flash->error(__('Parcela não encontrada'));
                    else
                        $valorParcelas = $total / $parcelas;

                    $operadora = null;
                    try {
                        $operadora = $this->EcmCarrinho->EcmVenda->EcmOperadoraPagamento
                            ->get($this->request->data('operadora'), ['contain' => ['EcmFormaPagamento']]);
                    } catch (RecordNotFoundException $e) {
                        $this->Flash->error(__('Operadora não encontrada'));
                    }

                    $tipoPagamento = null;
                    try {
                        $tipoPagamento = $this->EcmCarrinho->EcmVenda->EcmTipoPagamento
                            ->get($this->request->data('tipoPagamento'));

                    } catch (RecordNotFoundException $e) {
                        $this->Flash->error(__('Tipo de pagamento não encontrado'));
                    }

                    if(!empty($parcelas) && $operadora && $tipoPagamento){
                        $parcelasArray = $this->calcularValorParcelas($operadora->get('ecm_forma_pagamento_id'));

                        if(!in_array($valorParcelas, $parcelasArray)){
                            $this->Flash->error(__('Numero de parcelas incorreto, por favor selecione a forma de pagamento'));
                        }

                        $vendaStatus = $this->EcmCarrinho->EcmVenda->EcmVendaStatus
                            ->find()->where(['status' => EcmVendaStatus::STATUS_ANDAMENTO])->first();

                        $venda->set('valor_parcelas', $valorParcelas);
                        $venda->set('numero_parcelas', $parcelas);
                        $venda->set('ecm_venda_status', $vendaStatus);
                        $venda->set('mdl_user_id', $carrinho->get('mdl_user_id'));
                        $venda->set('ecm_operadora_pagamento', $operadora);
                        $venda->set('ecm_tipo_pagamento', $tipoPagamento);
                        $venda->set('ecm_carrinho_id', $carrinho->get('id'));

                        $this->EcmCarrinho->EcmVenda->save($venda);

                        $this->loadModel('EcmLogContrato');
                        if(!$this->EcmLogContrato->exists(['ecm_venda_id' => $venda->id])){
                            $ecmLogContrato = $this->EcmLogContrato->newEntity();
                            $ecmLogContrato->ecm_venda_id = $venda->id;
                            $ecmLogContrato->timecreated = time();
                            $this->EcmLogContrato->save($ecmLogContrato);
                        }

                        $this->request->session()->write('compraConfirmada', true);

                        $controllerFormaPagamento = $operadora->get('ecm_forma_pagamento')->get('controller');
                        $plugin = 'FormaPagamento'.$controllerFormaPagamento;

                        $this->request->data['cartao']['valorparcelas'] = $this->request->data['valorParcelas'];
                        $cartao = JWT::encode(
                            $this->request->data['cartao']
                        , Security::salt());
                        $this->request->session()->write('info', $cartao);

                        return $this->redirect(['plugin' => $plugin,'controller' => '','action' => 'requisicao']);
                    }else
                        $this->Flash->error(__('Por favor selecione todos os dados para pagamento!'));
                }
            }
        }

        if($this->request->query('error'))
            $this->Flash->error(__('Falha no pagamento!'));

        $ecmCarrinho = $this->getCarrinho();

        if(is_null($ecmCarrinho->ecm_carrinho_item)){
            $ecmCarrinho->ecm_carrinho_item = [];
        } else {
            foreach($ecmCarrinho->ecm_carrinho_item as $item){
                if(isset($item->ecm_promocao) && isset($item->ecm_cupom)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[$item->ecm_promocao],$item->ecm_cupom);
                } else if(isset($item->ecm_promocao)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[$item->ecm_promocao]);
                } else if(isset($item->ecm_cupom)){
                    $descontos = EcmCarrinho::verificarDesconto($item->ecm_produto,[],$item->ecm_cupom);
                }
                if(isset($descontos)){
                    if(isset($item->ecm_promocao) && is_null($item->ecm_promocao->descontovalor) && isset($descontos["descontoPromocao"])){
                        $item->ecm_promocao->descontovalor = $descontos["descontoPromocao"];
                    }
                    if(isset($item->ecm_cupom) && is_null($item->ecm_cupom->descontovalor) && isset($descontos["descontoCupom"])){
                        $item->ecm_cupom->descontovalor = $descontos["descontoCupom"];
                    }
                }
            }
        }

        $usuario = $ecmCarrinho->get('mdl_user');

        $formaPagamento = $this->EcmFormaPagamento->find('list', ['keyField' => 'id', 'valueField' => function ($formaPagamento) {
            return ['text' => $formaPagamento->nome, 'value' => $formaPagamento->id,
                'tipo' => $formaPagamento->tipo, 'controller' => $formaPagamento->controller, 'dataname' => $formaPagamento->dataname];
        }, 'conditions' => ['habilitado' => 'true', 'ecm_alternative_host_id' => $ecmCarrinho->ecm_alternative_host_id]])
            //->group(['EcmFormaPagamento.tipo'])
            ->toArray();

        $formaPagamento[0] = 'Selecione';
        ksort($formaPagamento);

        $this->loadModel('Config.EcmConfig');
        $linkSite = $this->EcmConfig->find()->where(['nome' => 'dominio_acesso_site'])->first()->valor;
        
        // CORRIGIR PROBLEMA HTTP://
        $link = "https://".$linkSite."/proposta/".$ecmCarrinho->get('id');

        $mes = [NULL => 'Mês', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06',
            '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12'];
        $ano = [NULL => 'Ano'];
        for($i=0; $i <= 12; $i++){
            $ano[date("Y")+$i] = date("Y")+$i;
        }

        $this->set(compact('ecmCarrinho','formaPagamento','valorParcelas','usuario','link','mes','ano'));
        $this->set('_serialize', ['ecmCarrinho','formaPagamento','valorParcelas','usuario','link','mes','ano']);
    }

    /**
     * Contrato method
     *
     * @return \Cake\Network\Response|null
     */
    public function contrato() {
        $ecmCarrinho = $this->getCarrinho();

        $usuario = $ecmCarrinho->get('mdl_user');

        foreach($ecmCarrinho->ecm_carrinho_item as $item) {
            $tipo = "";
            foreach($item->ecm_produto->ecm_tipo_produto as $tipoproduto){
                if($tipoproduto->nome == "Prazo Extra" || $tipoproduto->nome == "Pacotes" || $tipoproduto->nome == "Presencial"){
                    $tipo = $tipoproduto->nome;
                    break;
                }
            }
            if($tipo == "Presencial") {
                $EcmCursoPresencialTurma = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->EcmCursoPresencialTurma->find()
                    ->where(['EcmCursoPresencialTurma.id' => $item->ecm_curso_presencial_turma->id])
                    ->innerJoinWith('EcmCursoPresencialData', function ($q) {
                        return $q->contain(['EcmCursoPresencialLocal' => function ($q) {
                            return $q->contain(['MdlCidade' => function ($q) {
                                return $q->contain(['MdlEstado']);
                            }]);
                        }]);
                    });
                $item->ecm_curso_presencial_turma->ecm_curso_presencial_data = $EcmCursoPresencialTurma->first()->ecm_curso_presencial_data;
            } else {
                $EcmProduto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->find()
                    ->where(['EcmProduto.id' => $item->ecm_produto->id]);
                if($tipo == "Prazo Extra"){
                    $EcmProduto->contain(['MdlCourse', 'EcmProdutoPrazoExtra']);
                } else if($tipo == "Pacotes"){
                    $EcmProduto->contain(['MdlCourse', 'EcmProdutoPacote']);
                } else {
                    $EcmProduto->contain(['MdlCourse' => ['MdlEnrol' => ['conditions' => ['MdlEnrol.enrol like "manual"']]]]);
                }
                $EcmProduto = $EcmProduto->first();
                $item->ecm_produto->mdl_course = $EcmProduto->mdl_course;
                if($tipo == "Prazo Extra"){
                    $item->ecm_produto->ecm_produto_prazo_extra = $EcmProduto->ecm_produto_prazo_extra;
                } else if($tipo == "Pacotes"){
                    $item->ecm_produto->ecm_produto_pacote = $EcmProduto->ecm_produto_pacote;
                }
            }
        }

        $this->set(compact('ecmCarrinho','usuario'));
        $this->set('_serialize', ['ecmCarrinho','usuario']);
    }

    /**
     * Agendamento method
     *
     * @return \Cake\Network\Response|null
     */
    public function agendamento(){
        $ecmCarrinho = $this->getCarrinho();

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = (int)explode('-', $this->request->data['id'])[0];
            $tipoproduto = "";
            $item = 0;
            foreach($ecmCarrinho->ecm_carrinho_item as $carrinho_item) {
                if($carrinho_item->ecm_produto->id == $id){
                    $item = $carrinho_item->id;
                    foreach($carrinho_item->ecm_produto->ecm_tipo_produto as $ecm_tipo_produto) {
                        if ($ecm_tipo_produto->nome == "Pacotes" || $ecm_tipo_produto->nome == "Prazo Extra") {
                            $tipoproduto = $ecm_tipo_produto->nome;
                            break;
                        }
                    }
                }
            }
            $this->loadModel('Produto.EcmProduto');
            switch($tipoproduto){
                case "Pacotes":
                    $ecmProduto = $this->EcmProduto->get($id, ['contain' => ['EcmProdutoPacote']]);
                    $enrolperiod = (int)$ecmProduto->ecm_produto_pacote->enrolperiod;
                    break;
                case "Prazo Extra":
                    $ecmProduto = $this->EcmProduto->get($id, ['contain' => ['EcmProdutoPrazoExtra']]);
                    $enrolperiod = (int)$ecmProduto->ecm_produto_prazo_extra->enrolperiod;
                    break;
                default:
                    $ecmProduto = $this->EcmProduto->get($id, ['contain' => ['MdlCourse' =>
                        ['MdlEnrol' => ['conditions' => ['MdlEnrol.enrol like "manual"']]]]]);;
                    $enrolperiod = (int)$ecmProduto->mdl_course[0]->mdl_enrol[0]->enrolperiod;
                    $enrolperiod = $enrolperiod/24/60/60;
                    break;
            }
            echo json_encode(['enrolperiod' => $enrolperiod, 'item' => $item]);
        } else if($this->request->is('post')){
            if(isset($this->request->data["date1"]) && $this->request->data["date1"] != "") {
                $count = 1;
                do {
                    if($this->request->data["date".$count] != "") {
                        $data[$count]['datainicio'] = $this->request->data["date" . $count];
                        $data[$count]['item'] = $this->request->data["item" . $count];
                        $data[$count]['duracao'] = $this->request->data["enrolperiod" . $count];
                    }
                    $count++;
                } while (array_key_exists("distancia".$count, $this->request->data));
                if(isset($data)) {
                    $this->request->data = $data;
                    $retorno = parent::agendar();
                    if ($retorno['sucesso']) {
                        $this->Flash->success(__('O agendamento foi realizado com sucesso.'));
                        return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
                    } else {
                        $this->Flash->error(__($retorno['mensagem']));
                    }
                } else {
                    $this->Flash->error(__('Erro ao agendar o produto. Favor, tente novamente.'));
                }
                $this->request->data = [];
            }
        }

        $this->loadModel('CursoPresencial.EcmCursoPresencialTurma');
        $aDistancia = [0 => 'Selecione o produto'];
        $presencial = [];

        foreach($ecmCarrinho->ecm_carrinho_item as $carrinho_item) {
            if($carrinho_item->status == "Adicionado") {
                $altoqi = false;
                foreach($carrinho_item->ecm_produto->ecm_tipo_produto as $tipo_produto) {
                    /**
                     * 47 = AltoQi LAB -  Programa de capacitação profissional em projetos de edificações
                     * 48 = Produtos AltoQi
                     */
                    if($tipo_produto->id == 47 || $tipo_produto->id == 48){
                        $altoqi = true;
                    }
                }
                if(!$altoqi){
                    $item = clone $carrinho_item;
                    if (isset($item->ecm_curso_presencial_turma_id)) {
                        $item->ecm_curso_presencial_turma = $this->EcmCursoPresencialTurma->find()->contain(['EcmCursoPresencialData'])
                            ->where(['EcmCursoPresencialTurma.id' => $item->ecm_curso_presencial_turma_id])->first();
                    }
                    $cont = 0;
                    while ($item->quantidade > $cont++) {
                        if (isset($item->ecm_curso_presencial_turma_id)) {
                            $presencial[] = $item;
                        } else {
                            $aDistancia[$item->ecm_produto->id . '-' . $cont] = $item->ecm_produto->nome;
                        }
                    }
                }
            }
        }

        if(empty($presencial) && count($aDistancia) == 1){
            return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
        }

        $usuario = $ecmCarrinho->get('mdl_user');

        $this->set(compact('usuario','aDistancia','presencial'));
        $this->set('_serialize', ['usuario','aDistancia','presencial']);
    }

    /*
     * Função reponsável por adicionar um produto em um carrinho.
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  produto: (id do produto),
     *  presencial: (id das turmas presenciais),
     *  quantidade: (quantidade de itens, esse parâmetro é opcional)
     * }
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
     * 4- {'sucesso':false, 'mensagem': 'Parâmetro quantidade incorreto'}
     * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
     * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
     * 7- {'sucesso':false, 'mensagem': 'Não há vagas o suficiente'}
     *
     * */
    public function add(){
        $this->autoRender = false;
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        echo json_encode(parent::addItem());
    }

    /*
     * Função reponsável por adicionar um produto em um carrinho.
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  produto: (id do produto),
     *  presencial: (id das turmas presenciais),
     *  valor: (valor do item)
     * }
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
     * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
     * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
     * 7- {'sucesso':false, 'mensagem': 'Não há vagas o suficiente'}
     *
     * */
    public function edit(){
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        $this->autoRender = false;
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
        $aplicacao = NULL;
        
        if($this->request->is('get') && !empty($this->request->query) && !is_null($this->request->query('produto'))){
            $idProduto = $this->request->query('produto');

            if( strpos( $idProduto, '-A') > 0 ){
                $ids = explode("-A", $idProduto);

                if($ids){
                    $idProduto = $ids[0];
                    $idApp = $ids[1];
                    $aplicacao = $this->EcmProdutoEcmAplicacao->get($idApp, ['contain' => ['EcmProdutoAplicacao']]);
                }
            }

            $produto = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->get($idProduto);
            $carrinho = $this->getCarrinho();
            $item = $this->EcmCarrinho->EcmCarrinhoItem->newEntity();
            $item->set('ecm_produto', $produto);
            $item->set('aplicacao', $aplicacao);

            if ($carrinho->existeItem($item)) {
                $item = $carrinho->getItem($item);
                
                $modulos = $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemMdlCourse->find()
                    ->select(['mdl_course_id', 'valor', 'descricao' => 'fullname'])
                    ->contain(['MdlCourse'])
                    ->where(['ecm_carrinho_item_id' => $item->id])->toArray();

                $aplicacoes = $this->EcmCarrinho->EcmCarrinhoItem->EcmCarrinhoItemEcmProdutoAplicacao->find()
                    ->select(['ecm_produto_ecm_aplicacao_id', 'valor', 'descricao' => 'CONCAT(descricao,SUBSTR(modulos_linha,3))'])
                    ->contain(['EcmProdutoEcmAplicacao.EcmProdutoAplicacao'])
                    ->where(['ecm_carrinho_item_id' => $item->id, 'valor !=' => 0])->toArray();

                $modulos = array_merge($modulos, $aplicacoes);
                echo json_encode($modulos);
            }
        } else {
            echo json_encode(parent::editValor());
        }
    }

    /*
    * Função reponsável por remover um produto em um carrinho.
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  produto: (id do produto),
    *  remover_tudo: ('1 ou 0, esse parâmetro é opcional)
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Deve ser feito uma requisição do tipo POST'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto incorreto'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro remover_tudo incorreto'}
    * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetros não informados'}
    *
    * */
    public function remove(){
        $this->autoRender = false;
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

        echo json_encode(parent::removeItem());
    }

    /**
     *
     * Função que recebe o id da forma de pagamento como parametro e
     * responde um array com a seguinte estrutura(Quant. das parcelas => valor da parcelas)
     *
     * @param Integer $formaPagamento ID da Forma de Pagamento
     *
     * @return Array
     *
     * */
    public function calcularValorParcelas($formaPagamento){
        $this->loadModel('FormaPagamento.EcmFormaPagamento');
        $this->loadModel('Configuracao.EcmConfig');

        $parcelas = $this->EcmFormaPagamento->get($formaPagamento, ['fields'=>['parcelas']])->parcelas;

        $EcmCarrinho = $this->getCarrinho();
        $produto_altoqi = false;

        if(!is_null($EcmCarrinho->ecm_carrinho_item))
            foreach($EcmCarrinho->ecm_carrinho_item as $item){
                foreach($item->ecm_produto->ecm_tipo_produto as $tipo){
                    if($tipo->id == 48)
                        $produto_altoqi = true;
                }
            }

        if(!$produto_altoqi){
            $valor_minimo_parcela = $this->EcmConfig->find()->where(['nome' => 'valor_minimo_parcela'])->first();
            $valor_minimo_parcela = str_replace(",",".",str_replace(".","",$valor_minimo_parcela->get('valor')));

            $maximo_numero_parcela = $this->EcmConfig->find()->where(['nome' => 'maximo_numero_parcela'])->first();
            if(!is_null($maximo_numero_parcela) && $parcelas > $maximo_numero_parcela->get('valor'))
                $parcelas = $maximo_numero_parcela->get('valor');
        }

        $totalItens = 0;

        if(!is_null($EcmCarrinho->ecm_carrinho_item))
            foreach($EcmCarrinho->ecm_carrinho_item as $item){
                if($item->status == "Adicionado" && isset($item->ecm_promocao)){
                    $numaxparcelas = $item->ecm_promocao->numaxparcelas;
                    if(!is_null($numaxparcelas) && $numaxparcelas > 0)
                        $parcelas = min($parcelas, $numaxparcelas);
                }
                if($item->status == "Adicionado")
                    $totalItens++;
            }

        if($totalItens == 1){
            $produto = end($EcmCarrinho->ecm_carrinho_item);
            $produto = $produto->get('ecm_produto');

            if(!is_null($produto->get('parcela')) && $produto->get('parcela') > 0)
                $parcelas = min($parcelas, $produto->get('parcela'));
        }

        $total = $EcmCarrinho->calcularTotal();
        $valorParcelas = [1 => $total];

        for ($i = 2; $i <= $parcelas; $i++) {
            $valorParcelado = $total / $i;
            if(!isset($valor_minimo_parcela) || $valorParcelado > $valor_minimo_parcela){
                $valorParcelas[$i] = $valorParcelado;
            }
        }

        return $valorParcelas;
    }

    /**
     * visualizar_carrinhos method
     *
     * @return \Cake\Network\Response|null
     */
    public function visualizarCarrinhos()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('Produto.EcmProduto');
            $this->loadModel('Produto.EcmProdutoEcmAplicacao');

            $produto = $this->request->data['produto'];
            $ecmCarrinhoItem = $this->EcmCarrinho->EcmCarrinhoItem->find()
                                        ->contain(['EcmProdutoEcmAplicacao' => function($q) use ($produto) {
                                            return $q->contain(['EcmProdutoAplicacao'])->where(['EcmProdutoEcmAplicacao.ecm_produto_id' => $produto]);
                                        }])->where(['id' => $this->request->data['item']])->first();

            if(is_array($ecmCarrinhoItem->ecm_produto_ecm_aplicacao)){
                $aplicacao = $ecmCarrinhoItem->ecm_produto_ecm_aplicacao[0];
                
                $descricao = $this->EcmProduto->encriptCodigotw($aplicacao);
                $descricao = '<b>Codigo:</b> ' . $descricao['codigo_tw'] .
                    '<br><b>Descrição:</b> ' . $descricao['descricao'] .
                    '<br><b>Qtde de pontos de rede:</b> ' . $aplicacao['_joinData']->qtde_pontos_rede .
                    '<br><b>Frete:</b> ' . $aplicacao['_joinData']->frete .
                    '<br><b>Observação:</b> ' . $aplicacao['_joinData']->observacao .
                    '<br><b>Ativação:</b> ' . $aplicacao->ecm_produto_aplicacao->ativacao .
                    '<br><b>Tipo:</b> ' . $aplicacao['_joinData']->tipo;
                if(!is_null($aplicacao['_joinData']->desconto_renovacao))
                    $descricao .= '<br><b>Tempo para desconto na renovação:</b> ' . $aplicacao['_joinData']->desconto_renovacao;
                if(!empty($aplicacao['_joinData']->upgrade))
                    $descricao .= '<br><b>Codigo de upgrade:</b> ' . $aplicacao['_joinData']->upgrade;
                echo json_encode(['descricao' => $descricao]);
                die;
            }

            echo json_encode(['descricao' => 'Aplicação não encontrada']);
            die;
        }
        $conditions = [];
        $conditionsUser = ['joinType' => 'LEFT'];

        if($this->request->is('post'))
            $this->request->query = $this->request->data;

        if($this->request->is('get'))
            $this->request->data = $this->request->query;

        if(count($this->request->data)){
            if(!empty($this->request->data['idnumber'])){
                $conditionsUser = ['conditions' => ['OR' => [
                    'MdlUser.idnumber like "%'.$this->request->data['idnumber'].'%"',
                    'MdlUser.firstname like "%'.$this->request->data['idnumber'].'%"',
                    'MdlUser.lastname like "%'.$this->request->data['idnumber'].'%"']]];
            }

            $alternativehost = $this->request->data('alternativehost');
            if(!is_null($alternativehost) && $alternativehost != "0"){
                array_push($conditions, 'EcmCarrinho.ecm_alternative_host_id='.$alternativehost);
            }

            $user = $this->request->data('user');
            if(!is_null($user)){
                if ($user == "Sim") {
                    array_push($conditions, 'EcmCarrinho.mdl_user_id IS NULL');
                } else if ($user == "Não") {
                    array_push($conditions, 'EcmCarrinho.mdl_user_id IS NOT NULL');
                }
            }

            $status = $this->request->data('status');
            if(!is_null($status) && $status != "Todos"){
                //array_push($conditions, 'EcmCarrinho.status="'.$this->request->data['status'].'"');
                $conditions['EcmCarrinho.status'] = $status;
            }
            if(!empty($this->request->data['inicio'])){
                $inicio = \DateTime::createFromFormat('j/m/Y', $this->request->data['inicio']);
                array_push($conditions, 'EcmCarrinho.data >= "'.$inicio->format("Y-m-d").'"');
            }
            if(!empty($this->request->data['fim'])){
                $fim = \DateTime::createFromFormat('j/m/Y', $this->request->data['fim']);
                array_push($conditions, 'EcmCarrinho.data <= "'.$fim->format("Y-m-d H:i:s").'"');
            }

            $ecmProduto = $this->request->data('ecm_produto');
            if(!is_null($ecmProduto) && $ecmProduto['_ids'] != ""){
                $conditions['EcmCarrinho.id IN'] = $this->EcmCarrinho->EcmCarrinhoItem->find('list', [
                    'valueField' => ['ecm_carrinho_id'], 'conditions' => [
                        'EcmCarrinhoItem.ecm_produto_id IN' => $ecmProduto['_ids']
                    ]])->toArray();
            }
        }

        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'EcmAlternativeHost',
                'EcmCarrinhoItem' => [ 'EcmProduto' => ['EcmTipoProduto']],
                'EcmVenda' => ['EcmTipoPagamento',
                    'foreignKey' => false,
                    'joinType' => 'LEFT',
                    'conditions' => 'EcmVenda.ecm_carrinho_id = EcmCarrinho.id'],
                'MdlUser' => $conditionsUser
            ],
            'order' => ['EcmCarrinho.data' => 'desc']
        ];
        $ecmCarrinho = $this->paginate($this->EcmCarrinho);

        $ecmAlternativeHost = $this->EcmCarrinho->EcmAlternativeHost->find('list', [
                'keyField' => 'id', 'valueField' => 'fullname'])->toArray();
        $ecmAlternativeHost[0] = "Todas Entidades";
        ksort($ecmAlternativeHost);

        $user = ['Todos' => 'Todos', 'Sim' => 'Sim', 'Não' => 'Não'];

        $status = ['Todos' => 'Todos', 'Finalizado' => 'Finalizado',
                'Em Aberto' => 'Em Aberto', 'Cancelado' => 'Cancelado'];

        $conditions['EcmCarrinhoItem.status'] = 'Adicionado';
        $total = $this->EcmCarrinho->find()->innerJoinWith('EcmCarrinhoItem')->where($conditions)
                    ->select(['total' => 'SUM(EcmCarrinhoItem.valor_produto_desconto * EcmCarrinhoItem.quantidade)']);

        if(isset($conditionsUser['conditions'])){
            $total = $total->innerJoinWith('MdlUser', function ($q) use ($conditionsUser) {
                return $q->where($conditionsUser['conditions']);
            });
        }

        $total = $total->first()->total;

        $produtos = $this->EcmCarrinho->EcmCarrinhoItem->EcmProduto->find('list', [
            'keyField' => 'id', 'valueField' => 'nome'
        ]);

        $this->set(compact('ecmCarrinho', 'ecmAlternativeHost', 'user', 'status', 'total', 'produtos'));
        $this->set('_serialize', ['ecmCarrinho', 'ecmAlternativeHost', 'user', 'status', 'total', 'produtos']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Carrinho id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmCarrinho = $this->EcmCarrinho->get($id, [
            'contain' => ['MdlUser' => ['joinType' => 'LEFT'],
                'EcmCarrinhoItem' => [
                    'EcmPromocao',
                    'EcmCupom',
                    'EcmProduto' => ['EcmTipoProduto'],
                    'EcmCursoPresencialTurma' => [
                        'EcmCursoPresencialData' => [
                            'EcmCursoPresencialLocal' => [
                                'MdlCidade' => ['MdlEstado']
                            ]
                        ]
                    ]
                ],
                'EcmVenda' => [
                    'EcmTipoPagamento',
                    'EcmVendaStatus' => ['strategy' => 'subquery'],
                    'foreignKey' => false,
                    'joinType' => 'LEFT',
                    'conditions' => 'EcmVenda.ecm_carrinho_id = EcmCarrinho.id']
            ]
        ]);

        $this->set('ecmCarrinho', $ecmCarrinho);
        $this->set('_serialize', ['ecmCarrinho']);
    }
}