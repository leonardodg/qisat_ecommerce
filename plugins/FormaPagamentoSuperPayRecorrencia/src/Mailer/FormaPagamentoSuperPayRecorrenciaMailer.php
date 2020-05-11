<?php
namespace FormaPagamentoSuperPayRecorrencia\Mailer;
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/02/2017
 * Time: 14:34
 */
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

class FormaPagamentoSuperPayRecorrenciaMailer extends Mailer
{

    public function compraEfetuada($toEmail, $params){

        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc1 = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $cc2 = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $cc3 = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_financeiro'])->first()->valor;
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $this->from([$fromEmail => $fromEmailTitle])
            ->to($toEmail)
            ->bcc(array($cc1, $cc2, $cc3))
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoSuperPayRecorrencia.compraEfetuada')
            ->viewVars($params);
    }

}