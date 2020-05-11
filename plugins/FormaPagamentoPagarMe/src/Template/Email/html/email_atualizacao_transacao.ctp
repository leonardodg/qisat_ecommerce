Prezados,
<br/><br/>
A transação de número <?= $result['id'] ?> do
 usuário <?= $mdlUser->get('firstname').' '.$mdlUser->get('lastname') ?>
 chave <?= $mdlUser->get('idnumber') ?> foi alterada.
 
 <!--br/><br/>Informações da transação vindas do Pagar-me:<br/><pre--><?php //echo print_r($result) ?>
