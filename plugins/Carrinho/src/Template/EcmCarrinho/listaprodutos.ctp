<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->MutipleSelect->getScript();?>

<?php

$attrPermissao = array(
    'filter' => 'true',
    'multiple' => 'true',
    'position' => '"top"');

$multiselect = $this->MutipleSelect->multipleSelect('#ecm-tipo-produto-ids',$attrPermissao);
$scripts = $this->Jquery->domReady($multiselect);
echo $this->Html->scriptBlock($scripts);

?>

<div class="ecmCarrinho medium-12 large-12 columns content">
    <?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='index/".$usuario->id."'"]) ?>
    <?= $this->Form->button(__("Retorna á Lista Usuários"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='../mdl-user/listar-usuario'"]) ?>
    <?= $this->Html->link('Montar Carrinho', '/carrinho/montarcarrinho', ['class' => 'button right']) ?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>
    <h3><?= __('Lista de Produtos') ?></h3>
    <?= $this->Html->link('Cursos Online', '/carrinho/listaprodutos', ['class' => 'button active']) ?>
    <?= $this->Html->link('Cursos Presenciais', '/carrinho/listaprodutos/presencial', ['class' => 'button']) ?>
    <?= $this->Html->link('Produto AltoQi', '/carrinho/produtos_altoqi/', ['class' => 'button']) ?>
    <legend><?= __('Filtro') ?></legend>
    <?= $this->Form->create() ?>
    <fieldset>
        <div class="medium-6 large-6 columns">
        <?= $this->Form->input('produto', ['label' => 'Produto']) ?>
        </div>
        <div class="medium-6 large-6 columns">
        <?= $this->Form->input('sigla', ['label' => 'Sigla']) ?>
        </div>
        <div class="medium-10 large-10 columns">
        <?= $this->Form->input('ecm_tipo_produto._ids', ['options' => $optionsTipoProduto, 'label' => __('Selecione a Categoria')]) ?>
        </div>
        <div class="medium-2 large-2 columns">
        <?= $this->Form->button('Buscar', array('style' => 'float: right; margin-top: 10px;'),['type' => 'submit']) ?>
        </div>
    </fieldset>
    <?= $this->Form->end() ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>
    </div>
    <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveProdutoList">
        <thead>
            <tr>
                <th width="10%"><?= $this->Paginator->sort('sigla', null, [
                        'url' => ['controller' => false]]) ?></th>
                <th width="40%"><?= $this->Paginator->sort('nome', null, [
                        'url' => ['controller' => false]]) ?></th>
                <th width="10%" ><?= $this->Paginator->sort('preco', null, [
                        'url' => ['controller' => false]]) ?></th>
                <th width="30%"><?= h('Tipos do Produto') ?></th>
                <th width="10%" ><?= h('Ação') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmProduto as $value): ?>
            <tr>
                <td> <?= $value->sigla ?> </td>
                <td> <?= $value->nome ?> </td>
                <td> R$ <?= $this->Number->precision($value->preco, 2) ?> </td>
                <td>  <?php foreach ($value->ecm_tipo_produto as $tipo): echo $tipo->nome.'<br>'; endforeach; ?>  </td>
                <td> <input type="button" data-id="<?= $value->id ?>" value="Adicionar"></div> </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>
    </div>
    <?= $this->Html->link('Montar Carrinho', '/carrinho/montarcarrinho', ['class' => 'button right']) ?>

</div>
<script>
    $("input[type='button']").click(function() {
        var id = $(this).attr("data-id");
        $.post("add", {"produto": id}, function(data) {
            if(!data.sucesso){
                bootbox.alert(data.mensagem);
            }else{
                bootbox.alert("Produto Adicionado com Sucesso!");
            }
        }, "json");
    });
</script>