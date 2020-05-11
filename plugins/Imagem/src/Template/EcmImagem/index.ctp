<div class="ecmImagem col-md-12">
    <h3><?= __('Ecm Imagem') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('descricao');
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= $this->Paginator->sort('src') ?></th>
                <th><?= $this->Paginator->sort('descricao') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmImagem as $ecmImagem): ?>
            <tr>
                <td><?= $this->Number->format($ecmImagem->id) ?></td>
                <td><?= h($ecmImagem->nome) ?></td>
                <td><?= h($ecmImagem->src) ?></td>
                <td><?= h($ecmImagem->descricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmImagem->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmImagem->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmImagem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmImagem->id)]) ?>
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
