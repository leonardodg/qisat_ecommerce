<?= $this->MutipleSelect->getScript();?>

<?php
$attrPermissao = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'position' => '"top"',
    'multipleWidth' => '250');

$scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#ecm-permissao-ids',$attrPermissao));
$scripts .= $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#mdl-user-ids',$attrPermissao));
echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmGrupoPermissao col-md-12">
    <?= $this->Form->create($ecmGrupoPermissao) ?>
    <fieldset>
        <legend><?= __('Novo Grupo de Permissão') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('descricao',['label'=>__('Descrição')]);

            echo $this->Html->tag('div', null, ['class' => 'input checkbox']);
            echo $this->Html->tag('label', $this->Form->checkbox('acesso_total') . __('Acesso total ao sistema'));
            echo $this->Html->tag('/div');

            echo $this->Form->input('ecm_permissao._ids', ['label'=>__('Permissão'), 'options' => $optionsPermissao]);

            echo $this->Html->tag('fieldset');

            echo $this->Html->tag('legend', __('Selecionar Usuário'));

            echo $this->Html->tag('div',__('Atenção: para selecionar os usuários faça uma busca pelo nome do usuário e
            selecione no campo "Selecione o Usuário", abaixo será listado os campos selecionados em "Usuários Selecionados"'));

            echo $this->Html->tag('br /', null);

            echo $this->Form->input('buscar_usuario', ['label' => __('Buscar Usuário')]);
            echo $this->Form->input('select_usuario', ['label'=>__('Selecione o Usuário'), 'options' => []]);

            echo $this->Form->input('mdl_user._ids', ['label'=>__('Usuários Selecionados'), 'options' => []]);
            echo $this->Html->tag('/fieldset');

            echo $this->Html->tag('div', null, ['class' => 'input text required']);
            echo $this->Html->tag('label', __('Empresa'), ['for' => 'ecm_alternative_host_id']);
            echo $this->Form->select('ecm_alternative_host_id', $ecmAlternativeHost);
            echo $this->Html->tag('/div');

            echo $this->Html->tag('div', null, ['class' => 'input checkbox']);
            echo $this->Html->tag('label', $this->Form->checkbox('atendente') . __('Atendente'));
            echo $this->Html->tag('/div');

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->scriptBlock($this->UserScript->selectUserAjax('#buscar-usuario', '#select-usuario', '#mdl-user-ids')); ?>

