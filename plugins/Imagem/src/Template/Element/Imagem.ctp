<div id="divimagem">
    <fieldset>
        <legend><?= __('Add Imagem') ?></legend>
        <?php
            echo $this->Form->hidden('ecm_imagem[0].id');
            echo $this->Form->input('ecm_imagem[0].nome', [ 'type' => 'file', 'required' => false,
                'onchange' => 'exibirImagem(this)' ]);

            $atributosImagem = [ 'style' => 'max-height:52px;' ];
            echo $this->Html->tag('img', null, $atributosImagem);

            echo $this->Form->hidden('ecm_imagem[0].src');
            echo $this->Form->hidden('ecm_imagem[0].removido', ['value' => 0]);
            echo $this->Form->input('ecm_imagem[0].descricao', ['label' => 'Descrição']);
        ?>
        <?= $this->Form->button(__('Remover Imagem'), ['type' => 'button', 'onclick' => 'removerImagem(this)']) ?>
    </fieldset>
</div>
<?php if (!empty($imagens)):
    $row = 0;
    foreach ($imagens as $imagem): ?>
        <div id="divimagem<?= ++$row ?>">
            <fieldset>
                <legend><?= __('Edit Imagem') ?></legend>
                <?php
                    echo $this->Form->hidden('ecm_imagem['.$row.'].id', ['value' => $imagem['id']]);
                    echo $this->Form->input('ecm_imagem['.$row.'].nome', ['type' => 'file', 'required' => false,
                        'onchange' => 'exibirImagem(this)' , 'value' => $imagem['nome'] ]);

                    $atributosImagem = [ 'style' => 'max-height:52px;' ];
                    $atributosImagem['src'] = $this->request->webroot . 'upload/' . $imagem->src;
                    echo $this->Html->tag('img', null, $atributosImagem);

                    echo $this->Form->hidden('ecm_imagem['.$row.'].src', ['value' => $imagem['src']]);
                    echo $this->Form->hidden('ecm_imagem['.$row.'].removido', ['value' => 0]);
                    echo $this->Form->input('ecm_imagem['.$row.'].descricao', ['label' => 'Descrição',
                        'value' => $imagem['descricao']]);
                ?>
                <?= $this->Form->button(__('Remover Imagem'), ['type' => 'button', 'onclick' => 'removerImagem(this)']) ?>
            </fieldset>
        </div>
    <?php endforeach;
endif;?>
<?= $this->Form->button(__('Adicionar Imagem'), ['type' => 'button', 'onclick' => 'adicionarImagem(this)']) ?>
<script>
    $('form').attr('enctype', 'multipart/form-data');
    var row = 1;
    $(function() {
        row = $('div[id*="divimagem"]').length;
    });
    $('#divimagem').hide();
    function adicionarImagem(botao){
        var divimagem = $('#divimagem');
        if(divimagem.is(':visible')){
            var divimagem2 = divimagem.clone();
            divimagem2.attr('id', 'divimagem'+row);
            divimagem2.find('img').removeAttr('src');
            divimagem2.html(divimagem2.html().replace(/0/g, row++));
            divimagem2.removeAttr('id');
            $(botao).before(divimagem2);
        }else{
            divimagem.show();
        }
    }
    function removerImagem(botao){
        var divimagem = $(botao).parent().parent();
        var id = divimagem.find('input[name*="id"]').val();
        if(id != ""){
            $.post("<?= \Cake\Routing\Router::url(['plugin' => 'Imagem', 'controller' => '',
                'action' => 'delete']) ?>", {'id' : id}, function( data ) {}, "json");
        }
        if(divimagem.attr('id') == 'divimagem'){
           /*divimagem.find('input').val('');
            divimagem.find('img').removeAttr('src');*/
            divimagem.find('input[name*="removido"]').val('1');
            divimagem.hide();
        } else {
            divimagem.remove();
        }
    }
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
