<div class="ecmTipoProduto col-md-12">
    <h3><?= __('Tipo de Produto') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
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
                <th><?= __('Tipo de produto relacionado') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmTipoProduto as $ecmTipoProduto):?>

            <tr>
                <td><?= $this->Number->format($ecmTipoProduto->id) ?></td>
                <td><?= h($ecmTipoProduto->nome) ?></td>
                <td><?= $ecmTipoProduto->EcmTipoProdutoAR['nome'] ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'tipo-produto', 'action' => 'view', $ecmTipoProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'tipo-produto', 'action' => 'edit', $ecmTipoProduto->id]) ?>
                    <?//= $this->Form->postLink(__('Delete'), ['controller' => 'tipo-produto', 'action' => 'delete', $ecmTipoProduto->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmTipoProduto->id)]) ?>
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
