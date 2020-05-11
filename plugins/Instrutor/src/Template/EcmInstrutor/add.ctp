<?= $this->Html->script('/webroot/js/tiny_mce4/tinymce.min') ?>
<?= $this->MutipleSelect->getScript();?>
<?php

$attrMultiSelect = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'multipleWidth' => '105');

$scripts = $this->MutipleSelect->multipleSelect('#ecm-produto-ids',$attrMultiSelect);
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-instrutor-area-ids',$attrMultiSelect);

$scripts = $this->Jquery->domReady($scripts);
echo $this->Html->scriptBlock($scripts);


$script = 'var file   = this.files[0];
           var reader = new FileReader();
           reader.onloadend = function () {
                $("#imagem_instrutor").attr(\'src\', reader.result);
           };
           if (file) {
                reader.readAsDataURL(file);
                $("#imagem_instrutor").show();
           } else {
                $("#imagem_instrutor").attr(\'src\', "");
                $("#imagem_instrutor").hide();
           }';

$script = $this->Jquery->get('#imagem')->event('change',$script);
$script = $this->Jquery->domReady($script);

echo $this->Html->scriptBlock($script);
?>

<div class="ecmInstrutor col-md-12">
    <?= $this->Form->create($ecmInstrutor, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            //echo $this->Form->input('mdl_user_id', ['label' => __('Usuário'), 'options' => $optionsUser, 'empty'=>true]);

            echo $this->Html->tag('fieldset');

            echo $this->Html->tag('legend', __('Selecionar Usuário'));

            echo $this->Html->tag('div',__('Atenção: para selecionar os usuários faça uma busca pelo nome do usuário e
                selecione no campo "Selecione o Usuário"'));

            echo $this->Html->tag('br /', null);

            echo $this->Form->input('buscar_usuario', ['label' => __('Buscar Usuário')]);

            $options = [];

            if(!is_null($ecmInstrutor->get('mdl_user'))){
                $usuario = $ecmInstrutor->get('mdl_user');
                $options = [
                    $usuario->get('id') => $usuario->get('firstname').' '.$usuario->get('lastname')
                ];
            }

            echo $this->Form->input('mdl_user_id', ['label'=>__('Selecione o Usuário'), 'options' => $options]);

            echo $this->Html->tag('/fieldset');

            echo $this->Form->input('imagem', [
                'label' => __('Imagem'),
                'type' => 'file']);

            $atributosImagem = [
                'id'=>'imagem_instrutor',
                'style' => 'max-height:52px;'
            ];

            $atributosImagem['src'] = '/img/instrutor.png';
            if(isset($ecmInstrutor->ecm_imagem)){
                $atributosImagem['src'] = '/upload/'.$ecmInstrutor->ecm_imagem->src;
            }

            echo $this->Html->tag('img',null, $atributosImagem);

            echo $this->Form->input('ecm_produto._ids', ['label' => __('Produto'), 'options' => $ecmProduto]);

            echo $this->Form->input('formacao', ['label' => __('Formação')]);

            echo $this->Form->input('ecm_instrutor_area._ids', ['label' => __('Área de atuação'), 'options' => $ecmInstrutorArea]);

            echo $this->Form->textarea('descricao', ['label' => __('Descrição')]);
            echo $this->TinyMCE->editor(['selector' => 'textarea']);

        echo $this->Form->input('ativo', ['label' => __('Ativo'), 'type' => 'checkbox']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Html->scriptBlock($this->UserScript->selectOptionsUserAjax('#buscar-usuario', '#mdl-user-id')); ?>