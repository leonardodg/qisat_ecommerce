<?php
namespace Convenio\Mailer;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

/**
 * WscUser mailer.
 */
class WscConvenioMailer extends Mailer
{
    public function solicitacaoConvenio($convenio)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $toEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $this->from([$fromEmail => $fromEmailTitle])
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Preenchimento de Termo de Adesão')
            ->emailFormat('html')
            ->template('Convenio.solicitacaoConvenio')
            ->viewVars(['convenio' => $convenio]);
    }

    public function solicitacaoDescontoConvenio($convenioInteresse)
    {
        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $toEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $this->from([$fromEmail => $fromEmailTitle])
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Registro de Interesse')
            ->emailFormat('html')
            ->template('Convenio.solicitacaoDescontoConvenio')
            ->viewVars(['convenioInteresse' => $convenioInteresse]);
    }
}
