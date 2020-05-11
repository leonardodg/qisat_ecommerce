<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 24/02/2017
 * Time: 12:55
 */

namespace WebService\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Validation\Validator;


class WscSolicitarIdentidadeVisualController extends WscController
{

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
     * Deve ser feito requisições do tipo GET para:
     * http://{host}/newsletter/inserir/{email}
     *
     *
     * Retornos:
     * 1- {'sucesso':true}
     * 2- {'sucesso':false, 'mensagem': 'Este Web Service não aceita esse tipo de requisição'}
     * 3- {'sucesso':false, 'mensagem': 'Parâmetro nome não informado'}
     * 4- {'sucesso':false, 'mensagem': 'Parâmetro email não informado'}
     * 5- {'sucesso':false, 'mensagem': 'Parâmetro telefone não informado'}
     * 6- {'sucesso':false, 'mensagem': 'Parâmetro area não informado'}
     * 7- {'sucesso':false, 'mensagem': 'Erro ao salvar'}
     * 8- {'sucesso':false, 'mensagem': 'Não foi possível inserir'}
     * 9- {'sucesso':false, 'mensagem': 'Parâmetro entidade incorreto'}
     * 10- {'sucesso':false, 'mensagem': 'Entidade não encontrada'}
     *
     * */
    public function solicitar($email = null)
    {
        $retorno = ['sucesso' => false, 'mensagem' => __('E-mail não informado')];
        if (!is_null($email)) {

            $validaDados = $this->validarDados();
            if (is_array($validaDados)) {
                $retorno = $validaDados;
            } else {
                $this->loadModel('Configuracao.EcmConfig');

                $corpoEmail = 'Prezados, <br /><br />';
                $corpoEmail .= 'Foi solicitado o envio da identidade visual para o e-mail <b>'.$email.'</b>.';

                $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
                $emailDesign = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_design'])->first()->valor;
                $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
                
                $email = new Email('default');
                $email->from([$fromEmail => $fromEmailTitle])
                    ->to($adminEmail)
                    ->addTo($emailDesign)
                    ->emailFormat('html')
                    ->template('default')
                    ->subject('QiSat | Solicitação de Identidade Visual');

                if($email->send($corpoEmail)){
                    $retorno = ['sucesso' => true, 'mensagem' => __('Identidade visual solicitada com sucesso')];
                }else{
                    $retorno = [
                        'sucesso' => false,
                        'mensagem' => __('Erro ao solicitar identidade visual, por favor tente novamente!')
                    ];
                }

            }
        }

        $this->set(compact('retorno'));
    }

    protected function validarDados()
    {
        $validator = new Validator();
        $validator->email('email');

        $email = $this->request->param('pass')[0];

        $errors = $validator->errors(['email' => $email]);
        if (!empty($errors)) {
            return ['sucesso' => false, 'mensagem' => __('Informe um valor válido para o e-mail')];
        }
    }
}