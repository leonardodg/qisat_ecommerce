Prezados,<br /><br />
<strong>Nome: </strong> <?= $nome ?> <br />
<strong>E-mail: </strong> <?= $email ?> <br />
<strong>Telefone: </strong><?= $telefone ?> <br />
<p> Cliente resgistrou interesse no curso <strong> <?= $curso ?> </strong> </p>

<?php 

    if(isset($categoria)) 
        echo 'Categoria: '.$categoria; 
    else if(isset($turma))
        echo 'Turma: '.$turma; 

?>