<?= $this->MutipleSelect->getScript();?>

<?php
$attrPermissao = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'multipleWidth' => '250');

$scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#ecm-produto-ids',$attrPermissao));
echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmTipoProduto col-md-12">
    <?= $this->Form->create($ecmTipoProduto) ?>
    <fieldset>
        <legend><?= __('Novo Tipo de Produto') ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];

            echo $this->Form->input('nome');
            echo $this->Form->input('ecm_tipo_produto_id',['label'=>__('Tipo de Produto'), 'options' => $optionsTipoProduto]);
            echo $this->Form->input('habilitado',['label'=>__('Habilitado'), 'options' => $optionsHabilitado]);
            echo $this->Form->input('blocked',[
                'label'=>__('Manter este Tipo de Produto Bloqueado, ou seja, este tipo de produto não pode ser excluído'),
                'options' => $optionsHabilitado
            ]);

            echo $this->Form->input('ecm_produto._ids', ['label'=>__('Produtos'),'options' => $optionsProduto]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
