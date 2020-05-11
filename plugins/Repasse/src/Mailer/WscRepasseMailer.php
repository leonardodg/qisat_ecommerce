<?php
namespace Repasse\Mailer;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

/**
 * RepasseMailer mailer.
 */
class WscRepasseMailer extends Mailer
{

    public function downloadIdentidade($fromEmail, $toEmail, $params)
    {

        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_design'])->first()->valor;

        $this->from($fromEmail)
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Solicitação de Identidade Visual')
            ->emailFormat('html')
            ->template('Repasse.downloadIdentidade')
            ->viewVars($params);

        if(isset($params['repasse']))
            $params['repasse']->set('assunto_email','QiSat | Solicitação de Identidade Visual');
    }

    public function rdstation($params)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $toEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $this->from([$fromEmail => $fromEmailTitle])
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Integração RDStation')
            ->emailFormat('html')
            ->template('Repasse.rdstation')
            ->viewVars($params);

        if(isset($params['repasse']))
            $params['repasse']->set('assunto_email','QiSat | Integração RDStation');
    }

    public function mensagemContato($fromEmail, $toEmail, $params)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;

        $this->from($fromEmail)
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Mensagem enviada através da página de contato')
            ->emailFormat('html')
            ->template('Repasse.mensagemContato')
            ->viewVars($params);

        if(isset($params['repasse']))
            $params['repasse']->set('assunto_email','QiSat | Mensagem enviada através da página de contato');
    }

    public function interesseCursoPresencial($fromEmail, $toEmail, $params)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;

        $this->from($fromEmail)
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Registro de Interesse - Curso Presencial')
            ->emailFormat('html')
            ->template('Repasse.interesseCursoPresencial')
            ->viewVars($params);

        if(isset($params['repasse']))
            $params['repasse']->set('assunto_email','QiSat | Registro de Interesse - Curso Presencial');
    }

    public function ligamosParaVoce($fromEmail, $toEmail, $params)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;

        $this->from($fromEmail)
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Solicitação de contato')
            ->emailFormat('html')
            ->template('Repasse.ligamosParaVoce')
            ->viewVars($params);

        if(isset($params['repasse']))
            $params['repasse']->set('assunto_email','QiSat | Solicitação de contato');
    }
}
