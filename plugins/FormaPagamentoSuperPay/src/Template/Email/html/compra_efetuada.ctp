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
Seus dados para acesso ao Ambiente Pessoal são:<br/>
Chave AltoQi/QiSat: <b><?= $usuario->username?></b><br/>
Senha: <b><?= $senha?></b><br/>
<br/>
<ul>
    <li>
        A equipe QiSat entrará em contato com você por telefone e/ou e-mail em até 48 horas para confirmar seus dados de compra e garantir que a sua escolha foi a melhor solução.<br>
    </li>
    <li>
        Durante o contato você poderá tirar possíveis dúvidas e agendar a data de início do curso.<br>
    </li>
    <li>
        Para acessar o curso faça login em <a href="www.qisat.com.br">www.qisat.com.br</a> e acesse a área do aluno. No ambiente pessoal clique na opção “Meus Cursos”.<br>
    </li>
</ul>
<br/>
Se deseja falar diretamente com a empresa, poderá fazê-lo através da Central de Inscrições.<br/>