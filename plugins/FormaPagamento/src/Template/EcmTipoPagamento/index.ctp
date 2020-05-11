<div class="ecmTipoPagamento col-md-12">
    <h3><?= __('Lista de Tipo de Pagamento') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('descricao');
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= $this->Paginator->sort('descricao', __('Descrição')) ?></th>
                <th><?= $this->Paginator->sort('EcmFormaPagamento.nome', _('Forma de Pagamento')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmTipoPagamento as $ecmTipoPagamento): ?>
            <tr>
                <td><?= $this->Number->format($ecmTipoPagamento->id) ?></td>
                <td><?= h($ecmTipoPagamento->nome) ?></td>
                <td><?= h($ecmTipoPagamento->descricao) ?></td>
                <td><?= $ecmTipoPagamento->has('ecm_forma_pagamento') ? $this->Html->link($ecmTipoPagamento->ecm_forma_pagamento->nome, ['controller' => '', 'action' => 'view', $ecmTipoPagamento->ecm_forma_pagamento->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'tipo-pagamento', 'action' => 'view', $ecmTipoPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'tipo-pagamento', 'action' => 'edit', $ecmTipoPagamento->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'tipo-pagamento', 'action' => 'delete', $ecmTipoPagamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmTipoPagamento->id)]) ?>
                </td>
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
