<div class="ecmCarrinho col-md-12">
    <?= $this->element('Carrinho.comprando_para',['usuario'=>$usuario]);?>

    <fieldset>
    <b>Compra Efetuada com Sucesso!
        <br>Pedido: <?= isset($venda->pedido)?$venda->pedido:$venda->id ?>
        <br>Instruções para Cursos:</b>
        No prazo máximo de 48 horas* será encaminhado um e-mail informando a habilitação de acesso ao curso.
        Acompanhe a situação de seus cursos através de seu ambiente pessoal.<br><br>
    <b>Para compras através de boleto bancário deve-se considerar:</b><br>
        1)Nos Cursos à Distância a contagem dos prazos inicia após a confirmação de pagamento.
        Se desejar falar diretamente com a empresa, poderá fazê-lo através da Central de Inscrições.<br><br>
        <b>Central de Inscrições:</b> (48) 3332-5000 / Fax: (48) 3332-5010 <br>
        <b>E-mail: </b><a href='mailto:qisat@qisat.com.br'>qisat@qisat.com.br </a><br><br>
    </fieldset>

    <?= $this->Form->create() ?>
        <?= $this->Form->button(__("Lista de Usuários"), ['name' => 'avancar', "type" => "button", "onclick" => "location.href='../usuario/listar-usuario'"]) ?>
    <?= $this->Form->end() ?>
</div>
