<?= $this->MutipleSelect->getScript();?>
<?= $this->JqueryUI->getScript();?>
<?= $this->JqueryMask->getScript();?>

<?php
$atributos = array (
    'changeMonth' => true,
    'changeYear' => true,
    'numberOfMonths' => 2,
    'minDate' => 0,
    'showButtonPanel' => true
);

$atributos ['onClose'] = 'function( selectedDate ) {
							 $( "#datafim" ).datepicker( "option", "minDate", selectedDate );
						 }';

$atributosDate ['#datainicio'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
							$( "#datainicio" ).datepicker( "option", "maxDate", selectedDate );
						 }';

$atributosDate ['#datafim'] = $atributos;

$datePicker = $this->JqueryUI->datePicker ( array (
    '#datainicio',
    '#datafim'
), $atributosDate );

$attrMultiSelect = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'position' => '"top"',
    'multipleWidth' => '105');

$scripts = $this->JqueryMask->mask(['#datainicio','#datafim'],['00/00/0000']);
$scripts .= $this->JqueryMask->mask('#descontovalor',['#.##0,00', ['reverse' => true]]);
$scripts .= $this->JqueryMask->mask('#descontoporcentagem',['00,00']);
$scripts .= $this->JqueryMask->mask('#numaxparcelas',['#']);
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-alternative-host-ids',$attrMultiSelect);
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-produto-ids',$attrMultiSelect);
$scripts .= $datePicker;

$scriptDesabilitarCampo = 'if($(this).val().length > 0){
                               elemento.attr("disabled", "disabled");
                               elemento.val("");
                           }else{
                               elemento.removeAttr("disabled");
                           }';
$scripts .= $this->Jquery->get('#descontovalor')->event(
    'keyup',
    'var elemento = $("#descontoporcentagem");'.$scriptDesabilitarCampo
);

$scripts .= $this->Jquery->get('#descontoporcentagem')->event(
    'keyup',
    'var elemento = $("#descontovalor");'.$scriptDesabilitarCampo
);

$scripts = $this->Jquery->domReady($scripts);

echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmPromocao col-md-12">
    <?= $this->Form->create($ecmPromocao) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];

            echo $this->Form->input('datainicio', ['label' => __('Data de Inicio'), 'type'=>'text']);
            echo $this->Form->input('datafim', ['label' => __('Data de Fim'),'type'=>'text']);
            echo $this->Form->input('descontovalor', ['label' => __('Desconto em Valor'),'type'=>'text']);
            echo $this->Form->input('descontoporcentagem', ['label' => __('Desconto em Porcentagem'),'type'=>'text']);
            echo $this->Form->input('descricao',['label' => __('Descrição')]);
            echo $this->Form->input('habilitado',['options'=>$optionsHabilitado]);
            echo $this->Form->input('arredondamento',['options'=>$optionsHabilitado]);
            echo $this->Form->input('numaxparcelas',['label' => __('Número máximo de parcelas'),'type'=>'text']);
            echo $this->Form->input('ecm_alternative_host._ids', ['label' => __('Entidade'), 'options' => $ecmAlternativeHost]);
            echo $this->Form->input('ecm_produto._ids', ['label' => __('Produto'), 'options' => $ecmProduto]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
