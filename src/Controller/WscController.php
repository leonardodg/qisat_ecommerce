<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 29/06/2016
 * Time: 10:36
 */

namespace App\Controller;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use Cake\Event\Event;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\Network\Http\Client;

class WscController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        if($this->request->params['controller'] != 'EcmCarrinho') {
            $this->Auth->config(['authenticate' => [
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' => 'MdlUser',
                    'passwordHasher' => [
                        'className' => 'AES',
                    ]
                ]
            ], 'storage' => 'Memory', 'unauthorizedRedirect' => false]);
        }
    }

    public function afterFilter(Event $event)
    {

        if(!is_null($this->request->header('Cookie')) && ($this->request->is('post') || $this->request->is('options'))){
            $this->loadModel('Configuracao.EcmConfig');

            $indexof = strpos($this->referer(), "/");
            $referer = substr($this->referer(), $indexof + 2, strpos($this->referer(), "/", 8) - $indexof - 2);

            $dominio = $this->EcmConfig->find('list', ['keyField' => 'id', 'valueField' => 'valor'])
                ->where(['nome like "dominio_acesso_%"'])->toArray();

            if (in_array($referer, $dominio)) {
                $dominioAcesso = substr($this->referer(), 0, strpos($this->referer(), "/", 8));
            }else{
                $dominioAcesso = $this->EcmConfig->find('all', ['keyField' => 'id', 'valueField' => 'valor'])
                    ->where(['nome' => 'dominio_acesso_site'])->first()->get('valor');
            }
            $this->response->header('Access-Control-Allow-Origin', $dominioAcesso);
        }

        if ($this->request->is('options'))
            $this->response->body('');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $verificaCaptcha = $this->verificarCaptcha();

        if (!$verificaCaptcha) {
            $retorno = [
                'sucesso' => false,
                'mensagem' => __('Captcha inválido'),
                'erro' => 'captcha-invalido'
            ];

            echo json_encode($retorno);
            die;
        }

        if ($this->request->is('options')) {
            $this->Auth->allow();
            return $this->response;
        }

        if($this->request->params['controller'] != 'EcmCarrinho') {
            $verificado = false;

            $this->loadModel('Configuracao.EcmConfig');
            /**
             * Verificação por Dominio ou por Ip
             */
            $indexof = strpos($this->referer(), "/");
            if ($indexof > 1) {
                $referer = substr($this->referer(), $indexof + 2, strpos($this->referer(), "/", 8) - $indexof - 2);

                $dominio = $this->EcmConfig->find('list', ['keyField' => 'id', 'valueField' => 'valor'])
                    ->where(['nome like "dominio_acesso_%"'])->toArray();
                if (in_array($referer, $dominio)) {
                    $verificado = true;
                } else {
                    $ip = $this->EcmConfig->find('list', ['keyField' => 'id', 'valueField' => 'valor'])
                        ->where(['nome like "ip_acesso_%"'])->toArray();
                    if (in_array($this->request->clientIp(), $ip))
                        $verificado = true;
                }
            }
            /**
             * Verificação para o retorno da forma de pagamento do super pay
             */
            if (!$verificado) {
                $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
                if ($ambienteProducao->valor == 1)
                    $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'token_super_pay'])->first();
                else
                    $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_token_super_pay'])->first();

                if ($token->valor == $this->request->data('codigoEstabelecimento') &&
                    (is_numeric($this->request->data('numeroTransacao')) || is_numeric($this->request->data('numeroRecorrencia')))) {
                    $verificado = true;
                } else if (!$verificado && !is_null($this->request->session()->read('carrinho'))) {
                    if ($this->request->session()->read('carrinho')->status == "Finalizado" && $this->request->params['action'] == "agendamento")
                        $verificado = true;
                }
            }

            if(!$verificado)
                $verificado = $this->verificaAcesso();

            if (!$verificado)
                $this->redirect(['plugin' => false, 'controller' => 'pages', 'action' => 'index']);

           $novoToken = false;
            if($this->request->params['controller'] == 'WscFormaPagamento' &&
                $this->request->params['action'] == 'requisicao'){

                $jwt = new JwtAuthenticate($this->_components, []);
                $payload = $jwt->getPayload($this->request);

                if(is_null($payload) || is_null($payload->exp) || ($payload->exp - time()) <= 7200)
                    $novoToken = true;
            }

            $this->createToken($this->Auth->user()['id'], $this->Auth->user()['username'], $novoToken);
        }
    }

    protected function validaAlternativeHost(){
        $this->loadModel('Entidade.EcmAlternativeHost');

        $referer = str_replace('https://', 'http://', $this->referer());

        if(strpos($referer, "/"))
            $referer = substr($referer, 0, strpos($referer, "/", 8)+1);

        if(strpos($referer, 'www')){
            $referer = str_replace('www.','',$referer);
        }

        if($ecmAlternativeHost = $this->EcmAlternativeHost->find()->where(['host' => $referer])->first())
            return $ecmAlternativeHost['id'];

        return ['sucesso' => false, 'mensagem' => __('Entidade não encontrada')];
    }

    private function verificaAcesso(){
        $this->loadModel('EcmPermissao');

        $permissao = $this->EcmPermissao
            ->find('all',[
                'fields' => 'id'
            ])
            ->where([
                'action' => $this->request->action,
                'controller' => $this->request->controller,
                'plugin' => $this->request->plugin,
                'acesso_total' => 1
            ])
            ->first();

        return !is_null($permissao);
    }


    protected function validarCaptcha($captcha = null){

        $this->loadModel('Configuracao.EcmConfig');
        $this->environment = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();

        $http = new Client();

        if(is_null($captcha)) {
            if (!is_null($this->request->data('recaptcha')))
                $captcha = $this->request->data('recaptcha');
            elseif (!is_null($this->request->query('recaptcha')))
                $captcha = $this->request->query('recaptcha');
        }

        if(!is_null($captcha)) {

            if($this->environment->valor == "1"){
                $secret = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'recaptcha_v2_producao'])->first()->valor;
            }else{
                $secret = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'recaptcha_v2_teste'])->first()->valor;
            }

            $query = [ 'secret' =>  $secret, 'response' =>  $captcha ];
            $response = $http->get( 'https://www.google.com/recaptcha/api/siteverify', $query );
            $responseData = $response->json;

            if($response->isOk()){
                return $responseData['success'];
            }
        }

        return false;
    }

    protected function verificarCaptcha(){
        $this->loadModel('EcmValidacaoRecaptcha');

        $validar = $this->EcmValidacaoRecaptcha->find('all')
            ->where([
                'url' => $this->request->url
            ])
            ->first();

        if(!is_null($validar)) {
            $dataRequest = json_decode(file_get_contents('php://input'));
            $verificaCaptcha = isset($dataRequest->recaptcha) ? $this->validarCaptcha($dataRequest->recaptcha) : false;

            return $verificaCaptcha;
        }
        return true;
    }
}