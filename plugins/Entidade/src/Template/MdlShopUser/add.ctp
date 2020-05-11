<?= $this->JqueryMask->getScript(); ?>

<?php

$script = $this->Jquery->domReady($this->JqueryMask->mask('#cpf-cnpj', ['999.999.999-99']));
echo $this->Html->scriptBlock($script);

?>

<div class="mdlShopUser col-md-12">
    <?= $this->Form->create($mdlShopUser) ?>
    <fieldset>
        <legend><?= $titulo?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('cpf_cnpj', ['label' => __('CPF'), 'maxlength' => 14]);
            echo $this->Form->input('crea', ['label' => __('Número do CREA')]);
            echo $this->Form->input('adimplente', ['options' => [ 1 => __('Sim'), 2 => __('Não')]]);
            echo $this->Form->input('ecm_alternative_host_id', ['label' => __('Entidade'), 'options' => $ecmAlternativeHost, 'empty' => __('Selecione')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
