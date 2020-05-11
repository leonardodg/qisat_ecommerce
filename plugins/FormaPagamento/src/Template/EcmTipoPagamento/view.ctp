<div class="ecmTipoPagamento col-md-12">
    <h3><?= $ecmTipoPagamento->nome ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= h($ecmTipoPagamento->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Descricao') ?></th>
            <td><?= h($ecmTipoPagamento->descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Nome atribuido para realizar conexão com os mecanimos de Pagamento') ?></th>
            <td><?= h($ecmTipoPagamento->dataname) ?></td>
        </tr>
        <tr>
            <th><?= __('Forma de Pagamento') ?></th>
            <td><?= $ecmTipoPagamento->has('ecm_forma_pagamento') ? $this->Html->link($ecmTipoPagamento->ecm_forma_pagamento->nome, ['controller' => '', 'action' => 'view', $ecmTipoPagamento->ecm_forma_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmTipoPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
        </tr>
    </table>
</div>
