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
                                 $( "#data-fim-pesquisa" ).datepicker( "option", "minDate", selectedDate );
                             }';

$atributosDate ['#data-inicio-pesquisa'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
                                $( "#data-inicio-pesquisa" ).datepicker( "option", "maxDate", selectedDate );
                             }';

$atributosDate ['#data-fim-pesquisa'] = $atributos;

$datePicker = $this->JqueryUI->datePicker(array (
    '#data-inicio-pesquisa',
    '#data-fim-pesquisa'
), $atributosDate);

echo $this->Html->scriptBlock($this->Jquery->domReady($datePicker));

?>

<div class="ecmInstrutor col-md-12">
    <h3><?= __('Relatório total de vezes que um produto foi adicionado no carrinho') ?></h3>

    <?= $this->Form->create($carrinho, ['type'=>'get','url' => ['controller' => 'ProdutosAdicionados', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
        echo $this->Form->input('entidade',['label'=> __('Entidade'),
            'options' => $ecmEntidade,
            'empty' => __('Todos')]);
        echo $this->Form->input('produto',['label'=> __('Produto'),
            'options' => $ecmProduto,
            'empty' => __('Todos')]);
        echo $this->Form->input('data_inicio_pesquisa', ['label' => __('Data de Inicio'), 'type'=>'text']);
        echo $this->Form->input('data_fim_pesquisa', ['label' => __('Data de Fim'),'type'=>'text']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>
</div>

<div class="ecmInstrutor col-md-12">
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="large-6"><?= __('Nome') ?></th>
            <th class="large-2"><?= __('Entidade') ?></th>
            <th class="large-2"><?= __('Quantidade') ?></th>
            <th class="large-2"><?= __('Percentual') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmCarrinhoItem as $ecmCarrinhoItem): ?>
            <tr>
                <td><?= $ecmCarrinhoItem->ecm_produto->nome.' - '.$ecmCarrinhoItem->ecm_produto->sigla ?></td>
                <td><?= $ecmCarrinhoItem->entidade ?></td>
                <td><?= $ecmCarrinhoItem->total ?></td>
                <td><?= $this->Number->format(($ecmCarrinhoItem->total * 100) / $total).' %' ?></td>
            </tr>
        <?php endforeach; ?>
        <tr style="font-weight: bold;">
            <td colspan="2"><?= __('Total')?></td>
            <td><?= $this->Number->format($total)?></td>
        </tr>
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