<div class="ecmFormaPagamento col-md-12">
    <h3><?= __('Lista de Forma de Pagamento') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('descricao');
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('tipo', ['options' => $tipo]);
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
            <th><?= $this->Paginator->sort('parcelas') ?></th>
            <th><?= $this->Paginator->sort('habilitado') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmFormaPagamento as $ecmFormaPagamento): ?>
            <tr>
                <td><?= $this->Number->format($ecmFormaPagamento->id) ?></td>
                <td><?= h($ecmFormaPagamento->nome) ?></td>
                <td><?= h($ecmFormaPagamento->descricao) ?></td>
                <td><?= $this->Number->format($ecmFormaPagamento->parcelas) ?></td>
                <td><?= $ecmFormaPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmFormaPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmFormaPagamento->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => '', 'action' => 'delete', $ecmFormaPagamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmFormaPagamento->id)]) ?>
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
