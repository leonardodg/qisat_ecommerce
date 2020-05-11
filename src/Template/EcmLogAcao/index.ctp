<?php

echo $this->JqueryUI->getScript();

$atributos = array (
    'changeMonth' => true,
    'changeYear' => true,
    'numberOfMonths' => 2,
    'maxDate' => 0,
    'showButtonPanel' => true
);

$atributos ['onClose'] = 'function( selectedDate ) {
                                 $( "#data-fim" ).datepicker( "option", "minDate", selectedDate );
                             }';

$atributosDate ['#data-inicio'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
                                $( "#data-inicio" ).datepicker( "option", "maxDate", selectedDate );
                             }';

$atributosDate ['#data-fim'] = $atributos;

$datePicker = $this->JqueryUI->datePicker ( array (
    '#data-inicio',
    '#data-fim'
), $atributosDate );

$scripts = $this->Jquery->domReady($datePicker);

echo $this->Html->scriptBlock($scripts);

?>

<div class="ecmLogAcao col-md-12">
    <?= $this->Form->create($ecmLogAcao, ['type' =>'get', 'id'=>'FormConsultaLogAcao', 'url' => ['controller' => 'EcmLogAcao', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php

        echo $this->Form->input('usuario', ['label' => __('Usuário'), 'type'=>'text']);
        echo $this->Form->input('tabela_pesquisa', ['label' => __('Tabela'), 'type'=>'text']);
        echo $this->Form->input('acao_pesquisa', ['label' => __('Ação'), 'empty'=>true, 'options' => $optionAcao]);
        echo $this->Form->input('data_inicio', ['label' => __('Data de Inicio'), 'type'=>'text']);
        echo $this->Form->input('data_fim', ['label' => __('Data de Fim'),'type'=>'text']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <h3><?= __('Registro de Ações') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="large-2"><?= $this->Paginator->sort('mdl_user_id', __('Usuário')) ?></th>
                <th class="large-2"><?= $this->Paginator->sort('tabela') ?></th>
                <th class="large-1"><?= $this->Paginator->sort('acao', __('Ação')) ?></th>
                <th class="large-1"><?= $this->Paginator->sort('chave') ?></th>
                <th class="large-2"><?= $this->Paginator->sort('data') ?></th>
                <th class="large-1"><?= $this->Paginator->sort('ip') ?></th>
                <th class="large-2"><?= $this->Paginator->sort('url') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmLogAcao as $ecmLogAcao): ?>
            <tr>
                <td><?= $ecmLogAcao->mdl_user->firstname.' '.$ecmLogAcao->mdl_user->lastname ?></td>
                <td><?= h($ecmLogAcao->tabela) ?></td>
                <td><?= h($ecmLogAcao->acao) ?></td>
                <td><?= h($ecmLogAcao->chave) ?></td>
                <td><?= h($ecmLogAcao->data) ?></td>
                <td><?= h($ecmLogAcao->ip) ?></td>
                <td><?= h($ecmLogAcao->url) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
