Prezado(a) <b> <?= $usuario->firstname.' '.$usuario->lastname?> </b>,<br/>
<br/>
Seu pedido <b><?= $pedido?></b> foi efetuado com sucesso no site <a href="www.qisat.com.br" >www.qisat.com.br </a><br/>
<br/>
<?php if(\Carrinho\Model\Entity\EcmCarrinho::countItems($produtos)):?>
    O curso adquirido foi:<br/>
<?php else: ?>
    Os cursos adquiridos foram:<br/>
<?php endif;?>

<?php foreach($produtos as $produto):?>
    <?php if(($produto->get('status')) == "Adicionado"):?>
        <?php $isTrilha = \Produto\Model\Entity\EcmProduto::isTrilha($produto->get('ecm_produto')->get('ecm_tipo_produto'));?>
        <?php if(($produto->get('ecm_produto')->get('mdl_course')) > 1 && !$isTrilha):?>
            <?php foreach($produto->get('ecm_produto')->get('mdl_course') as $curso):?>
                <b><?= $curso->get('fullname')?></b><br/>
            <?php endforeach;?>
        <?php else: ?>
            <b>
            <?php if($isTrilha):?>
                AltoQi LAB -&nbsp;
            <?php endif;?>
            <?= $produto->get('ecm_produto')->get('nome')?></b><br/>
        <?php endif;?>
    <?php endif;?>
<?php endforeach;?>

<br/>
Valor Total do Pedido: <b>R$ <?= $valor?></b> <br/>
<br/>
A forma de pagamento escolhida foi:<br/>
Boleto bancário <br/>
1 parcelas de <?= $valor?> <br/>
<br/>
Caso precise reimprimir o seu boleto de pagamento clique no link abaixo: <br/>
<a href="<?= $linkBoleto?>" > <b>Boleto 1/1 vencimento em <?= $vencimento?></b> </a>
<br/>
Seus dados para acesso ao Ambiente Pessoal são:<br/>
Chave AltoQi/QiSat: <b><?= $usuario->username?></b><br/>
Senha: <b><?= $senha?></b><br/>
<br/>
<?php if(!isset($isTrilha) || !$isTrilha):?>
Assim que for confirmado o pagamento do boleto, será encaminhado e-mail de habilitação de acesso aos cursos.<br/>
Consulte a situação dos cursos adquiridos no Ambiente Pessoal.<br/>
Para acessá-los faça login em <a href="www.qisat.com.br">www.qisat.com.br</a> e acesse a área do aluno.<br/>
<br/>
<?php endif;?>
<ul>
    <li>
        Assim que for confirmado o pagamento do boleto, será aprovado seu pedido. Você poderá escolher uma data de início em até 60 dias e no dia agendado será encaminhado e-mail de habilitação de acesso aos cursos.
    </li>
    <li>
        Após habilitação de acesso na data agendada, você poderá consultar a situação dos cursos adquiridos no ambiente Área do aluno.
    </li>
    <li>
        Para acessá-los faça login em <a href="www.qisat.com.br">www.qisat.com.br</a> e acesse a Área do Aluno.
    </li>
</ul>
<br/>
Se deseja falar diretamente com a empresa, poderá fazê-lo através da Central de Inscrições.<br/>