<div class="ecmRepasse col-md-12">
    <?= $this->Form->create($ecmRepasse, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Adicionar Repasse') ?></legend>
        <?php
            echo $this->Html->tag('fieldset');
            echo $this->Html->tag('legend', __('Selecionar Atendente'));
            echo $this->Html->tag('div',__('Atenção: para selecionar os usuários faça uma busca pelo nome do usuário e
                    selecione no campo "Selecione o Usuário"'));
            echo $this->Html->tag('br /', null);
            echo $this->Form->input('buscar_usuario', ['label' => __('Buscar Atendente')]);
            echo $this->Form->input('mdl_user_id', ['label'=>__('Selecione o Atendente'), 'options' => []]);
            echo $this->Html->tag('/fieldset');

            echo $this->Html->tag('fieldset');
            echo $this->Html->tag('legend', __('Selecionar Cliente'));
            echo $this->Html->tag('div',__('Atenção: para selecionar os usuários faça uma busca pelo nome do usuário e
                        selecione no campo "Selecione o Usuário"'));
            echo $this->Html->tag('br /', null);
            echo $this->Form->input('buscar_entidade', ['label' => __('Buscar Cliente')]);
            echo $this->Form->input('mdl_user_cliente_id', ['label'=>__('Selecione o Cliente'), 'options' => []]);
            echo $this->Html->tag('/fieldset');

            echo $this->Form->input('ecm_alternative_host_id', ['options'=>$ecmAlternativeHost,
                'label'=>'Origem - Empresa']);
            echo $this->Form->input('equipe', ['options'=>$equipe]);

            echo $this->Form->input('ecm_repasse_categorias_id', ['options'=>$ecmRepasseCategorias,
                'label'=>'Categoria do sistema']);
            echo $this->Form->input('ecm_repasse_origem_id', ['options'=>$ecmRepasseOrigem,
                'label'=>'Origem do sistema', 'default' => 4]);

            echo $this->Form->input('observacao', ['label'=>'Observação', 'type'=>'textarea']);

            echo $this->Form->input('enviar_email', ['label'=>'Deseja enviar email?', 'type'=>'checkbox']);

            echo $this->Form->input('assunto_email', ['label'=>'Título do email']);
            echo $this->Form->input('corpo_email', ['label'=>'Corpo do email']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->scriptBlock($this->UserScript->selectOptionsUserAjax('#buscar-usuario', '#mdl-user-id')); ?>
<?= $this->Html->scriptBlock($this->UserScript->selectOptionsUserAjax('#buscar-entidade', '#mdl-user-cliente-id')); ?>
<script>
    $(function() {
        $("#assunto-email").parent().hide();
        $("#corpo-email").parent().hide();
        $("#enviar-email").on('change', function() {
            if (this.checked) {
                $("#assunto-email").parent().show();
                $("#corpo-email").parent().show();
            } else {
                $("#assunto-email").parent().hide();
                $("#corpo-email").parent().hide();
            }
        });
    });
</script>