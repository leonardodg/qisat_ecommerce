<?php
namespace Vendas\Mailer;

use Cake\Mailer\Mailer;

/**
 * EcmVendaPresencial mailer.
 */
class EcmVendaPresencialMailer extends Mailer
{
    public function confirmacaoEdicaoInscricao($fromEmail, $toEmail, $params)
    {
        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('QiSat | Confirmação da Edição e Inscrição no Curso ' . $params['nomeCurso'])
            ->emailFormat('html')
            ->template('Vendas.confirmacaoEdicaoInscricao')
            ->viewVars($params);
    }

    public function confirmacaoEdicaoInscricaoAdmin($fromEmail, $toEmail, $params, $cc)
    {
        $this->from($fromEmail)
            ->to($toEmail)
            ->addCc($cc)
            ->subject('QiSat | Confirmação da Edição e Inscrição no Curso ' . $params['nomeCurso'])
            ->emailFormat('html')
            ->template('Vendas.confirmacaoEdicaoInscricaoAdmin')
            ->viewVars($params);
    }
}
