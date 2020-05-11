<div class="ecmConvenioContrato col-md-12">
    <?= $this->Form->create($ecmConvenioContrato) ?>
    <fieldset>
        <legend><?= __('Dados do Contrato de Convênio') ?></legend>
        <?php

            $dataInicioConvenio = '';
            if(!is_null($ecmConvenioContrato->data_inicio_convenio))
                $dataInicioConvenio = \Cake\I18n\Time::parse($ecmConvenioContrato->data_inicio_convenio)->format('d/m/Y');

            $dataFimConvenio = '';
            if(!is_null($ecmConvenioContrato->data_inicio_convenio))
                $dataFimConvenio = \Cake\I18n\Time::parse($ecmConvenioContrato->data_fim_convenio)->format('d/m/Y');

            $options = [''=>__('Selecione'), 'true' => __('Sim'), 'false' => __('Não')];

            echo $this->Form->input('data_inicio_convenio', [
                'type' => 'text', 'label' => __('Data de Inicio do Convênio'),
                'value' => $dataInicioConvenio
            ]);
            echo $this->Form->input('data_fim_convenio', [
                'type' => 'text', 'label' => __('Data de Fim do Convênio'),
                'value' => $dataFimConvenio
            ]);
            echo $this->Form->input('contrato_ativo', ['options' => $options]);
            echo $this->Form->input('contrato_assinado', ['options' => $options]);
        ?>
    </fieldset>
    <div class="large-12">
        <h4><?= __('Contrato') ?></h4>
        <?= $this->element('Convenio.elFinder') ?>
    </div>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php

echo $this->JqueryUI->getScript();
echo $this->JqueryMask->getScript();

$atributos = array (
    'changeMonth' => true,
    'changeYear' => true,
    'numberOfMonths' => 2,
    'showButtonPanel' => true
);

$atributos ['onClose'] = 'function( selectedDate ) {
                                 $( "#data-fim-convenio" ).datepicker( "option", "minDate", selectedDate );
                             }';

$atributosDate ['#data-inicio-convenio'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
                                $( "#data-inicio-convenio" ).datepicker( "option", "maxDate", selectedDate );
                             }';

$atributosDate ['#data-fim-convenio'] = $atributos;

$datePicker = $this->JqueryUI->datePicker(array (
    '#data-inicio-convenio',
    '#data-fim-convenio'
), $atributosDate);

$scripts = $this->JqueryMask->mask(['#data-inicio-convenio','#data-fim-convenio'],['00/00/0000']);

echo $this->Html->scriptBlock($this->Jquery->domReady($datePicker.$scripts));
?>
