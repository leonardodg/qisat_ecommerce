<div class="ecmFormaPagamento col-md-12">
    <h3><?= $ecmFormaPagamento->nome ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= h($ecmFormaPagamento->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Nome atribuido para realizar conexão com os mecanimos de Pagamento') ?></th>
            <td><?= h($ecmFormaPagamento->dataname) ?></td>
        </tr>
        <tr>
            <th><?= __('Descrição') ?></th>
            <td><?= h($ecmFormaPagamento->descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Parcelas') ?></th>
            <td><?= $this->Number->format($ecmFormaPagamento->parcelas) ?></td>
        </tr>
        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmFormaPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
        </tr>
        <tr>
            <th><?= __('Controller') ?></th>
            <td><?= $ecmFormaPagamento->controller?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Operadoras de Pagamento Realcionadas') ?></h4>
        <?php if (!empty($ecmFormaPagamento->ecm_operadora_pagamento)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Descrição') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmFormaPagamento->ecm_operadora_pagamento as $ecmOperadoraPagamento): ?>
            <tr>
                <td><?= h($ecmOperadoraPagamento->id) ?></td>
                <td><?= h($ecmOperadoraPagamento->nome) ?></td>
                <td><?= h($ecmOperadoraPagamento->descricao) ?></td>
                <td><?= $ecmFormaPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'operadora-pagamento', 'action' => 'view', $ecmOperadoraPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'operadora-pagamento', 'action' => 'edit', $ecmOperadoraPagamento->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Tipos de Pagamento Relacionados') ?></h4>
        <?php if (!empty($ecmFormaPagamento->ecm_tipo_pagamento)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Descrição') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmFormaPagamento->ecm_tipo_pagamento as $ecmTipoPagamento): ?>
            <tr>
                <td><?= h($ecmTipoPagamento->id) ?></td>
                <td><?= h($ecmTipoPagamento->nome) ?></td>
                <td><?= $ecmFormaPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
                <td><?= h($ecmTipoPagamento->descricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'tipo-pagamento', 'action' => 'view', $ecmTipoPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'tipo-pagamento', 'action' => 'edit', $ecmTipoPagamento->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
