<div style="padding:20px 0px 8px 20px; line-height:125%; color:#015da2; font-size:14px;text-align:left; font-weight:bolder;">
    Novo Cadastro De Pessoa
    <?= $usuario->mdl_user_dados->tipousuario == 'fisico'? 'Física' : 'Jurídica'?>
    Efetuado
</div>
<br /><br />

Nome: <?= $usuario->firstname.' '.$usuario->lastname?><br />
CPF: <?= $usuario->numero?><br />
Chave: <?= $usuario->idnumber?><br />
E-mail: <?= $usuario->email?><br />
Telefone 1: <?= $usuario->phone1?><br />
Telefone 2: <?= $usuario->phone2?><br />
Endereco: <?= $usuario->address?><br />
Bairro: <?= $usuario->mdl_user_endereco->district?><br />
Cidade: <?= $usuario->city?><br />
Estado: <?= $usuario->mdl_user_endereco->state?><br />
CEP: <?= $usuario->mdl_user_endereco->cep?><br />
Pais: <?= $usuario->country?><br />
IP Origem: <?= $usuario->lastip?><br />
Data/Hora: <?= date('d/m/Y H:i:s', $usuario->timecreated)?>
<br/><br/>

<?php
    if($mensagem != '') {
        echo '<b> Atenção:</b> <br />';
        echo $mensagem;
    }
?>
