<?= $this->JqueryMask->getScript();?>
<?php
$scripts = $this->JqueryMask->mask('#data-publicacao',['##/##/#### ##:##', ['placeholder' => '__/__/____ __:__']]);
$scripts .= $this->JqueryMask->mask('#data-modificacao',['##/##/#### ##:##', ['placeholder' => '__/__/____ __:__']]);
$scripts = $this->Jquery->domReady($scripts);

echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmInstrutorArtigo col-md-12">
    <?= $this->Form->create($ecmInstrutorArtigo) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('link');
            echo $this->Form->input('titulo', ['label' => __('Título')]);
            echo $this->Form->input('descricao', ['label' => __('Descrição')]);
            echo $this->Form->input('tag');
            echo $this->Form->input('data_publicacao', [
                'label' => __('Data de Publicação'), 'maxlength' => 16,
                'type' => 'text'
            ]);
            echo $this->Form->input('data_modificacao', [
                'label' => __('Data de Modificação'), 'maxlength' => 16,
                'type' => 'text'
            ]);
            echo $this->Form->input('imagem', ['label'=>__('Imagem(link)')]);
            echo '<img id="imagem-artigo" src="" style="max-height:350px;max-width:750px;display: none;">';
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<script>
    $(document).ready(function(){

        if($('#titulo').val().length == 0) {
            $('form input[type=text]').attr('disabled', 'disabled');
            $('#link').removeAttr('disabled');
        }

        $('#link').change(function(){
            var url = $('#link').val();

            $.post('<?= \Cake\Routing\Router::url(['controller' => 'artigo', 'action' => 'buscaInformacoesArtigo'])?>',
                {'url': url},
                function(retorno){
                    $('form input[type=text]').removeAttr('disabled');

                    $('#titulo').val(retorno.data.titulo);
                    $('#descricao').val(retorno.data.descricao);
                    $('#tag').val(retorno.data.tag);
                    $('#data-publicacao').val(retorno.data.data_publicacao);
                    $('#data-modificacao').val(retorno.data.data_modificacao);
                    $('#imagem').val(retorno.data.imagem);
                    $('#imagem-artigo').attr('src', retorno.data.imagem);
                    $('#imagem-artigo').fadeIn();
                },
                'json'
            );
        });

        $('#imagem').change(function(){
            $('#imagem-artigo').fadeOut();
            $('#imagem-artigo').attr('src', $('#imagem').val());
            $('#imagem-artigo').fadeIn();
        });
    });
</script>
