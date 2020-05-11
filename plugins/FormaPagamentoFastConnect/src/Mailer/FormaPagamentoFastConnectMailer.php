<?php
namespace FormaPagamentoFastConnect\Mailer;

use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;

class FormaPagamentoFastConnectMailer extends Mailer
{

    public function compraEfetuadaQiSat($toEmail, $params){

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
            ->template('FormaPagamentoFastConnect.compraEfetuadaQisat')
            ->viewVars($params);

    }

    public function compraEfetuadaAltoQi($toEmail, $params){

        $this->EcmConfig = TableRegistry::get('Configuracao.EcmConfig');
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_central_inscricoes'])->first()->valor;
        $bcc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;

        $this->from([ "store@altoqi.com.br" => "AltoQi - Tecnologia Aplicada a Engenharia"])
            ->to($toEmail)
            ->addCc([$cc, 'store@altoqi.com.br'])
            ->addBcc($bcc)
            ->transport('altoqi')
            ->subject('AltoQi | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoFastConnect.compraEfetuadaAltoqi', 'altoqi')
            ->viewVars($params);

    }

}