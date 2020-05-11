<?php
namespace FormaPagamentoBoletoPhp\Mailer;
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/02/2017
 * Time: 14:34
 */
use Cake\Mailer\Mailer;

class FormaPagamentoBoletoPhpMailer extends Mailer
{

    public function compraEfetuada($fromEmail, $toEmail, $params){
        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoBoletoPhp.compraEfetuada')
            ->viewVars($params);
    }

}