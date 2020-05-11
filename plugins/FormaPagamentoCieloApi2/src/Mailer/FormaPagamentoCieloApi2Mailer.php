<?php
namespace FormaPagamentoCieloApi2\Mailer;
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 16/02/2017
 * Time: 14:34
 */
use Cake\Mailer\Mailer;

class FormaPagamentoCieloApi2Mailer extends Mailer
{

    public function compraEfetuada($fromEmail, $toEmail, $params){
        $this->from([$fromEmail => 'QiSat - O Canal de e-Learning da Engenharia'])
            ->to($toEmail)
            ->subject('QiSat | Confirmação de Pedido')
            ->emailFormat('html')
            ->template('FormaPagamentoCieloApi2.compraEfetuada')
            ->viewVars($params);
    }

}