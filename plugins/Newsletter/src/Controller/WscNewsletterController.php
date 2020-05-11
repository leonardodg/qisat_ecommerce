<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 12/09/2016
 * Time: 13:20
 */

namespace Newsletter\Controller;


use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class WscNewsletterController extends WscController
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
    public function inserir($email = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('E-mail não informado')];
        if (!is_null($email)) {
            $retorno = ['sucesso' => false, 'mensagem' => __('E-mail não pode ser salvo, por favor tente novamente')];

            $validaDados = $this->validarDados();
            if(is_array($validaDados)) {
                $retorno = $validaDados;
            }else{
                $this->loadModel('Newsletter.EcmNewsletter');

                if(!$this->EcmNewsletter->exists(['email' => $email])) {
                    $ecmNewsletter = $this->EcmNewsletter->newEntity();
                    $ecmNewsletter->set('email', $email);

                    if ($this->EcmNewsletter->save($ecmNewsletter)) {
                        $retorno = ['sucesso' => true, 'mensagem' => __('E-mail salvo com sucesso')];
                    }
                }else{
                    $retorno = ['sucesso' => true, 'mensagem' => __('E-mail salvo com sucesso')];
                }
            }
        }

        $this->set(compact('retorno'));
    }

    protected function validarDados(){
        $validator = new Validator();
        $validator->email('email');

        $email = $this->request->param('pass')[0];

        $errors = $validator->errors(['email' => $email]);
        if(!empty($errors)){
            return ['sucesso' => false, 'mensagem' => __('Informe um valor válido para o e-mail')];
        }
    }

}