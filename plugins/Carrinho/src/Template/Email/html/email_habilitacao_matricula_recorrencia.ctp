Prezados,<br><br>
Não foi possivel&nbsp;
<?php if($pago): ?>
    alterar o status do
<?php else: ?>
    deletar o
<?php endif; ?>
&nbsp;curso <?= $mdlCourse->fullname ?> (id: <?= $mdlCourse->id ?>)&nbsp;
do aluno <?= $mdlUser->firstname ?> <?= $mdlUser->lastname ?> (id: <?= $mdlUser->id ?>).
