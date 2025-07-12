<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use App\Auth\AESPasswordHasher;
use App\Model\Entity\MdlUser;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('CookieOverride');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'home'
            ],
            'logoutRedirect' => [
                'controller' => 'users',
                'action' => 'login'
            ],
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'username', 'password' => 'password'],
                    'userModel' => 'MdlUser',
                    'passwordHasher' => [
                        'className' => 'AES',
                    ]
                ]
            ]
        ]);
    }

    public function beforeFilter(Event $event)
    {
        $this->loadModel('EcmConfig');

        $linkSite = $this->EcmConfig->find('all')->where(['nome' => 'dominio_acesso_site'])->first()->valor;
        $this->set('linkHostSite', $linkSite);

        if(is_null($this->request->session()->read('dominio_site'))){
            $this->loadModel('Configuracao.EcmConfig');

            $dominioSite = $this->EcmConfig->find()->where(['nome' => 'dominio_acesso_site'])->first()->valor;
            $this->request->session()->write('dominio_site', $dominioSite);
        }

        if($this->request->action == 'logout'){
            if($this->verificarCookieLogin()) {

                $this->setCookieConfig();
                $this->CookieOverride->delete('QiSat');
            }
        }elseif(!$this->verificarCookieLogin() && !is_null($this->Auth->user())){
            $this->redirect(['plugin' => false, 'controller' => 'user', 'action' => 'logout']);
        }

        /**
         * Autenticação via cookie
         */
        $jwt = new JwtAuthenticate($this->_components, []);
        if (is_null($this->Auth->user()) && !is_null($this->CookieOverride->read('QiSat')) &&
                !is_null($jwt->getPayload($this->request))) {
            $cookie = $this->CookieOverride->read('QiSat');
            $this->request->data['username'] = $cookie['username'];
            $this->request->data['password'] = $cookie['password'];
            $user = $this->Auth->identify();
            $this->loadModel('EcmGrupoPermissao');
            $acessoTotal = $this->EcmGrupoPermissao->verificarAcessoTotalUsuario($user['id']);
            if($acessoTotal == 0) {
                $this->loadModel('EcmPermissao');
                $user['permissoes'] = $this->EcmPermissao->buscarPermissoesUsuario($user['id']);
            }else{
                $user['permissoes'] = ['acesso_total' => true];
            }
            $this->Auth->setUser($user);
        }

        $permissoes = null;
        if(!is_null($this->Auth->user())) {
            $permissoes = $this->Auth->user('permissoes');

            if(array_key_exists('acesso_total', $permissoes) &&
                $permissoes['acesso_total'] == true){
                $this->Auth->allow();
                return;
            }
        }else{
            $this->loadModel('EcmPermissao');
            $permissoes = $this->EcmPermissao->buscarPermissoesPorRestricao('site');
        }

        if (!is_null($permissoes) && array_key_exists($this->request->plugin, $permissoes)){
            if(array_key_exists($this->request->controller, $permissoes[$this->request->plugin])) {
                $permissoes = $permissoes[$this->request->plugin][$this->request->controller];
                $this->Auth->allow(array_keys($permissoes));
            }
        }
    }

    public function isAuthorized($user)
    {
        if(!isset($user['permissoes']))
            $user['permissoes'] = [];
        $acesso = MdlUser::verificarPermissao($this->request->action, $this->request->controller,
                $this->request->plugin, $user['permissoes']);
        if(!$acesso){
            $this->Flash->error(__('You are not authorized to access that location.'));
            $this->redirect(['plugin' => false, 'controller' => 'pages', 'action' => 'index']);
        }
        return $acesso;
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    protected function verificarCookieLogin(){
        $this->setCookieConfig();

        return $this->CookieOverride->check('QiSat');
    }

    protected function criarCookie($user){
        $this->loadModel('MdlUser');

        $senha = $this->MdlUser
            ->find('all', ['fields'=>['password']])
            ->where(['id' => $user['id']])
            ->first();

        $senha = $senha->get('password');

        $this->setCookieConfig();

        $this->CookieOverride->write('QiSat',
            ['username' => $user['username'], 'password' => $senha, 'time' => time()]
        );
    }

    protected function setCookieData(){
        if($this->CookieOverride->check('QiSat')) {
            $cookie = $this->CookieOverride->read('QiSat');

            $aesHash = new AESPasswordHasher();
            $cookie['password'] = $aesHash->decrypt($cookie['password']);

            $this->request->data = $cookie;
        }
    }

    protected function setCookieConfig(){
        $this->loadModel('EcmConfig');

        $dominio = $this->EcmConfig->find()->where(['nome' => 'dominio'])->first();

        $this->CookieOverride->configKey('QiSat', [
            'httpOnly' => true,
            'domain' => $dominio->get('valor')
        ]);
    }

    protected function createToken($id, $username, $novoToken = false){
        $this->loadModel('Configuracao.EcmConfig');
        $jwt = new JwtAuthenticate($this->_components, []);
        $payload = $jwt->getPayload($this->request);

        $token_tempo = $this->EcmConfig->find()->where(['nome' => 'login_token_tempo_expiracao'])->first()->valor;
        $renovacao = $this->EcmConfig->find()->where(['nome' => 'login_token_tempo_renovacao'])->first()->valor;

        if (!is_null($id) && !is_null($username) && ($novoToken ||
                (isset($payload) && $payload->exp < time() + $token_tempo - $renovacao))) {
            $token = JWT::encode([
                'sub' => $id,
                //'username' => $username,
                'cookieTime' => $this->CookieOverride->read('QiSat')['time'],
                'exp' => time() + $token_tempo
            ], Security::salt());

            $this->set('token', $token);

            return $token;
        }

        return $jwt->getToken($this->request);
    }

    protected function getToken(){
        $this->loadModel('Configuracao.EcmConfig');
        $jwt = new JwtAuthenticate($this->_components, []);
        return $jwt->getToken($this->request);
    }
}
