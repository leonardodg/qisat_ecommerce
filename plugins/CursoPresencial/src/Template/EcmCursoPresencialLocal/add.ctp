<div class="ecmCursoPresencialLocal col-md-12">
    <?= $this->Form->create($ecmCursoPresencialLocal) ?>
    <fieldset>
        <legend><?= __('Add Local de Curso Presencial') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('mdl_estado_id', ['options' => $mdlEstado, 'label' => 'Estado', 'required' => 'required']);
            echo $this->Form->input('mdl_cidade_id', ['options' => '', 'label' => 'Cidade', 'required' => 'required']);
            echo $this->Form->input('endereco', ['type' => 'textarea']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $(function() {
        $('label[for="mdl-cidade-id"]').hide();
        $('#mdl-cidade-id').hide();
    });
    $('#mdl-estado-id').change(function() {
        var estado = $(this).val();
        if(estado > 0) {
            $.getJSON('add?estado='+estado, function(data){
                lista = [];
                lista.push('<option value="0">Selecione uma cidade</option>');
                $.each(data['mdlCidade'], function( index, value ) {
                    lista.push('<option value="'+index+'">'+value+'</option>');
                });
                $('#mdl-cidade-id').append(lista);
                $('label[for="mdl-cidade-id"]').show();
                $('#mdl-cidade-id').show();
            });
        } else {
            $('label[for="mdl-cidade-id"]').hide();
            $('#mdl-cidade-id').hide();
        }
    });
</script>