Foram enviados os e-mails de confimação de edição e inscrição para os participantes do
<b>Curso <?= $nomeCurso ?></b> em <?= $cidadeUF ?> nas datas de <b> <?= $data ?> </b>.
<br /><br />
<b> Local: </b> <?= $nomeLocal ?> <br />
<?= $endereco ?> <br />
<?= $cidadeUF ?> <br /><br />
<?php if(isset($listaParticipantes)): ?>
    <u>E-mails enviados para:</u><br />
    <?= $listaParticipantes ?>
<?php endif; ?>