<?= $this->JqueryMask->getScript();?>
<?php
$script = 'var file   = this.files[0];
           var reader = new FileReader();
           reader.onloadend = function () {
                $("#imagem").attr(\'src\', reader.result);
           };
           if (file) {
                reader.readAsDataURL(file);
                $("#imagem").show();
           } else {
                $("#imagem").attr(\'src\', "");
                $("#imagem").hide();
           }';

$script = $this->Jquery->get('#logo-instituicao')->event('change',$script);
$script .= $this->JqueryMask->maskTelefone('#telefone');
$script = $this->Jquery->domReady($script);

echo $this->Html->scriptBlock($script);

?>

<div class="ecmConvenio col-md-12">
    <?= $this->Form->create($ecmConvenio, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('ecm_convenio_tipo_instituicao_id', ['label' => __('Tipo de Instituição'), 'options' => $ecmConvenioTipoInstituicao]);

            $attrEstado = ['options' => $mdlEstado, 'empty'=> __('Selecione')];

            if(!is_null($ecmConvenio->get('mdl_cidade'))){
                $attrEstado['default'] = $ecmConvenio->get('mdl_cidade')->get('uf');
            }

            echo $this->Form->input('mdl_estado_id', $attrEstado);

            $attrCidade = ['options' => ['' => __('Selecione um Estado')], 'disabled'];

            if(isset($listaCidadesEstado)){
                $attrCidade = ['options' => $listaCidadesEstado];
            }

            echo $this->Form->input('mdl_cidade_id', $attrCidade);
            echo $this->Form->input('nome_responsavel', ['label' => __('Responsável')]);
            echo $this->Form->input('nome_coordenador');
            echo $this->Form->input('nome_instituicao', ['label' => __('Nome da Instituição')]);
            echo $this->Form->input('curso');
            echo $this->Form->input('disciplina');
            echo $this->Form->input('cargo');
            echo $this->Form->input('email', ['label' => __('E-mail')]);
            echo $this->Form->input('telefone');

            $atributosImagem = [
                'id'=>'imagem',
                'style' => 'max-height:52px;'
            ];
            if(strlen(trim($ecmConvenio->logo)) > 0){
                $atributosImagem['src'] = '/upload/convenio/'.$ecmConvenio->logo;
            }
            echo $this->Html->tag('img',null, $atributosImagem);

            echo $this->Form->input('logo_instituicao', [
                'label' => __('Imagem'),
                'type' => 'file']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<script>
    <?= $this->Cidade->changeCidades('#mdl-estado-id', '#mdl-cidade-id')?>
</script>
