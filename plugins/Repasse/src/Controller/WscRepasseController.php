<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 02/08/2016
 * Time: 12:39
 */

namespace Repasse\Controller;



use App\Controller\WscController;
use Cake\Event\Event;
use Repasse\Model\Entity\EcmRepasse;

use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;

class WscRepasseController extends WscController
{
    use MailerAwareTrait;
    
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

    }

    /*
    * Função reponsável por inserir repasses
    * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
    * {
    *  assunto_email: (assunto do e-mail),
    *  corpo_email: (corpo do e-mail),
    *  entidade: (id da entidade)
    * }
    *
    * Retornos:
    * 1- {'sucesso':true}
    * 2- {'sucesso':false, 'mensagem': 'Este Web Service não aceita esse tipo de requisição'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro assunto_email não informado'}
    * 4- {'sucesso':false, 'mensagem': 'Parâmetro corpo_email não informado'}
    * 5- {'sucesso':false, 'mensagem': 'Não foi possível inserir o repasse'}
    * 6- {'sucesso':false, 'mensagem': 'Parâmetro entidade incorreto'}
    * 7- {'sucesso':false, 'mensagem': 'Entidade não encontrada'}
    *
    * */
    public function salvar(){
        $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possível cadastrar a soilicitação de contato.')];
        if ($this->request->is('post')) {

            $validaDados = $this->validarDados();
            if(is_array($validaDados)) {
                $retorno = $validaDados;
            }else{
                $this->loadModel('Repasse.EcmRepasse');
                $repasse = $this->EcmRepasse->newEntity();

                $this->loadModel('MdlUser');
                if(isset($this->request->data['entidade'])){
                    $entidade = $this->request->data['entidade'];
                    $user_cliente = $this->MdlUser->find()->select(['id'])
                        ->where(['idnumber' => substr($entidade, 0, 6)])->orWhere(['email' => $entidade])
                        ->orWhere(['phone1 LIKE "%'.$entidade.'%"'])->orWhere(['phone2 LIKE "%'.$entidade.'%"'])
                        ->leftJoinWith('MdlUserDados', function($q)use($entidade){
                            return $q->where(['numero' => $entidade]);
                        })->first();

                    if(!is_null($user_cliente))
                        $this->request->data['mdl_user_cliente_id'] = $user_cliente->id;
                }

                if(isset($this->request->data['usuario'])){

                    $usuario = $this->request->data['usuario'];
                    $user = $this->MdlUser->find()->select(['id'])
                        ->where(['idnumber' => substr($usuario, 0, 6)])->orWhere(['email' => $usuario])
                        ->orWhere(['phone1 LIKE "%'.$usuario.'%"'])->orWhere(['phone2 LIKE "%'.$usuario.'%"'])
                        ->leftJoinWith('MdlUserDados', function($q)use($usuario){
                            return $q->where(['numero' => $usuario]);
                        })->first();

                    if(!is_null($user))
                        $this->request->data['mdl_user_id'] = $user->id;
                }

                if(!isset($this->request->data['data_registro']))
                    $this->request->data['data_registro'] = new \DateTime();

                if(!isset($this->request->data['status']))
                    $this->request->data['status'] = EcmRepasse::STATUS_NAO_ATENDIDO;

                if(!isset($this->request->data['ecm_alternative_host_id']))
                    $this->request->data['ecm_alternative_host_id'] = $this->validaAlternativeHost();

                if(isset($this->request->data['origem'])){
                    $ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()
                        ->where(['origem' => $this->request->data['origem']])->first();
                    if(!is_null($ecmRepasseOrigem))
                        $this->request->data['ecm_repasse_origem_id'] = $ecmRepasseOrigem->id;
                }

                if(isset($this->request->data['categoria'])){
                    $ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()
                        ->where(['categoria' => $this->request->data['categoria']])->first();
                    if(!is_null($ecmRepasseCategoria))
                        $this->request->data['ecm_repasse_categorias_id'] = $ecmRepasseCategoria->id;
                }

                // Automatizar distribuição de equipe e colaborador

                $repasse = $this->EcmRepasse->patchEntity($repasse, $this->request->data);

                if ($this->EcmRepasse->save($repasse))
                    $retorno = ['sucesso' => true];
            }
        }

        $this->set(compact('retorno'));
    }

	public function rdstation(){

        /* REMOVER - APENAS PARA MONITORAMENTO */
        $data = $this->request->data;
		$arquivo = ROOT . DS . "RDStation_" . date('d-m-Y') . ".json";
		$fp = fopen($arquivo, 'a+');
		fwrite($fp, "\n");
		fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
		fclose($fp);
		$sucesso = [];
        $sucesso['sucesso'] = true;

		echo json_encode($sucesso);
		return;
		/* REMOVER APENAS PARA MONITORAMENTO */


        $this->loadModel('MdlUser');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Repasse.EcmRepasse');
        $this->loadModel('Repasse.EcmRepasseOrigem');
        $this->loadModel('Repasse.EcmRepasseCategorias');
        $this->loadModel('Repasse.WscRepasseMailer');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $params = [];
        $userDados = [];
        $ecmRepasse = [];
        $ecmRepasse['data_registro'] = new \DateTime();
        $ecmRepasse['status'] = EcmRepasse::STATUS_NAO_ATENDIDO;
        $retorno = ['sucesso' => false, 'mensagem' => __('Falha ao registrar Repasse')];

        if($hostAltoQi = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_site_altoqi'])->first())
            if($ecmAlternativeHost = $this->EcmAlternativeHost->find()->where(['host LIKE "%'.$hostAltoQi->valor.'%"'])->first())
                $ecmRepasse['ecm_alternative_host_id'] = $ecmAlternativeHost['id'];

        if(isset($data) and isset($data['leads']) and is_array($data['leads']) and isset($data['leads'][0])) {
            $lead = (array) $data['leads'][0];

            foreach($lead as $key => $value){

                if(isset($value) and !empty($value)){
                    $key = strtolower($key);
                    if($key == 'last_conversion'){
                        if($value['content']){
                            $repasseUserData = $this->EcmRepasse->EcmRepasseUserData->newEntity();
                            $repasseUserData->set('name', 'campanha');
                            $repasseUserData->set('value', $value['content']['identificador']);
                            array_push($userDados, $repasseUserData);
                            $params['campanha'] = $value['content']['identificador'];
                        }
                    }else if($key == 'created_at'){
                        $repasseUserData = $this->EcmRepasse->EcmRepasseUserData->newEntity();
                        $repasseUserData->set('name', 'data');
                        $aux = new \DateTime($value);
                        $aux = $aux->format('Y-m-d H:i:s');
                        $repasseUserData->set('value', $aux);
                        array_push($userDados, $repasseUserData);
                        $params['data'] = $aux;
                    }else if(is_array($value) and $key != 'first_conversion' and $key != 'tags'){
                        $params[$key] = [];
                        foreach($value as $k => $v){
                            $k = strtolower($k);
                            if(!is_array($v)){
                                $repasseUserData = $this->EcmRepasse->EcmRepasseUserData->newEntity();
                                $repasseUserData->set('name', $k);
                                $repasseUserData->set('value', $v);
                                array_push($userDados, $repasseUserData);
                                $params[$k] = $v;
                            }
                        }
                    }else if(!is_object($value) and !is_array($value)){
                        $repasseUserData = $this->EcmRepasse->EcmRepasseUserData->newEntity();
                        $repasseUserData->set('name', $key);
                        $repasseUserData->set('value', $value);
                        array_push($userDados, $repasseUserData);
                        $params[$key] = $value;
                    }
                }
            }
            $ecmRepasse['ecm_repasse_user_data'] = $userDados;
        }

        $params['origem'] = 'RDStantion AltoQi';
        $ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()
            ->where(['LOWER(origem)' => strtolower($params['origem'])])->first();
        if(!is_null($ecmRepasseOrigem))
            $ecmRepasse['ecm_repasse_origem_id'] = $ecmRepasseOrigem->id;
        
        if(isset($params['campanha'])){
            $ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()
                ->where(['LOWER(categoria)' => strtolower($params['campanha'])])->first();
            if(!is_null($ecmRepasseCategoria))
                $ecmRepasse['ecm_repasse_categorias_id'] = $ecmRepasseCategoria->id;
            else{
                $ecmRepasseCategoria = $this->EcmRepasseCategorias->newEntity();
                $ecmRepasseCategoria->set('categoria', $params['campanha']);

                if ($ecmRepasseCategoria = $this->EcmRepasseCategorias->save($ecmRepasseCategoria)) 
                    $ecmRepasse['ecm_repasse_categorias_id'] = $ecmRepasseCategoria->get('id'); 
            }
        }

        $params['repasse'] = $ecmRepasse = $this->EcmRepasse->newEntity($ecmRepasse, ['associated' => ['EcmRepasse.EcmRepasseUserData']]);

        if($this->EcmRepasse->save($ecmRepasse)){
            $retorno = ['sucesso' => true];
            if($mail = $this->getMailer('Repasse.WscRepasse')->send('rdstation', [$params])){
                $corpo_email = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                $ecmRepasse->set('corpo_email', $corpo_email);
                $this->EcmRepasse->save($ecmRepasse);
            }
        }

        $this->set(compact('retorno'));
	}

    /**
     * Função para salvar os Entrantes/Repasses novo formato de parametros
     * 
     * @params
     *        { 
     *            (inteiro) userid : id do cliente 
     *            (array) user_dados : [ "exemplo" : "valor", "email" : "teste@qisat.com.br" ]
     *              - DADOS DO CLIENTE PARA SALVAR NA TABELA (ecm_repasse_user_data)
     *            (string) categoria : "nome categoria"
     *            (string) origem : "nome origem"
     *            (string) observacao : "dados extra"
     *            (string) empresa : "nome da empresa" => shortname (Tabela ecm_alternative_host)
     *         }
     * 
     * @ return 
     *         { sucesso : true }
     *         { sucesso : false, mensagem : 'Falha ao registrar Repasse' }
     */
    public function novo(){
        $this->loadModel('MdlUser');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Repasse.EcmRepasse');
        $this->loadModel('Repasse.EcmRepasseOrigem');
        $this->loadModel('Repasse.EcmRepasseCategorias');
        $this->loadModel('Repasse.WscRepasseMailer');
        
        $retorno = ['sucesso' => false, 'mensagem' => __('Falha ao registrar Repasse')];
        $userDados = [];
        $paramsEmail = [];

        if ($this->request->is('post')) {

            if(isset($this->request->data['userid'])){
                $userid = $this->request->data['userid'];
                $user_cliente = $this->MdlUser->find()->select(['id'])->where(['id' => $userid])->first();
                if(!is_null($user_cliente)){
                    $this->request->data['mdl_user_cliente_id'] = $user_cliente->id;
                    $paramsEmail['usuario'] = $user_cliente;
                }
            }

            if(isset($this->request->data['user_dados'])){
                foreach($this->request->data['user_dados'] as $key => $value){
                    $repasseUserData = $this->EcmRepasse->EcmRepasseUserData->newEntity();
                    $repasseUserData->set('name', $key);
                    $repasseUserData->set('value', $value);
                    array_push($userDados, $repasseUserData);
                    $paramsEmail[$key] = $value;
                }
                $this->request->data['ecm_repasse_user_data'] = $userDados;
            }

            if(!isset($this->request->data['data_registro']))
                $this->request->data['data_registro'] = new \DateTime();

            if(!isset($this->request->data['status']))
                $this->request->data['status'] = EcmRepasse::STATUS_NAO_ATENDIDO;

            if(!isset($this->request->data['ecm_alternative_host_id']))
                $this->request->data['ecm_alternative_host_id'] = $this->validaAlternativeHost();

            if(isset($this->request->data['origem'])){
                $paramsEmail['origem'] = $this->request->data['origem'];
                $ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()
                    ->where(['LOWER(origem)' => strtolower($this->request->data['origem'])])->first();
                if(!is_null($ecmRepasseOrigem))
                    $this->request->data['ecm_repasse_origem_id'] = $ecmRepasseOrigem->id;
                else{
                    $ecmRepasseOrigem = $this->EcmRepasseOrigem->newEntity();
                    $ecmRepasseOrigem->set('origem', $this->request->data['origem']);
                    if ($ecmRepasseOrigem = $this->EcmRepasseOrigem->save($ecmRepasseOrigem)) 
                        $this->request->data['ecm_repasse_origem_id'] = $ecmRepasseOrigem->get('id');
                }
            }

            if(isset($this->request->data['categoria'])){
                $ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()
                    ->where(['LOWER(categoria)' => strtolower($this->request->data['categoria'])])->first();
                if(!is_null($ecmRepasseCategoria))
                    $this->request->data['ecm_repasse_categorias_id'] = $ecmRepasseCategoria->id;
                else{
                    $ecmRepasseCategoria = $this->EcmRepasseCategorias->newEntity();
                    $ecmRepasseCategoria->set('categoria', $this->request->data['categoria']);

                    if ($ecmRepasseCategoria = $this->EcmRepasseCategorias->save($ecmRepasseCategoria)) 
                        $this->request->data['ecm_repasse_categorias_id'] = $ecmRepasseCategoria->get('id'); 
                }
            }

            $checkRecapcha = false;
            if( $ecmRepasseCategoria->get('recaptcha') == 0 ){
                $checkRecapcha = true;
            }else if ($ecmRepasseCategoria->get('recaptcha') == 1){
                if(array_key_exists('recaptcha', $this->request->data)){
                    if(parent::validarCaptcha($this->request->data['recaptcha'])){
                        $checkRecapcha = true;
                    }
                }
            }

            if( $checkRecapcha ){

                $paramsEmail['repasse'] = $repasse = $this->EcmRepasse->newEntity($this->request->data, ['associated' => ['EcmRepasse.EcmRepasseUserData']]);

                if($this->EcmRepasse->save($repasse)){
                   $retorno = ['sucesso' => true];
    
                   if($ecmRepasseCategoria->get('email')){
                        $emailTemplate = $ecmRepasseCategoria->get('categoria');
                        $emailTemplate = lcfirst(str_replace(' ', '', $emailTemplate));
                        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
                        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
                        $params = [[$fromEmail => $fromEmailTitle], $adminEmail, $paramsEmail];
    
                        if($mail = $this->getMailer('Repasse.WscRepasse')->send($emailTemplate, $params)){
                            $corpo_email = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                            $repasse->set('corpo_email', $corpo_email);
                            $this->EcmRepasse->save($repasse);
                        }
                   }
                }
            }else{
                $retorno['mensagem'] = __('Captcha inválido');
            }
        }

        $this->set(compact('retorno'));
    }

    protected function validarDados(){
        $assunto = $this->request->data('assunto_email');
        $corpo = $this->request->data('corpo_email');
        $entidade = $this->request->data('entidade');
        $observacao = $this->request->data('observacao');

        $validaAlternativeHost = self::validaAlternativeHost();

        if(is_array($validaAlternativeHost))
            return $validaAlternativeHost;

        if (!is_null($assunto) && !is_null($corpo))
            $this->request->data['envio_email'] = "1";

        if (is_null($entidade) && (is_null($observacao) || trim($observacao) != "") && is_null($assunto) && is_null($corpo))
            return ['sucesso' => false, 'mensagem' => __('Cliente não identificado')];
    }
}