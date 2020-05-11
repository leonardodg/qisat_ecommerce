<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * WscUser mailer.
 */
class WscUserMailer extends Mailer
{
    public function lembreteSenha($fromEmail, $toEmail, $params)
    {
        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('Lembrete de Senha')
            ->emailFormat('html')
            ->template('lembreteSenha')
            ->viewVars($params);
    }

    public function lembreteSenhaAdm($fromEmail, $toEmail, $params)
    {
        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('Lembrete de Senha')
            ->emailFormat('html')
            ->template('lembreteSenhaAdm')
            ->viewVars($params);
    }

    public function novoCadastro($fromEmail, $toEmail, $params)
    {

        $this->from($fromEmail)
            ->to($toEmail)
            ->subject('QiSat | Novo Cadastro Efetuado')
            ->emailFormat('html')
            ->template('novoCadastro')
            ->viewVars($params);
    }
}
