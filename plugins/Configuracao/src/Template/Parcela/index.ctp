<?= $this->JqueryMask->getScript();?>

<?php

$scripts = $this->JqueryMask->mask('#valor-minimo-parcela',['#.##0,00', ['reverse' => true]]);
$scripts .= $this->JqueryMask->mask('#maximo-numero-parcela',['##', ['reverse' => true]]);
$scripts = $this->Jquery->domReady($scripts);

echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmPermissao col-md-12">
    <?= $this->Form->create($ecmConfig) ?>
    <fieldset>
        <legend><?= __('Configuração do Valor das Parcelas') ?></legend>
        <?php
        echo $this->Form->input('valor_minimo_parcela', [
            'label' => __('Valor mínimo das Parcelas R$'),
            'value'=>$valorMinimo,
            'maxlength' => '6',
            'required' => true
        ]);
        echo $this->Form->input('maximo_numero_parcela', [
            'label' => __('Número máximo de parcelas'),
            'value'=>$minimoParcelas,
            'maxlength' => '2',
            'required' => true
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
