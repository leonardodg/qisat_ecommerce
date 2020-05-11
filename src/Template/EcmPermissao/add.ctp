<?= $this->MutipleSelect->getScript();?>

<?php
$attrPermissao = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'multipleWidth' => '250');

$scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#ecm-grupo-permissao-ids',$attrPermissao));
echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmPermissao col-md-12">
    <?= $this->Form->create($ecmPermissao) ?>
    <fieldset>
        <legend><?= __('Nova Permissão') ?></legend>
        <?php

            $optionsRestricao = ['login'=>__('Login'),'permissao'=>__('Permissão'),'site'=>__('Site')];

            echo $this->Form->input('action');
            echo $this->Form->input('controller');
            echo $this->Form->input('plugin');
            echo $this->Form->input('label');
            echo $this->Form->input('descricao',['label'=>__('Descrição')]);
            echo $this->Form->input('restricao',['label'=>__('Restrição'), 'options' => $optionsRestricao]);

            echo $this->Form->input('acesso_total',[
                'label' => [
                    'text' => __('Acesso Total').' <b>('.__('Permite que o acesso seja feito sem restrição de requisição').')</b>',
                    'escape' => false
                ],
                'type' => 'checkbox',
                'value' => 1
                ,
                'options' => $optionsRestricao
            ]);
        
            echo $this->Form->input('ecm_grupo_permissao._ids', ['label'=>__('Permissão'), 'options' => $optionsGrupoPermissao]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
