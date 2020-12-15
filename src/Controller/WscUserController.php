<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 28/06/2016
 * Time: 13:15
 */

namespace App\Controller;
use ADmad\JwtAuth\Auth\JwtAuthenticate;
use App\Lib\DetectSystem\DetectSystem;
use App\Lib\DetectSystem\Mobile_Detect;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Carrinho\Model\Entity\EcmCarrinho;
use App\Auth\AESPasswordHasher;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Firebase\JWT\JWT;
use Produto\Model\Entity\EcmTipoProduto;
use Repasse\Model\Entity\EcmRepasse;
use App\Model\Entity\MdlUser;


class WscUserController  extends WscController
{
    use MailerAwareTrait;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow('logout');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    public function login()
    {
        $validaAlternativeHost = $this->validaAlternativeHost();

        $retorno = ['sucesso' => false, 'mensagem' => __('Usuário ou senha inválido, tente novamente'), 'erro' => 'nao-autorizado'];
        if(is_numeric($validaAlternativeHost)) {
            if ($this->request->is('post')) {
                if(isset($this->request->data['altoqi']) && $this->request->data['altoqi']){
                    $this->loadComponent('BaseExterna');
                    if ($user = $this->BaseExterna->buscarUsuario($this->request->data['username'], $this->request->data['password'])){
                        $mdlUser = $this->buscarUsuarioQiSat($user);
                        $retorno['user'] = $user;
                        $retorno['mdlUser'] = $mdlUser;
                        if ($mdlUser){
                            $aesHash = new AESPasswordHasher();
                            $this->request->data['password'] = $aesHash->decrypt($mdlUser->password);
                            if(!is_numeric($mdlUser->username))
                                $this->request->data['username'] = $mdlUser->email;
                        } else {
                            $this->BaseExterna->importarUsuario($user['ChaveAltoQi'], $this->request->data['password']);
                        }
                    }
                }
                if(!filter_var($this->request->data['username'], FILTER_VALIDATE_EMAIL)===false){
                    $this->Auth->config('authenticate', [
                        'Form' => [
                            'fields' => ['username' => 'email', 'password' => 'password']
                        ]
                    ]);
                    $this->Auth->constructAuthenticate();
                    $this->request->data['email'] = $this->request->data['username'];
                    unset($this->request->data['username']);
                }
                $user = $this->Auth->identify();

                if ($user && $user['confirmed']) {
                    $this->criarCookie($user);

                    $this->loadModel('EcmPermissao');
                    $this->loadModel('EcmGrupoPermissao');

                    $acessoTotal = $this->EcmGrupoPermissao->verificarAcessoTotalUsuario($user['id']);

                    if ($acessoTotal == 0) {
                        $permissoes = $this->EcmPermissao->buscarPermissoesUsuario($user['id']);
                        $user['permissoes'] = $permissoes;
                    } else {
                        $user['permissoes'] = ['acesso_total' => true];
                    }

                    $this->request->session()->write('alternativeHostId', $validaAlternativeHost);

                    $this->loadModel('MdlUser');
                    $usuario = $this->MdlUser->find()
                        ->select([
                            'MdlUser.id', 'username', 'idnumber', 'firstname', 'lastname',
                            'email', 'phone1', 'phone2', 'address', 'city', 'country',
                            'picture'
                        ])
                        ->contain(
                            [
                                'MdlUserDados' => [
                                    'fields' => [
                                        'numero' => 'numero', 'numero_crea' => 'numero_crea',
                                        'tipousuario' => 'tipousuario', 'email_oferta' => 'email_oferta',
                                        'email_andamento' => 'email_andamento', 'email_mensagem_privada' => 'email_mensagem_privada',
                                        'email_ausente' => 'email_ausente', 'email_suporte' => 'email_suporte',
                                        'ligacao_lancamentos' => 'ligacao_lancamentos', 'ligacao_pagamento' => 'ligacao_pagamento',
                                        'sms_informacoes' => 'sms_informacoes', 'sms_lancamentos' => 'sms_lancamentos',
                                        'funcionarioqisat' => 'funcionarioqisat'
                                    ]
                                ],
                                'MdlUserEcmAlternativeHost' => [
                                    'fields' =>[
                                        'id' => 'ecm_alternative_host_id', 'mdl_user_id', 'numero',
                                        'adimplente', 'confirmado'
                                    ],
                                    'EcmAlternativeHost'=> [
                                        'fields' =>[
                                            'shortname' => 'shortname', 'fullname' => 'fullname',
                                            'host' => 'host'
                                        ]
                                    ]
                                ]
                            ]
                        )
                        ->where(['MdlUser.id' => $user['id']])
                        ->first()
                        ->toArray();


                    if(!empty($usuario['mdl_user_ecm_alternative_host'])){
                        $usuario['entidades'] = $usuario['mdl_user_ecm_alternative_host'];
                    }
                    unset($usuario['mdl_user_ecm_alternative_host']);

                    if($this->MdlUser->MdlUserEndereco->exists(['id' => $user['id']]))
                        $usuario['endereco'] = $this->MdlUser->MdlUserEndereco->get($user['id'])->toArray();

                    $this->loadModel('Configuracao.EcmConfig');
                    if($usuario['picture'] > 0){
                        $moodle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_moodle'])->first()->valor;
                        $usuario['picture'] = 'http://' . $moodle . '/user/pix.php/' . $usuario['id'] . '/f1';
                    }

                    $this->Auth->setUser($user);
                    $this->setUsuarioCarrinho();
                    //$this->validarCupomCarrinho();
                    $ecmCarrinho = $this->request->session()->read('carrinho');

                    if(!is_null($ecmCarrinho) && is_null($ecmCarrinho->mdl_user_modified_id))
                        $this->buscarCupomLogin($user);

                    unset($retorno['mensagem']);

                    $retorno = ['sucesso' => true, 'usuario' => $usuario];

                    $this->createToken($user['id'], $user['username'], true);

                    /**
                     * Registrar log browser
                     */
                    $detect = new DetectSystem();
                    $mobile_detect = new Mobile_Detect();
                    $agente = '';
                    if(isset($_SERVER['HTTP_USER_AGENT']))
                        $agente = $_SERVER['HTTP_USER_AGENT'];
                    $objetoJson = '';
                    $navegador = $detect->getBrowser();
                    $varsaonavegador = $detect->getBrowserVersion();
                    $sistema = $detect->getOS();
                    $versaosistema = $detect->getOSVersion();
                    $bits = '';
                    $mobile = 'no';
                    /*Verifica se � Mobile*/
                    if($mobile_detect->isMobile()){
                        $string = $_SERVER['HTTP_USER_AGENT'];
                        $string = substr($string, stripos($string, 'Android'));
                        $string = substr($string, 0, stripos($string, ';'));
                        $string = strrchr($string, " ");
                        if(!empty(trim($string)))
                            $versaosistema = trim($string);
                        $mobile = 'yes';
                    }else{
                        /*Desktop*/
                        //if(isset($_SERVER['HTTP_USER_AGENT']))
                            //$objetoJson = json_encode(get_browser(null, true));
                        $bits = $detect->getOSBits();
                    }
                    /*$configLog = null;
                    if($configLog = current(get_records_sql("SHOW TABLE STATUS LIKE '{$CFG->prefix}log'"))){
                        $configLog = (int)$configLog->Auto_increment - 1;
                    }*/
                    $record = array();
                    $record['userid'] = $user['id'];
                    //$record['logid'] = $configLog;
                    $record['browser'] = $navegador;
                    $record['browserversion'] = $varsaonavegador;
                    $record['system'] = $sistema;
                    $record['systemversion'] = $versaosistema;
                    $record['systembits'] = $bits;
                    $record['mobile'] = $mobile;
                    $record['objeto_json'] = $objetoJson;
                    $record['user_agent'] = $agente;
                    $record['alternativehostid'] = $this->request->session()->read('alternativeHostId');
                    $record['timecreated'] = time();

                    $sql = 'INSERT INTO mdl_log_browser (userid, browser, browserversion, system, systemversion,
                              systembits, mobile, objeto_json, user_agent, alternativehostid, timecreated)
                              VALUES (:userid, :browser, :browserversion, :system, :systemversion, :systembits,
                              :mobile, :objeto_json, :user_agent, :alternativehostid, :timecreated)';

                    $conn = ConnectionManager::get('default');

                    if(!$conn->execute($sql, $record)){
                        $corpoEmail = 'ERRO: ao salvar informacoes do usuario na log_browser. User=' . $user['id'];
                        $corpoEmail .= ', Data do erro: ' . date('d/m/Y H:i:s', time()) . '<br><br><pre> Objeto : ';
                        $corpoEmail .= var_export($record, true) . '</pre>';

                        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'supportemail'])->first()->valor;
                        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

                        $email = new Email('default');
                        $email->from([$fromEmail => $fromEmailTitle])
                                ->to($adminEmail)
                                ->emailFormat('html')
                                ->template('default')
                                ->subject('Erro ao salvar registro na log browser')
                                ->send($corpoEmail);
                    }
                } else {
                    $this->logout();
                    if($user){
                        $retorno['mensagem'] = __('Favor, confirme seu usuário');
                        $retorno['erro'] = 'nao-confirmado';
                    }
                }
            }
        }else{
            $retorno = $validaAlternativeHost;
        }

        $this->set(compact('retorno'));
        $this->set('_serialize', ['retorno']);
    }

    private function buscarUsuarioQiSat($user){
        $this->loadModel('MdlUser');

        if($mdlUser = $this->MdlUser->find()->where(['username' => $user['ChaveAltoQi']])
                ->orWhere(['idnumber' => $user['ChaveAltoQi']])->first())
            return $mdlUser;

        if(!is_null($user['CNPJCPF']))
            if($mdlUser = $this->MdlUser->find()->matching('MdlUserDados', function($q)use($user){
                return $q->where(['numero' => $user['CNPJCPF']]);
            })->first())
                return $mdlUser;

        if(!is_null($user['Email']))
            if($mdlUser = $this->MdlUser->find()->where(['email' => $user['Email']])->first())
                return $mdlUser;

        return false;
    }

    private function setUsuarioCarrinho(){
        $ecmCarrinho = $this->request->session()->read('carrinho');

        if(!is_null($ecmCarrinho)) {
            $this->loadModel('Carrinho.EcmCarrinho');
            $this->loadModel('MdlUser');

            $ecmCarrinho->set('mdl_user_id', $this->Auth->user('id'));
            $usuario = $this->MdlUser->get($this->Auth->user('id'));

            $ecmCarrinho->set('mdl_user', $usuario);

            $this->request->session()->write('carrinho', $ecmCarrinho);
            $this->EcmCarrinho->save($ecmCarrinho);
        }
    }

    public function buscarCupomLogin($user){
        $ecmCarrinho = $this->request->session()->read('carrinho');

        if(!is_null($ecmCarrinho) && !is_null($ecmCarrinho->ecm_carrinho_item)) {
            $this->loadModel('MdlUser');
            $this->loadModel('Cupom.EcmCupom');
            $this->loadModel('Promocao.EcmPromocao');

            if(is_array($user))
                $user = $this->MdlUser->newEntity($user);

            $cupons = $this->EcmCupom->buscarCupons($user);

            $cupom = $this->EcmCupom->buscarMelhorCupom($cupons, $ecmCarrinho, $user->id);

            $carrinho = clone $ecmCarrinho;
            foreach ($carrinho->ecm_carrinho_item as $item) {
                $item = clone $item;
                if (!is_null($item->ecm_cupom)) {
                    unset($item->ecm_cupom);
                    $item->ecm_cupom_id = null;
                    $item->set('valor_produto_desconto', $item->valor_produto);
                }
                if(isset($item->ecm_promocao)){
                    $desconto = $ecmCarrinho->verificarDesconto($item->ecm_produto, [$item->ecm_promocao], $cupom);
                } else {
                    $listaPromocao = $this->EcmPromocao->buscaPromocoesAtivasUsuario($item->ecm_produto, $ecmCarrinho->get('mdl_user_id'));
                    $desconto = $ecmCarrinho->verificarDesconto($item->ecm_produto, $listaPromocao, $cupom);
                }
                if (is_null($desconto) || !array_key_exists("promocao", $desconto)) {
                    unset($item->ecm_promocao);
                    $item->ecm_promocao_id = null;
                    $item->set('valor_produto_desconto', $item->valor_produto);
                }
                if(!is_null($desconto)){
                    if(array_key_exists("promocao", $desconto)){
                        $item->set('ecm_promocao', $desconto['promocao']);
                        $item->set('ecm_promocao_id', $desconto['promocao']->id);
                    }
                    if(array_key_exists("cupom", $desconto)){
                        $item->set('ecm_cupom', $desconto['cupom']);
                        $item->set('ecm_cupom_id', $desconto['cupom']->id);
                    }
                    $item->set('valor_produto_desconto', $desconto['valorTotal']);
                }
                $carrinho->addItem($item);
            }

            $cupomAtual = $this->request->session()->read('cupom');

            if($carrinho->calcularTotal() < $ecmCarrinho->calcularTotal() ||
                    (!is_null($cupomAtual) && !$this->EcmCupom->verificarUso($cupomAtual, $user->id))){

                if(!is_null($cupom)) {
                    $this->request->session()->write('cupom', $cupom);
                } else if ($this->request->session()->check('cupom')) {
                    $this->request->session()->delete('cupom');
                }

                $this->request->session()->write('carrinho', $carrinho);
                $this->loadModel('Carrinho.EcmCarrinho');
                $this->EcmCarrinho->save($carrinho);
            }
        }
    }

    public function logout()
    {
        $this->Auth->logout();

        if ($this->request->session()->check('cupom')){
            $cupom = $this->request->session()->read('cupom');
            if ($cupom->tipo_aquisicao != 3) {
                $ecmCarrinho = $this->request->session()->read('carrinho');
                foreach ($ecmCarrinho->ecm_carrinho_item as $item) {
                    if (!is_null($item->ecm_cupom)) {
                        unset($item->ecm_cupom);
                        $item->ecm_cupom_id = null;
                        $item->set('valor_produto_desconto', $item->valor_produto);
                    }
                    $desconto = null;
                    if(isset($item->ecm_promocao)){
                        $desconto = $ecmCarrinho->verificarDesconto($item->ecm_produto, [$item->ecm_promocao]);
                    }
                    if(!is_null($desconto)){
                        if(array_key_exists("promocao", $desconto)){
                            $item->set('ecm_promocao', $desconto['promocao']);
                            $item->set('ecm_promocao_id', $desconto['promocao']->id);
                        }
                        $item->set('valor_produto_desconto', $desconto['valorTotal']);
                    }
                    $ecmCarrinho->addItem($item);
                }
                $this->request->session()->delete('cupom');
                $this->request->session()->write('carrinho', $ecmCarrinho);
                $this->loadModel('Carrinho.EcmCarrinho');
                $this->EcmCarrinho->save($ecmCarrinho);
            }
        }

        $retorno = ['sucesso' => true];
        $this->set(compact('retorno'));
    }

    public function matriculas($id = null)
    {
        set_time_limit(120);
        $retorno = ['sucesso' => false, 'mensagem' => 'Id do usuario não informado'];
        if(!is_numeric($id))
            $id = $this->request->data('id');
        if(!is_numeric($id))
            $id = $this->request->session()->read('Auth.User.id');
        if(is_numeric($id)) {
            $this->loadModel('MdlUser');
            if ($this->MdlUser->exists(['MdlUser.id' => $id])) {
                $mdlUser = $this->MdlUser->find('all')
                    ->contain(['MdlUserEnrolments' => function($q) use ($id) {
                        return $q->contain(['MdlEnrol' => function ($q) use ($id) {
                            return $q->contain(['MdlCourse' => function ($q) {
                                return $q->contain(['EcmProduto' => function ($q) {
                                    return $q->contain(['EcmInstrutor' => function ($q) {
                                        return $q->contain(['MdlUser' =>
                                            ['fields' => ['nome' => 'CONCAT(firstname," ",lastname)']]
                                        ])->select(['userid' => 'mdl_user_id']);
                                    }, 'EcmImagem', 'EcmTipoProduto' => function ($q) {
                                        return $q->select(['EcmTipoProduto.id', 'EcmTipoProduto.nome']);
                                    }])->select(['id', 'nome', 'sigla', 'refcurso'])->where(['refcurso' => 'true']);
                                }])->select(['id', 'curso' => 'fullname', 'category']);
                            }])->select(['roleid', 'enrolperiod', 'MdlEnrol.courseid'])
                            ->contain(['MdlGroups' => function($q) use ($id) {
                                return $q->contain(['MdlGroupsMembers' => function($q) use ($id) {
                                    return $q->select(['id', 'groupid', 'MdlGroupsMembers.userid'])
                                        ->where(['MdlGroupsMembers.userid' => $id]);
                                }])->select(['id', 'courseid', 'mdl_fase_id']);
                            }]);
                        }])->select(['id', 'status', 'userid', 'timestart', 'timeend']);
                    }])
                    ->contain(['MdlUserEcmAlternativeHost' => function($q){
                        return $q->contain(['EcmAlternativeHost' => function($q){
                            return $q->select(['id', 'MdlUserEcmAlternativeHost.mdl_user_id']);
                        }]);
                    }])
                    ->select(['id'])->where(['id' => $id])->first();

                $this->loadModel('Configuracao.EcmConfig');
                $this->loadModel('WebService.MdlCertificate');
                $this->loadModel('Imagem.EcmImagem');
                $moodle = $this->EcmConfig->find()->where(['nome' => 'dominio_acesso_moodle'])->first()->valor;
                $retorno = ['sucesso' => true];
                foreach($mdlUser['mdl_user_enrolments'] as $user_enrolments){
                    unset($cursoIndividual);
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
                    if(!isset($cursoIndividual) || $cursoIndividual){
                        $statusCurso = $this->MdlUser->verificaStatusCurso($user_enrolments, $id);
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
                            if(!empty($ecm_produto['ecm_instrutor']))
                                $user_enrolments['instrutores'] = $ecm_produto['ecm_instrutor'];
                            if(!empty($ecm_produto['ecm_tipo_produto'])) {
                                $user_enrolments['produto']['categorias'] = $ecm_produto['ecm_tipo_produto'];
                                unset($user_enrolments['produto']['ecm_tipo_produto']);
                            }

                            /**
                             * Melhoria no retorno das imagens e do nome do capitulo da série
                             */
                            if(isset($user_enrolments['produto'])) {
                                foreach ($user_enrolments['produto']['categorias'] as $categoria) {
                                    if ($categoria->id == 33 || $categoria->id == 41) {
                                        $cursoid = $user_enrolments['cursoid'];
                                        $ecm_produto['ecm_imagem'] = $this->EcmImagem->find()
                                            ->matching('EcmProduto.MdlCourse', function ($q) use ($cursoid) {
                                                return $q->where(['MdlCourse.id' => $cursoid]);
                                            })
                                            ->matching('EcmProduto.EcmTipoProduto', function ($q) {
                                                return $q->where(['EcmTipoProduto.id' => 32]);
                                            })
                                            ->where(['EcmImagem.descricao' => 'Imagens - Capa'])->toArray();
                                        foreach ($ecm_produto['ecm_imagem'] as $imagem) {
                                            unset($imagem['_matchingData']);
                                        }
                                    }
                                    if ($categoria->id == 33) {
                                        $pos = strrpos($ecm_produto['sigla'], "_C");
                                        if (is_numeric($pos)) {
                                            /*$user_enrolments['produto']['nome'] = 'Capítulo ' .
                                                substr($ecm_produto['sigla'], $pos+2) . ': ' . $ecm_produto['nome'];*/
                                            $user_enrolments['produto']['capitulo'] = substr($ecm_produto['sigla'], $pos+2);
                                        }
                                    }
                                }
                            }


                            if(!empty($ecm_produto['ecm_imagem'])){
                                $user_enrolments['imagem'] = $ecm_produto['ecm_imagem'];
                                foreach($user_enrolments['imagem'] as $imagem) {
                                    if($imagem['descricao'] == 'Imagens - Capa'){
                                        $user_enrolments['imagem'] = \Cake\Routing\Router::url('/upload/' . $imagem['src'], true);
                                        break;
                                    }
                                }
                                if(is_array($user_enrolments['imagem']))
                                    $user_enrolments['imagem'] = null;
                            }

                        }
                        /**
                         * view : links.view+"?id="+matricula.courseid+"&instance="+matricula.instance,
                         * forum : links.forum+"?id="+matricula.courseid+"&instance="+matricula.instance,
                         * biblioteca : links.biblioteca+"?id="+matricula.biblioteca+"&instance="+matricula.instance,
                         * contrato : links.contrato+"?id="+_id+"&course="+matricula.courseid+"&instance="+matricula.instance,
                         * tira_duvidas : links.tira_duvidas+"?cid="+matricula.courseid+"&instance="+matricula.instance+"&bid="+matricula.tira_duvidas+"8&rcp=0"
                         */
                        $user_enrolments['view'] = '#';
                        if($user_enrolments['status'] == "Liberado para Acesso")
                            $user_enrolments['view'] = 'https://'.$moodle.'/course/view.php?id='.$user_enrolments['cursoid'];

                        $this->loadModel('WebService.MdlCourseModules');
                        $mdlCourseModules = $this->MdlCourseModules->find()
                            ->contain(['MdlModules' => function($q){
                                return $q->orWhere(['MdlModules.name' => 'forum']);
                            }])->contain(['MdlForum' => function($q) use ($user_enrolments){
                                return $q->where(['MdlForum.course' => $user_enrolments['cursoid']]);
                            }])->first();
                        if(!is_null($mdlCourseModules))
                            $user_enrolments['forum'] = 'https://'.$moodle.'/mod/forum/view.php?id='.$mdlCourseModules->id;

                        $mdlCourseModules = $this->MdlCourseModules->find()
                            ->contain(['MdlModules' => function($q){
                                return $q->orWhere(['MdlModules.name' => 'folder']);
                            }])->contain(['MdlFolder' => function($q) use ($user_enrolments){
                                return $q->where(['MdlFolder.course' => $user_enrolments['cursoid'], 'MdlFolder.name' => 'Biblioteca']);
                            }])->first();
                        if(!is_null($mdlCourseModules))
                            $user_enrolments['biblioteca'] = 'https://'.$moodle.'/mod/folder/view.php?id='.$mdlCourseModules->id;

                        $user_enrolments['tira_duvidas'] = 'https://'.$moodle.'/blocks/tira_duvidas/historico/historico.php?cid='.$user_enrolments['cursoid'];

                        $certificado = $this->MdlCertificate->find('all',[
                            'fields' => ['MdlCertificateIssues.timecreated']
                        ])
                            ->contain(['MdlCertificateIssues'])
                            ->where([
                                'MdlCertificate.course' => $user_enrolments['cursoid'],
                                'MdlCertificateIssues.userid' => $id
                            ])->first();

                        $user_enrolments['data_conclusao'] = null;

                        if($certificado)
                            $user_enrolments['data_conclusao'] = $certificado->MdlCertificateIssues->timecreated;

                        unset($user_enrolments['mdl_enrol']);
                        $retorno['matriculas'][] = $user_enrolments;
                    }
                }
            }
        }
        $this->set(compact('retorno'));
        $this->set('_serialize', ['retorno']);
    }

    /**
     * @param null $id do curso
     *
     * Web Service para aceite de contrato de matricula
     */
    public function aceiteContrato($id = null)
    {
        $retorno = ['sucesso' => false, 'mensagem' => 'Usuario não autenticado'];
        if($userid = $this->request->session()->read('Auth.User.id')) {
            $retorno['mensagem'] = 'Curso não informado';
            if (!is_numeric($id))
                $id = $this->request->data('id');
            if (is_numeric($id)) {
                $this->loadModel('MdlUser');
                $mdlRoleAssignments = $this->MdlUser->MdlRoleAssignments->find('all')
                    ->contain(['MdlContext'])->where(['userid' => $userid,'instanceid' => $id])->first();
                $mdlRoleAssignments->roleid = 5;
                if ($this->MdlUser->MdlRoleAssignments->save($mdlRoleAssignments)) {
                    $retorno = ['sucesso' => true, 'mensagem' => 'Contrato aceito com sucesso!'];
                    $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find('all')
                        ->contain(['MdlEnrol'])->where(['userid' => $userid, 'courseid' => $id])->first();
                    $this->loadModel('EcmLogContrato');
                    $ecmLogContrato = $this->EcmLogContrato->newEntity();
                    $ecmLogContrato->mdl_user_enrolments_id = $mdlUserEnrolments->id;
                    $ecmLogContrato->timecreated = time();
                    $this->EcmLogContrato->save($ecmLogContrato);
                }
            }
        }
        $this->set(compact('retorno'));
        $this->set('_serialize', ['retorno']);
    }

    public function user($id = null)
    {
        $retorno = ['sucesso' => false, 'mensagem' => __('Usuário não encontrado')];
        if(!is_numeric($id))
            $id = $this->request->data('id');
        if(!is_numeric($id))
            $id = $this->request->session()->read('Auth.User.id');

        if(is_numeric($id)) {
            $this->loadModel('MdlUser');
            if ($this->MdlUser->exists(['MdlUser.id' => $id])) {
                $mdlUser = $this->MdlUser->get($id,
                    [
                        'fields' => [
                        'id', 'username', 'password', 'idnumber', 'firstname', 'lastname', 'email',
                        'imagealt', 'picture', 'phone1', 'phone2', 'country', 'city', 'address'
                        ],
                        'contain' => [
                            'MdlUserDados' => [
                                'fields' => [
                                    'numero' => 'numero', 'numero_crea' => 'numero_crea',
                                    'tipousuario' => 'tipousuario', 'email_oferta' => 'email_oferta',
                                    'email_andamento' => 'email_andamento', 'email_mensagem_privada' => 'email_mensagem_privada',
                                    'email_ausente' => 'email_ausente', 'email_suporte' => 'email_suporte',
                                    'ligacao_lancamentos' => 'ligacao_lancamentos', 'ligacao_pagamento' => 'ligacao_pagamento',
                                    'sms_informacoes' => 'sms_informacoes', 'sms_lancamentos' => 'sms_lancamentos',
                                    'funcionarioqisat' => 'funcionarioqisat'
                                ]
                            ],
                            'MdlUserEndereco' => [
                                'fields' => ['number', 'complement', 'district', 'state', 'cep']
                            ],
                            'MdlUserEcmAlternativeHost' => [
                                'fields' =>[
                                    'id' => 'ecm_alternative_host_id', 'mdl_user_id', 'numero',
                                    'adimplente', 'confirmado'
                                ],
                                'EcmAlternativeHost'=> [
                                    'fields' =>[
                                        'shortname' => 'shortname', 'fullname' => 'fullname',
                                        'host' => 'host'
                                    ]
                                ]
                            ]
                        ]
                    ]
                );

                if(isset($mdlUser->mdl_user_endereco)) {
                    $mdlUser->endereco = $mdlUser->mdl_user_endereco;
                    $mdlUser->endereco->logradouro = $mdlUser->address;
                }

                if(!empty($mdlUser->mdl_user_ecm_alternative_host)){
                    $mdlUser->entidades = $mdlUser->mdl_user_ecm_alternative_host;
                }
                unset($mdlUser->mdl_user_ecm_alternative_host);

                unset($mdlUser->mdl_user_endereco);
                unset($mdlUser->address);
                if($mdlUser->picture > 0){
                    $this->loadModel('Configuracao.EcmConfig');
                    $moodle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_moodle'])->first()->valor;
                    $mdlUser->picture = 'https://' . $moodle . '/user/pix.php/' . $mdlUser->id . '/f1';
                }
                $retorno = ['sucesso' => true, 'user' => $mdlUser];
            }
        }

        $this->set(compact('retorno'));
    }

    /**
     * Testar função
     */
    public function createUser()
    {
        $user = null;
        $atualizacao = false;
        if (!$this->request->is('post')) {
            $retorno = ['sucesso' => false, 'mensagem' => __('Este Web Service não aceita esse tipo de requisição'), 'erro' => 1];
        } else if (isset($this->Auth->user()['id']) && isset($this->request->data['id']) &&
                $this->Auth->user()['id'] != $this->request->data['id']) {
            $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possivel alterar o usuário'), 'erro' => 2];
        } else {
            $this->loadModel('MdlUser');
            $this->loadComponent('BaseExterna');
            $numeroValido = false;
            $emailValido = false;
            
            if (!isset($this->request->data['id']) && isset($this->request->data['email']) &&
                (isset($this->request->data['cpf']) || isset($this->request->data['cnpj']) || isset($this->request->data['numero']))
            ) {
                $email = isset($this->request->data['email']) ? $this->request->data['email'] : '';
                $emailValido = filter_var($email, FILTER_VALIDATE_EMAIL);
                
                if (isset($this->request->data['cpf'])) {
                    $cpf = $this->request->data['cpf'];

                    $this->request->data['numero'] = $cpf;
                    unset($this->request->data['cpf']);

                    $numeroValido = MdlUser::validarCPF($cpf);
                } else if (isset($this->request->data['cnpj'])) {
                    $cnpj = $this->request->data['cnpj'];

                    $this->request->data['numero'] = $cnpj;
                    unset($this->request->data['cnpj']);
                    
                    $numeroValido = MdlUser::validarCNPJ($cnpj);
                }

                $numero = isset($this->request->data['numero']) ? $this->request->data['numero'] : '';
                if ($user = $this->BaseExterna->verificarUsuario($email, $numero)) {
                    if ($user !== true)
                        $this->request->data['id'] = $this->BaseExterna->importarUsuario($user['ChaveAltoQi'], $user['SenhaInternet'])->id;
                }
            }
            
            if($user !== true && $numeroValido && $emailValido) {
                if (($id = $this->request->data('id')) && $this->MdlUser->exists(['MdlUser.id' => $id])) {
                    $mdlUser = $this->MdlUser->get($id);
                    $this->request->data['timemodified'] = time();
                    if (isset($this->request->data['timecreated']))
                        unset($this->request->data['timecreated']);
                } else {
                    $mdlUser = $this->MdlUser->newEntity();
                    $this->request->data['timecreated'] = time();
                    if (isset($this->request->data['timemodified']))
                        unset($this->request->data['timemodified']);
                }

                if (isset($this->request->data['password'])) {
                    $this->request->data['auth'] = "aesauth";
                    $aes = new AESPasswordHasher();
                    $this->request->data['password'] = $aes->hash($this->request->data['password']);
                }
                if (!isset($this->request->data['lang']))
                    $this->request->data['lang'] = "pt_br";
                if (!isset($this->request->data['timezone']))
                    $this->request->data['timezone'] = "America/Sao_Paulo";

                if (isset($this->request->data['picture'])) {
                    $picture = $this->request->data['picture'];
                    unset($this->request->data['picture']);
                }

                $entidadesUsuario = $this->request->data('entidades');
                unset($this->request->data['entidades']);
                if(is_null($this->request->data('id'))) {
                    $this->request->data['username'] = $this->request->data['email'];
                }
                $mdlUser = $this->MdlUser->patchEntity($mdlUser, $this->request->data);

                $this->loadModel('Entidade.EcmAlternativeHost');

                if (!is_null($entidadesUsuario)) {
                    $listaEntidades = [];
                    foreach ($entidadesUsuario as $entidade) {
                        if ($this->EcmAlternativeHost->exists(['id' => $entidade['entidade']])) {
                            $userAlternativeHost = $this->MdlUser->MdlUserEcmAlternativeHost->newEntity();
                            $userAlternativeHost->set('ecm_alternative_host_id', $entidade['entidade']);
                            $userAlternativeHost->set('numero', $entidade['numero']);
                            $listaEntidades[] = $userAlternativeHost;
                        }
                    }
                    $mdlUser->set('mdl_user_ecm_alternative_host', $listaEntidades);
                }

                $mdlUser->set('mnethostid', 1);
                $mdlUser->set('confirmed', 1);

                if ($this->MdlUser->save($mdlUser)) {
                    $retorno = ['sucesso' => true, 'usuario' => $mdlUser];
                    $atualizacao = isset($this->request->data['id']);
                    unset($this->request->data['id']);

                    if (isset($picture)) {
                        $this->loadModel('Configuracao.EcmConfig');
                        $moodledata = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'diretorio_moodledata'])->first();
                        $this->MdlUser->enviarImagem($picture, $mdlUser, $moodledata->valor);
                    }

                    if ($this->MdlUser->MdlUserEndereco->exists(['MdlUserEndereco.id' => $mdlUser->id])) {
                        $mdlUserEndereco = $this->MdlUser->MdlUserEndereco->get($mdlUser->id);
                    } else {
                        $mdlUserEndereco = $this->MdlUser->MdlUserEndereco->newEntity();
                        $mdlUserEndereco->id = $mdlUser->id;
                    }
                    $mdlUserEndereco = $this->MdlUser->MdlUserEndereco->patchEntity($mdlUserEndereco, $this->request->data);
                    if ($this->MdlUser->MdlUserEndereco->save($mdlUserEndereco)) {
                        $mdlUser->mdl_user_endereco = $mdlUserEndereco;
                    } else {
                        $retorno['mensagem'] = __('Não foi possivel cadastrar/alterar o endereço do usuário.');
                        $retorno['erro'] = 3;
                    }

                    if ($this->MdlUser->MdlUserDados->exists(['mdl_user_id' => $mdlUser->id])) {
                        $mdlUserDados = $this->MdlUser->MdlUserDados->find('all', [
                            'conditions' => ['mdl_user_id' => $mdlUser->id]])->first();
                    } else {
                        $mdlUserDados = $this->MdlUser->MdlUserDados->newEntity();
                        $mdlUserDados->mdl_user_id = $mdlUser->id;
                    }
                    $mdlUserDados = $this->MdlUser->MdlUserDados->patchEntity($mdlUserDados, $this->request->data);
                    if ($this->MdlUser->MdlUserDados->save($mdlUserDados)) {
                        $mdlUser->mdl_user_dados = $mdlUserDados;
                    } else {
                        if (isset($retorno['endereco']))
                            $retorno['mensagem'] = "";
                        else
                            $retorno['mensagem'] .= "\n";

                        $retorno['mensagem'] .= __('Não foi possivel cadastrar/alterar os dados do usuário.');
                        $retorno['erro'] = 4;
                    }

                    $idAlternativeHost = $this->request->session()->read('alternativeHostId');
                    if (!isset($idAlternativeHost) || empty($idAlternativeHost))
                        $idAlternativeHost = 1;

                    $origem = $this->EcmAlternativeHost->get($idAlternativeHost)->codigoorigemaltoqi;

                    //$this->enviarToken($mdlUser);
                    if(empty($mdlUser->firstaccess))
                        $this->enviarConfirmacaoCadastro($mdlUser);

                    $mdlUser = $this->BaseExterna->exportarUsuario($mdlUser, $origem, $atualizacao);
                    if ($mdlUser !== false) {
                        if(!$atualizacao) {
                            if (isset($mdlUser->username) && isset($mdlUser->idnumber))
                                $this->MdlUser->save($mdlUser);
                            else {
                                $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possivel cadastrar/alterar a chave do usuario')];
                                $retorno['erro'] = 5;
                            }
                        }
                    } else {
                        $retorno = ['sucesso' => true, 'mensagem' => __('Não foi possivel exportar o usuário')];
                        $retorno['erro'] = 6;
                    }

                } else {
                    $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possivel cadastrar/alterar o usuário')];
                    $retorno['erro'] = 7;
                }
            }else{
                $mensagem = '';
                $codigoErro = 0;
                if($this->MdlUser->exists(['email' => $this->request->data['email']])){
                    $mensagem = __('E-mail já registrado');
                    $codigoErro = 8;
                }elseif($this->MdlUser->MdlUserDados->exists(['numero' => $this->request->data['numero']])){
                    $mensagem = __('CPF/CNPJ já registrado');
                    $codigoErro = 9;
                }elseif(!$emailValido){
                    $mensagem = __('E-mail inválido');
                    $codigoErro = 10;
                }elseif(!$numeroValido){
                    $mensagem = __('CPF/CNPJ inválido');
                    $codigoErro = 11;
                }

                $retorno = ['sucesso' => false, 'mensagem' => $mensagem, 'erro' => $codigoErro];
            }
        }

        if($retorno['sucesso'] && !$atualizacao){
            $mensagem = isset($retorno['mensagem'])? $retorno['mensagem'] : '';
            
            if($mail =$this->envioEmailUsuario($mdlUser, $mensagem)){
                $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                $this->inserirRepasseNovoCadastro($mdlUser, $mensagem);
            }
        }

        $this->set(compact('retorno'));
    }

    public function enviarTokenConfirmacao(){
        $retorno = ['sucesso' => false, 'status' => 'cadastre-se', 'mensagem' => __('O usuário não foi encontrado.')];
        $this->loadModel('MdlUser');
        $username = $this->request->data['username'];

        /**
         * Busca por CPF
         *
        if (preg_match('([0-9]{2}[\.]?[0-9]{3}[\.]?[0-9]{3}[\/]?[0-9]{4}[-]?[0-9]{2})|([0-9]{3}[\.]?[0-9]{3}[\.]?[0-9]{3}[-]?[0-9]{2})', $username) !== false) {
        $usuario = $this->MdlUser->find()->matching('MdlUserDados', function ($q) use ($username) {
        return $q->where(['numero' => $username]);
        })->first();
        }*/

        if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
            if($usuario = $this->MdlUser->find()->where(['email' => $username])->first()){
                $this->loadComponent('BaseExterna');
                $retorno = $this->enviarToken($usuario);
            }
        }
        $this->set(compact('retorno'));
    }

    public function enviarToken($usuario){
        $retorno = ['sucesso' => true, 'status' => 'confirmado', 'mensagem' => __('O usuário já encontra-se confirmado em nossa base de dados.')];
        if($usuario->confirmed)
            return $retorno;

        $token = JWT::encode([
            'id' => $usuario->id,
            'time' => time()
        ], Security::salt());

        $email = new Email();
        $email->template('emailCadastroConfirmacao')->emailFormat('html');
        $email->subject('QiSat | Confirmação de Cadastro');

        $this->EcmConfig = TableRegistry::get('EcmConfig');
        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_noreply']])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $dominio = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'dominio_acesso_site']])->first()->valor;

        $email->from([$noreply => $fromEmailTitle]);
        $email->viewVars(['usuario' => $usuario, 'dominio' => $dominio, 'token' => $token]);

        try {
            $email->to([$usuario->email => $usuario->firstname . ' ' . $usuario->lastname]);
            $email->send();
            $retorno['status'] = 'enviado';
            $retorno['mensagem'] = __('O token foi enviado com sucesso.');
        } catch (\Exception $e) {
            $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;

            $email->to([$supportemail => $fromEmailTitle]);
            $email->send();
            $retorno = ['sucesso' => false, 'status' => 'erro', 'mensagem' => __('Não foi possivel enviar o token para o usuário.')];
        }

        return $retorno;
    }

    public function confirmarCadastro(){
        $retorno = ['sucesso' => false, 'status' => 'erro', 'mensagem' => 'Favor, informe o token de confirmação'];
        if(!empty($this->request->data['token'])){
            $token = $this->request->data['token'];
            $tokenInfo = JWT::decode($token, Security::salt(), array('HS256'));

            $this->loadModel('Configuracao.EcmConfig');
            $tempo = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'token_tempo_confirmacao_cadastro']])->first();

            $retorno = ['sucesso' => false, 'status' => 'invalido', 'mensagem' => 'Token inválido.'];

            if(!isset($tempo) || $tempo->valor + $tokenInfo->time > time()) {
                $this->loadModel('MdlUser');
                $usuario = $this->MdlUser->get($tokenInfo->id, ['fields' => ['id', 'confirmed', 'username', 'password', 'firstname', 'lastname', 'email']]);
                if($usuario->confirmed){
                    return ['sucesso' => false, 'status' => 'confirmado', 'mensagem' => 'Cadastro ja confirmado.'];
                }
                $usuario->confirmed = 1;
                if($this->MdlUser->save($usuario)){
                    $retorno = ['sucesso' => true, 'status' => 'valido', 'mensagem' => 'Cadastro confirmado com sucesso.'];

                    $this->enviarConfirmacaoCadastro($usuario);

                }else{
                    $retorno = ['sucesso' => false, 'status' => 'erro', 'mensagem' => 'Não foi possivel confirmar o cadastro.'];
                }
            } else {
                $retorno['status'] = 'expirado';
                $retorno['mensagem'] = __('O token expirou.');
            }
        }
        $this->set(compact('retorno'));
    }

    private function enviarConfirmacaoCadastro($usuario){
        $email = new Email();
        $email->template('emailConfirmacaoCadastro')->emailFormat('html');
        $email->subject('QiSat | Confirmação de Cadastro');

        $this->EcmConfig = TableRegistry::get('EcmConfig');
        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_noreply']])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $email->from([$noreply => $fromEmailTitle]);

        if(empty($usuario->id)) {
            $email->viewVars(['usuario' => $usuario]);
            $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
            $email->to([$supportemail => $fromEmailTitle]);
            $email->send();
        } else {
            if (empty($usuario->password)) {
                $this->loadModel('MdlUser');
                $usuario = $this->MdlUser->get($usuario->id, ['fields' => ['id', 'confirmed', 'username', 'password', 'firstname', 'lastname', 'email']]);
            }

            $aesHash = new AESPasswordHasher();
            $password = $aesHash->decrypt($usuario->password);

            $email->viewVars(['usuario' => $usuario, 'password' => $password]);

            try {
                $email->to([$usuario->email => $usuario->firstname . ' ' . $usuario->lastname]);
                $email->send();
            } catch (\Exception $e) {
                $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
                $email->to([$supportemail => $fromEmailTitle]);
                $email->send();
            }
        }
    }

    private function envioEmailUsuario($usuario, $mensagem){
        $this->loadModel('Configuracao.EcmConfig');

        $paramsEmail = [
            'usuario' => $usuario,
            'mensagem' => $mensagem
        ];

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $params = [[$fromEmail => $fromEmailTitle], $adminEmail, $paramsEmail];
        return $this->getMailer('WscUser')->send('novoCadastro', $params);
    }

    private function inserirRepasseNovoCadastro($usuario, $mensagem){
        $this->loadModel('Repasse.EcmRepasse');

        $repasse = $this->EcmRepasse->newEntity();

        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
        $repasse->set('assunto_email', 'QiSat | '.__('Novo Cadastro Efetuado'));
        $repasse->set('corpo_email',$mensagem );
        $repasse->set('ecm_alternative_host_id', 1);

        if(!is_null($usuario))
            $repasse->set('mdl_user_cliente_id', $usuario->id);

        if($ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()->where(['LOWER(origem)' => 'Site QiSat'])->first())
            $repasse->set('ecm_repasse_origem_id', $ecmRepasseOrigem->id);

        if($ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()->where(['LOWER(categoria)' => 'Novo Cadastro'])->first())
            $repasse->set('ecm_repasse_categorias_id', $ecmRepasseCategoria->id);

        $this->EcmRepasse->save($repasse);
    }

    /*
    * Função responsável por enviar e-mail de lembrete de senha
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/wsc-user/lembrete-senha/{email ou chave}
    *
    * Retornos:
    * 1- {'sucesso':true, cidade:lista de cidades}
    * 2- {'sucesso':false, 'mensagem': 'Parâmetro email ou chave não informado'}
    * 3- {'sucesso':false, 'mensagem': 'Erro ao enviar e-mail'}
    * 4- {'sucesso':false, 'mensagem': 'Usuário não encontrado'}
    *
    * */
    public function lembreteSenha($email = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Parâmetro email ou chave não informado'), 'erro' => 'sem-dados' ];

        if(is_null($email) || $email == "/:action")
            $email = $this->request->data('email');

        if(!is_null($email) && $email != "/:action") {
            $this->loadModel('MdlUser');

            $usuario = $this->MdlUser->find('all', ['firstname', 'lastname', 'email', 'idnumber', 'password'])
                ->orWhere(['email' => $email])
                ->orWhere(['idnumber' => $email])
                ->first();

            /**
             * // Lembrete de senha AltoQi
             *
            if(is_null($usuario)) {
                $this->loadComponent('BaseExterna');
                if ($user = $this->BaseExterna->verificarUsuario($email, null)){
                    $usuario = new \stdClass();
                    $usuario->email = $email;
                    $usuario->idnumber = $usuario->username = $user['ChaveAltoQi'];
                    $aes = new AESPasswordHasher();
                    if(!$user['SenhaInternet']){
                        $usuario->password = $aes->hash($user['ChaveAltoQi']);
                    }else{
                        $usuario->password = $aes->hash($user['SenhaInternet']);
                    }
                    $nomeCompleto = ltrim($user['NomeEntidade']);
                    if(strpos($nomeCompleto, ' ')){
                        $usuario->firstname = substr($nomeCompleto, 0,strpos($nomeCompleto, ' '));
                        $usuario->lastname = ltrim(substr($nomeCompleto, strpos($nomeCompleto, ' ')));
                    }else{
                        $usuario->firstname = $nomeCompleto;
                        $usuario->lastname = ' ';
                    }
                }
            }*/

            if(!is_null($usuario)) {

                $aesHash = new AESPasswordHasher();
                $senha = $aesHash->decrypt($usuario->password);

                $paramsEmail = [
                    'nome' => $usuario->firstname.' '.$usuario->lastname,
                    'email' => $usuario->email,
                    'login' => $usuario->username,
                    'senha' => $senha
                ];

                $this->loadModel('Configuracao.EcmConfig');
                $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
                $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

                $params = [ [$fromEmail => $fromEmailTitle], $usuario->email, $paramsEmail];

                if($this->getMailer('WscUser')->send('lembreteSenha', $params)){

                    $toEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;

                    $paramsEmail = [
                        'nomeChave' => $usuario->idnumber.' - '.$usuario->firstname.' '.$usuario->lastname,
                    ];

                    $params = [ [$fromEmail => $fromEmailTitle], $toEmail, $paramsEmail];

                    $this->getMailer('WscUser')->send('lembreteSenhaAdm', $params);

                    $retorno = ['sucesso' => true];
                }else {
                    $retorno = ['sucesso' => false, 'mensagem' => __('Erro ao enviar e-mail'), 'erro' => 'envio-email' ];
                }

            }else{
                $retorno = ['sucesso' => false, 'mensagem' => __('Usuário não encontrado'), 'erro' => 'nao-localizado' ];
            }
        }

        $this->set(compact('retorno'));
    }

    /**
     * Função responsável por checar o token do usuario
     * http://{host}/user/checkAuth
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Acesso Restrito'}
     */
    public function checkAuth(){
        $retorno = ['sucesso' => false, 'mensagem' => 'Acesso Restrito'];
        $token = $this->getToken();

        $jwt = new JwtAuthenticate($this->_components, []);
        if($jwt->authenticate($this->request, $this->response) && isset($this->Auth->user()['id'])){
            $this->loadModel('MdlUser');
            $user = $this->Auth->user();

            $mdlUser = $this->MdlUser->get($user['id'],
                [
                    'fields' => [
                        'id', 'username', 'password', 'idnumber', 'firstname', 'lastname', 'email',
                        'imagealt', 'picture', 'phone1', 'phone2', 'country', 'city', 'address'
                    ],
                    'contain' => [
                        'MdlUserDados' => [
                            'fields' => [
                                'numero' => 'numero', 'numero_crea' => 'numero_crea',
                                'tipousuario' => 'tipousuario', 'email_oferta' => 'email_oferta',
                                'email_andamento' => 'email_andamento', 'email_mensagem_privada' => 'email_mensagem_privada',
                                'email_ausente' => 'email_ausente', 'email_suporte' => 'email_suporte',
                                'ligacao_lancamentos' => 'ligacao_lancamentos', 'ligacao_pagamento' => 'ligacao_pagamento',
                                'sms_informacoes' => 'sms_informacoes', 'sms_lancamentos' => 'sms_lancamentos',
                                'funcionarioqisat' => 'funcionarioqisat'
                            ]
                        ],
                        'MdlUserEndereco' => [
                            'fields' => ['number', 'complement', 'district', 'state', 'cep']
                        ],
                        'MdlUserEcmAlternativeHost' => [
                            'fields' =>[
                                'id' => 'ecm_alternative_host_id', 'mdl_user_id', 'numero',
                                'adimplente', 'confirmado'
                            ],
                            'EcmAlternativeHost'=> [
                                'fields' =>[
                                    'shortname' => 'shortname', 'fullname' => 'fullname',
                                    'host' => 'host'
                                ]
                            ]
                        ]
                    ]
                ]
            );

            if(isset($mdlUser->mdl_user_endereco)) {
                $mdlUser->endereco = $mdlUser->mdl_user_endereco;
                $mdlUser->endereco->logradouro = $mdlUser->address;
            }

            if(!empty($mdlUser->mdl_user_ecm_alternative_host)){
                $mdlUser->entidades = $mdlUser->mdl_user_ecm_alternative_host;
            }

            unset($mdlUser->mdl_user_endereco);
            unset($mdlUser->mdl_user_ecm_alternative_host);
            unset($mdlUser->address);
            if($mdlUser->picture > 0){
                $this->loadModel('Configuracao.EcmConfig');
                $moodle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_moodle'])->first()->valor;
                $mdlUser->picture = 'https://' . $moodle . '/user/pix.php/' . $mdlUser->id . '/f1';
            }
            $retorno = ['sucesso' => true, 'usuario' => $mdlUser];
        }

        $this->set(compact('retorno', 'token'));
        $this->set('_serialize', ['retorno', 'token']);
    }

    /**
     * Função responsável por checar o password do usuario
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
     * http://{host}/user/checkPassword
     * {id, password}
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessaria'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetro password não informado'}
     * 4- {'sucesso':false, 'mensagem': 'Password incorreto'}
     */
    public function checkPassword(){
        $retorno = ['sucesso' => false, 'mensagem' => 'Requisição POST necessaria'];
        if ($this->request->is('post')) {
            $id = $this->Auth->user()['id'];
            $password = $this->request->data('password');
            $retorno['mensagem'] = 'Parâmetro password não informado';
            if (!is_null($password)) {
                $aes = new AESPasswordHasher();
                $password = $aes->hash($password);
                $this->loadModel('MdlUser');
                $retorno = ['sucesso' => $this->MdlUser->exists(['MdlUser.id' => $id, 'MdlUser.password' => $password])];
                if (!$retorno['sucesso'])
                    $retorno['mensagem'] = 'Password incorreto';
            }
        }
        $this->set(compact('retorno'));
    }

    /**
     * Função responsável por alterar o password do usuario
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
     * http://{host}/user/updatePassword
     * {id, password, newpassword, renewpassword}
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessaria'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetros não informado'}
     * 4- {'sucesso':false, 'mensagem': 'O novo password nao consiste com sua confirmação'}
     * 5- {'sucesso':false, 'mensagem': 'Não foi possivel atualizar o password do usuário'}
     * 6- {'sucesso':false, 'mensagem': 'Password incorreto'}
     */
    public function updatePassword(){
        $retorno = ['sucesso' => false, 'mensagem' => 'Requisição POST necessaria'];
        if ($this->request->is('post')) {
            $retorno['mensagem'] = 'Parâmetros não informado';
            $id = $this->request->data('userid');
            $password = $this->request->data('password');
            $newpassword = $this->request->data('newpassword');
            $renewpassword = $this->request->data('renewpassword');
            if (!is_null($id) && !is_null($password) && !is_null($newpassword) && !is_null($renewpassword)) {
                $this->loadModel('MdlUser');
                $aes = new AESPasswordHasher();
                $password = $aes->hash($password);
                if ($newpassword != $renewpassword) {
                    $retorno['mensagem'] = 'O novo password nao consiste com sua confirmação';
                } else if ($mdlUser = $this->MdlUser->find()->where(['MdlUser.id' => $id, 'MdlUser.password' => $password])->first()) {
                    $mdlUser->password = $aes->hash($newpassword);
                    if ($this->MdlUser->save($mdlUser))
                        $retorno = ['sucesso' => true];
                    else
                        $retorno['mensagem'] = 'Não foi possivel atualizar o password do usuário';
                } else {
                    $retorno['mensagem'] = 'Password incorreto';
                }
            }
        }
        $this->set(compact('retorno'));
    }

    /**
     * Função responsável por checar o email do usuario
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
     * http://{host}/user/checkEmail
     * {email}
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessaria'}
     * 3- {'sucesso':false, 'mensagem': 'Email não informado'}
     * 4- {'sucesso':false, 'mensagem': 'Email inválido'}
     * 5- {'sucesso':false, 'mensagem': 'Email não encontrado'}
     */
    public function checkEmail(){
        $retorno = ['sucesso' => true, 'mensagem' => 'Requisição POST necessaria'];
        if ($this->request->is('post')) {
            $retorno['mensagem'] = 'Email não informado';
            $email = $this->request->data['email'];
            if (!is_null($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
                $this->loadModel('MdlUser');
                if($this->MdlUser->exists(['email' => $email])){
                    $retorno['mensagem'] = 'Email já cadastrado';
                }else{
                    $retorno = ['sucesso' => false, 'mensagem' => 'Email valido'];
                    /**
                     * Forma de verificar se o email ja foi cadastrado na base da AltoQi *Desuso*
                     *
                    $this->loadComponent('BaseExterna');
                    $retorno = ['sucesso' => $this->BaseExterna->verificarUsuario($email, null) !== false];
                    if (!$retorno['sucesso'])
                    $retorno['mensagem'] = 'Email não encontrado';
                     */
                }
            } else {
                $retorno['mensagem'] = 'Email inválido';
            }
        }

        $this->set(compact('retorno'));
    }

    /**
     * Função responsável por fazer o upload da imagem do usuario
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
     * http://{host}/user/uploadImagemUsuario
     * {picture}
     *
     * Retornos:
     * 1- {'sucesso':true, 'imagem': link de acesso a imagem}
     * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessária'}
     * 3- {'sucesso':false, 'mensagem': 'Imagem não informada'}
     */
    public function uploadImagemUsuario(){

        $retorno = ['sucesso' => false, 'mensagem' => 'Requisição POST necessária'];
        if ($this->request->is('post')) {
            $this->loadModel('MdlUser');

            $usuario = null;
            $id = $this->Auth->user('id');

            try{
                $usuario = $this->MdlUser->get($id);
            }catch(RecordNotFoundException $e){}

            $retorno['mensagem'] = 'Imagem não informada';

            if (isset($this->request->data['picture'])) {
                $picture = $this->request->data['picture'];

                $this->loadModel('Configuracao.EcmConfig');
                $moodledata = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'diretorio_moodledata'])->first();
                $this->MdlUser->enviarImagem($picture, $usuario, $moodledata->valor);

                $moodle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_moodle'])->first()->valor;
                $urlImagem = 'http://' . $moodle . '/user/pix.php/' . $usuario['id'] . '/f1';

                $retorno = ['sucesso' => true, 'imagem' => $urlImagem];
            }
        }

        $this->set(compact('retorno'));
    }

    /**
     * Função responsável por checar o cpf do usuario
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros:
     * http://{host}/user/checkCPF
     * {cpf}
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Requisição POST necessaria'}
     * 3- {'sucesso':false, 'mensagem': 'CPF ou CNPJ não informados'}
     * 4- {'sucesso':false, 'mensagem': 'CPF ou CNPJ inválidos'}
     * 5- {'sucesso':false, 'mensagem': 'CPF ou CNPJ não encontrados'}
     */
    public function checkCPF(){
        $retorno = ['sucesso' => false, 'mensagem' => 'Requisição POST necessaria'];
        if ($this->request->is('post')) {
            $retorno['mensagem'] = 'CPF ou CNPJ não informados';
            $cpf = $this->request->data('cpf');
            if (!is_null($cpf)) {
                $retorno['mensagem'] = 'CPF ou CNPJ inválidos';
                if ($this->validarCPF($cpf) || $this->validarCNPJ($cpf)) {
                    $this->loadModel('MdlUser');
                    if($this->MdlUser->MdlUserDados->exists(['numero' => $cpf]))
                        $retorno['mensagem'] = 'CPF ou CNPJ já registrado';
                    else
                        /**
                         * Forma de verificar se o cpf/cnpj ja foi cadastrado na base da AltoQi *Desuso*
                         *
                        $this->loadComponent('BaseExterna');
                        $retorno = ['sucesso' => !($this->BaseExterna->verificarUsuario(null, $cpf) !== false)];
                        if ($retorno['sucesso'])
                        $retorno['mensagem'] = 'CPF ou CNPJ disponível para registro';
                        else
                        $retorno['mensagem'] = 'CPF ou CNPJ já registrado';
                         */
                        $retorno = ['sucesso' => true, 'mensagem' => 'CPF ou CNPJ disponível para registro'];
                }
            }
        }
        $this->set(compact('retorno'));
    }

    private function validarCPF( $cpf = '' ) {
        $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
        if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' ||
                $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' ||
                $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return FALSE;
        } else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d)
                    return FALSE;
            }
            return TRUE;
        }
    }
    private function validarCNPJ($cnpj){
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        if (strlen($cnpj) != 14)
            return false;
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
}