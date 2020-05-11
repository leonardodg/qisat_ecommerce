
<?php

    $script = 'var file   = this.files[0];
               var reader = new FileReader();
               reader.onloadend = function () {
                    $("#imagem_bandeira").attr(\'src\', reader.result);
               };
               if (file) {
                    reader.readAsDataURL(file);
                    $("#imagem_bandeira").show();
               } else {
                    $("#imagem_bandeira").attr(\'src\', "");
                    $("#imagem_bandeira").hide();
               }';

    $script = $this->Jquery->get('#imagem')->event('change',$script);
    $script = $this->Jquery->domReady($script);

    echo $this->Html->scriptBlock($script);
?>

<div class="ecmOperadoraPagamento col-md-12">
    <?= $this->Form->create($ecmOperadoraPagamento, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];

            echo $this->Form->input('nome');
            echo $this->Form->input('dataname', [
                'label' => __('Nome atribuido para realizar conexão com os mecanimos de Pagamento')
            ]);
            echo $this->Form->input('descricao', ['label' => __('Descrição')]);
            echo $this->Form->input('habilitado', ['options' => $optionsHabilitado]);
            echo $this->Form->input('ecm_forma_pagamento_id', ['label' => __('Forma de Pagamento'), 'options' => $ecmFormaPagamento]);
            echo $this->Form->input('imagem', [
                'label' => __('Imagem'),
                'type' => 'file',
                'required' => $requireImagem]);

            $atributosImagem = [
                'id'=>'imagem_bandeira',
                'style' => 'max-height:52px;'
            ];

            if(isset($ecmOperadoraPagamento->ecm_imagem)){
                $atributosImagem['src'] = '/ecommerce/upload/'.$ecmOperadoraPagamento->ecm_imagem->src;
            }
            echo $this->Html->tag('img',null, $atributosImagem);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
