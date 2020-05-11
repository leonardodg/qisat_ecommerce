Prezado(a) <b> <?= $usuario->firstname.' '.$usuario->lastname?> </b>,<br/>
<br/>
Seu pedido <b><?= $pedido?></b> foi efetuado com sucesso no site <a href="www.qisat.com.br" >www.qisat.com.br </a><br/>
<br/>


<?php if(count($produtos) > 0):?>
    Os Produtos adquiridos foram:<br/>

    <?php foreach($produtos as $item):?>

            <?php 
                $app = $item->get('ecm_produto_ecm_aplicacao');
                $aplicacao = $app->get('ecm_produto_aplicacao');
                $produto = $app->get('ecm_produto');

                if(is_null($aplicacao) || (!is_null($item->valor) && $item->valor > 0)){
                    $nome = h($produto->nome);
                } else {
                    $nome = h($aplicacao->descricao);
                }
            ?>

            <b><?= $nome. ( $item->valor > 0 ? ' Valor: '.$item->quantidade.' x '.$this->Number->format($item->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) : '' ) ?></b><br/>

    <?php endforeach;?>
<?php endif;?>

<?php if(count($servicos) > 0):?>
    Os Cursos adquiridos foram:<br/>
    <?php foreach($servicos as $item):?>
        <b><?=  h($item->ecm_produto->nome). ( $item->valor > 0 ? ' Valor: '.$item->quantidade.' x '.$this->Number->format($item->valor_produto, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) : '' ) ?></b><br/>
    <?php endforeach;?>
<?php endif;?>

<br/>
Valor Total do Pedido: <b><?= $parcelas?>X de R$ <?= $valor?></b> <br/>
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