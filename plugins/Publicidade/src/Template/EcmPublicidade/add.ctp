<div class="ecmPublicidade col-md-12">
    <?= $this->Form->create($ecmPublicidade, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Add Publicidade') ?></legend>
        <?php
            echo $this->Form->input('tipo', ['options' => $tipo, 'onchange' => 'listarprodutos(this)']);
            echo $this->Form->input('nome');
            echo $this->Form->input('habilitado', ['checked' => 'checked']);
            echo $this->Form->input('ecm_produto_id', ['label' => __('Produto'), 'options' => $ecmProduto]);
            echo $this->Form->input('src', ['ReadOnly']);
            echo $this->Form->input('arquivo', ['ReadOnly']);
            echo $this->element('Publicidade.elFinderPublicidade');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $("#ecm-produto-id").parent().hide();
    function listarprodutos(select){
        if($(select).val() == "Convite"){
            $("#ecm-produto-id").parent().show();
        } else {
            $("#ecm-produto-id").parent().hide();
        }
    }
</script>
