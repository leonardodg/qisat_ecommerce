<?php
namespace FormaPagamentoSuperPay\Mailer;
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/02/2017
 * Time: 14:34
 */
use Cake\Mailer\Mailer;

class FormaPagamentoSuperPayMailer extends Mailer
{

    public function compraEfetuada($fromEmail, $toEmail, $params){
        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoSuperPay.compraEfetuada')
            ->viewVars($params);
    }

}