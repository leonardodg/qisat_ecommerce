<div class="ecmPublicidade col-md-12">
    <?= $this->Form->create($ecmPublicidade, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Edit Publicidade') ?></legend>
        <?php
            echo $this->Form->input('tipo', ['options' => $tipo, 'onchange' => 'listarprodutos(this)']);
            echo $this->Form->input('nome');
            echo $this->Form->input('habilitado');
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
    listarprodutos($("#tipo"));
    function listarprodutos(select){
        if($(select).val() == "Convite"){
            $("#ecm-produto-id").parent().show();
        } else {
            $("#ecm-produto-id").parent().hide();
        }
    }
    var hash = '#elf_l1_<?= $hash_path ?>';
    if(window.location.href.indexOf(hash) == -1){
        window.location.href = "/publicidade/ecm-publicidade/edit/<?= $ecmPublicidade->id ?>"+hash;
    }
</script>
