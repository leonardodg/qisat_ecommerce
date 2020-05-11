<?= $this->JqueryUI->getScript();?>
<?= $this->JqueryMask->getScript();?>

<?php
$script = $this->JqueryMask->maskTelefone('#telefone');
$script = $this->Jquery->domReady($script);

echo $this->Html->scriptBlock($script);
?>

<div class="ecmCursoPresencialInteresse col-md-12">
    <?= $this->Form->create($ecmCursoPresencialInteresse) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('email', ['label' => __('E-mail')]);
            echo $this->Form->input('telefone');
            echo $this->Form->input('ecm_produto_id', ['label' => __('Curso Presencial'), 'options' => $ecmProduto]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
