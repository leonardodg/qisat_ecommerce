<?php
namespace FormaPagamentoSuperPayV3\Mailer;
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/02/2017
 * Time: 14:34
 */
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;

class FormaPagamentoSuperPayV3Mailer extends Mailer
{

    public function compraEfetuada($toEmail, $params){

        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $bcc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $this->from([$fromEmail => $fromEmailTitle])
            ->to($toEmail)
            ->addCc($cc)
            ->addBcc($bcc)
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoSuperPayV3.compraEfetuada')
            ->viewVars($params);

    }

}