<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 12/09/2016
 * Time: 13:20
 */

namespace Repasse\Controller;


use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Validation\Validator;
use Repasse\Model\Entity\EcmRepasse;

class WscLigamosParaVoceController extends WscController
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
     * Deve ser feito requisições do tipo POST, informando os seguintes parâmetros no formato JSON:
     * {
     *  nome: (nome),
     *  telefone: (telefone),
     *  email: (e-mail),
     *  area: (Área de Interesse),
     *  entidade:(id entidade de origem)
     * }
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
    public function salvar(){
        $retorno = ['sucesso' => false, 'mensagem' => __('Este Web Service não aceita esse tipo de requisição')];
        if ($this->request->is('post')) {
            $retorno = ['sucesso' => false, 'mensagem' => __('Erro ao salvar')];

            $validaDados = $this->validarDados();
            if(is_array($validaDados)) {
                $retorno = $validaDados;
            }else{
                $this->loadModel('Repasse.EcmRepasse');
                $this->loadModel('Entidade.EcmAlternativeHost');

                $entidade = $this->EcmAlternativeHost->get($this->validaAlternativeHost());

                $repasse = $this->EcmRepasse->newEntity();

                $textoEmail = __('Prezado(a) ').$this->request->data('nome');
                $textoEmail .= ',<br /><br />';
                $textoEmail .= __('Sua solicitação foi enviada. A equipe QiSat entrará em contato em breve.');
                $textoEmail .= '<br /><br />';
                $textoEmail .= __('Solicitação de contato através da ferramenta "Ligamos para você"');
                $textoEmail .= '<br /><br />';
                $textoEmail .= '<b>'.__('Nome').'</b>: '.$this->request->data('nome');
                $textoEmail .= '<br /><b>'.__('Telefone').'</b>: '.$this->request->data('telefone');
                $textoEmail .= '<br /><b>'.__('E-mail').'</b>: '.$this->request->data('email');
                $textoEmail .= '<br /><b>'.__('Área de Interesse').'</b>: '.$this->request->data('area');
                $textoEmail .= '<br /><b>'.__('Origem').'</b>: '.$entidade->get('shortname');

                $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
                $repasse->set('assunto_email', 'QiSat | '.__('Solicitação de contato'));
                $repasse->set('corpo_email',$textoEmail );
                $repasse->set('ecm_alternative_host_id', $entidade->get('id'));

                if ($this->EcmRepasse->save($repasse)) {
                    $this->enviarEmail('QiSat | '.__('Solicitação de contato'), $textoEmail);

                    $retorno = ['sucesso' => true];
                }else{
                    $retorno = ['sucesso' => false, 'mensagem' => __('Não foi possível inserir')];
                }
            }
        }

        $this->set(compact('retorno'));
    }

    private function enviarEmail($assuntoEmail, $corpoEmail){
        $this->loadModel('Configuracao.EcmConfig');

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $emailCentral = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
        
        $email = new Email('default');
        $email->from([$fromEmail => $fromEmailTitle])
            ->to($adminEmail)
            ->addTo($emailCentral)
            ->emailFormat('html')
            ->template('default')
            ->subject($assuntoEmail)
            ->send($corpoEmail);
    }

    protected function validarDados(){
        $nome = $this->request->data('nome');
        $mail = $this->request->data('email');
        $telefone = $this->request->data('telefone');
        $area = $this->request->data('area');

        $validaAlternativeHost = self::validaAlternativeHost();

        if(is_array($validaAlternativeHost)) {
            return $validaAlternativeHost;
        }

        if (is_null($nome)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro nome não informado')];
        }

        if (is_null($mail)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro email não informado')];
        }

        if (is_null($telefone)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro telefone não informado')];
        }

        if (is_null($area)) {
            return ['sucesso' => false, 'mensagem' => __('Parâmetro area não informado')];
        }

        $validator = new Validator();
        $validator->email('email');

        $errors = $validator->errors($this->request->data());
        if(!empty($errors)){
            return ['sucesso' => false, 'mensagem' => __('Informe um valor válido para o e-mail')];
        }
    }

}