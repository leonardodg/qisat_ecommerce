<?php
namespace FormaPagamentoPagarMe\Mailer;
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 10/12/2019
 * Time: 13:00
 */
use Cake\Mailer\Mailer;

class FormaPagamentoPagarMeMailer extends Mailer
{

    public function compraEfetuada($fromEmail, $toEmail, $params){
        $this->from([$fromEmail => 'QiSat - O Canal de e-Learning da Engenharia'])
            ->to($toEmail)
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoPagarMe.compraEfetuada')
            ->viewVars($params);
    }

}