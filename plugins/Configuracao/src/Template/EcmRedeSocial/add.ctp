<?php
$script = 'var file   = this.files[0];
           var reader = new FileReader();
           reader.onloadend = function () {
                $("#imagem_rede_social").attr(\'src\', reader.result);
           };
           if (file) {
                reader.readAsDataURL(file);
                $("#imagem_rede_social").show();
           } else {
                $("#imagem_rede_social").attr(\'src\', "");
                $("#imagem_rede_social").hide();
           }';

$script = $this->Jquery->get('#imagem')->event('change',$script);
$script = $this->Jquery->domReady($script);

echo $this->Html->scriptBlock($script);
?>
<div class="ecmRedeSocial col-md-12">
    <?= $this->Form->create($ecmRedeSocial, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('imagem', [
                'label' => __('Imagem'),
                'type' => 'file',
                'required' => $requireImagem]);

            $atributosImagem = [
                'id'=>'imagem_rede_social',
                'style' => 'max-height:52px;'
            ];

            if(isset($ecmRedeSocial->ecm_imagem)){
                $atributosImagem['src'] = '/ecommerce/upload/'.$ecmRedeSocial->ecm_imagem->src;
            }
            echo $this->Html->tag('img',null, $atributosImagem);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
