<div class="ecmOperadoraPagamento col-md-12">
    <h3><?= __('Lista de Operadora de Pagamento') ?></h3>

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
                <th><?= $this->Paginator->sort('descricao', ['label' => __('Descrição')]) ?></th>
                <th><?= _('Imagem') ?></th>
                <th><?= $this->Paginator->sort('EcmFormaPagamento.nome', _('Forma de Pagamento')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmOperadoraPagamento as $ecmOperadoraPagamento): ?>
            <tr>
                <td><?= $this->Number->format($ecmOperadoraPagamento->id) ?></td>
                <td><?= h($ecmOperadoraPagamento->nome) ?></td>
                <td><?= h($ecmOperadoraPagamento->descricao) ?></td>
                <td><?= $this->Html->image('/upload/'.$ecmOperadoraPagamento->ecm_imagem->src); ?></td>
                <td><?= $ecmOperadoraPagamento->has('ecm_forma_pagamento') ? $this->Html->link($ecmOperadoraPagamento->ecm_forma_pagamento->nome, ['controller' => '', 'action' => 'view', $ecmOperadoraPagamento->ecm_forma_pagamento->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'operadora-pagamento', 'action' => 'view', $ecmOperadoraPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'operadora-pagamento', 'action' => 'edit', $ecmOperadoraPagamento->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'operadora-pagamento', 'action' => 'delete', $ecmOperadoraPagamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmOperadoraPagamento->id)]) ?>
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
