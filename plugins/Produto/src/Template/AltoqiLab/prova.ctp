<?= $this->element('Produto.addProduto') ?>
<?= $this->element('Produto.editTipoProduto') ?>
<?= $this->element('Imagem.Imagem') ?>

<?php
    $options = ['options' => $mdlFase,
        'label' => 'Selecione a fase relacionada a esta prova', 'required' => true];
    if(!empty($ecmProduto->ecm_produto_ecm_produto))
        $options['default'] = $ecmProduto->ecm_produto_ecm_produto[0]->ecm_produto_relacionamento_id;

    echo $this->Form->input('mdl_fase', $options);
?>

<script>
    $(function() {
        $("#51").prop("checked", true).attr("disabled", "disabled");
    });
</script>

<?= $this->element('Produto.addProdutoSubmit') ?>
