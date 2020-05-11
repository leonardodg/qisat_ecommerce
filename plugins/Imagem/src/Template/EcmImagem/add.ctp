<div class="ecmImagem col-md-12">
    <?= $this->Form->create($ecmImagem, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Add Imagem') ?></legend>
        <?php
            echo $this->Form->input('nome', [ 'type' => 'file', 'required' => false,
                'onchange' => 'exibirImagem(this)' ]);

            $atributosImagem = [ 'style' => 'max-height:52px;' ];
            echo $this->Html->tag('img', null, $atributosImagem);

            echo $this->Form->hidden('src');
            echo $this->Form->input('descricao', ['label' => 'Descrição']);

            echo $this->Form->input('plugin', ['label' => 'Tipo de Associação', 'options' => $plugins]);
            echo $this->Form->input('associacao', ['label' => 'Associação', 'options' => $ecmOperadora]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $("#plugin").change(function() {
        var plugin = $(this).val();
        $.post("", {'plugin' : plugin}, function( data ) {
            $("#associacao").empty();
            $.each(data['retorno'], function(key, value) {
                $('#associacao').append($('<option>', {value : key}).text(value));
            });
        }, "json");
    });
    function exibirImagem(input) {
        var file = input.files[0];
        var reader = new FileReader();
        var imagem = $(input).parent().parent().find("img");
        reader.onloadend = function () {
            imagem.attr('src', reader.result);
        };
        if (file) {
            reader.readAsDataURL(file);
            imagem.show();
        } else {
            imagem.attr("src", "");
            imagem.hide();
        }
    };
</script>
