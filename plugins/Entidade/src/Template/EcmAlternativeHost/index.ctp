<div class="ecmAlternativeHost col-md-12">
    <h3><?= __('Ecm Alternative Host') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="6%"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('host') ?></th>
                <th width="12%"><?= $this->Paginator->sort('shortname') ?></th>
                <th><?= $this->Paginator->sort('fullname') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmAlternativeHost as $ecmAlternativeHost): ?>
            <tr>
                <td><?= $this->Number->format($ecmAlternativeHost->id) ?></td>
                <td><?= h($ecmAlternativeHost->host) ?></td>
                <td><?= h($ecmAlternativeHost->shortname) ?></td>
                <td><?= h($ecmAlternativeHost->fullname) ?></td>
                <td><?= h($ecmAlternativeHost->email) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmAlternativeHost->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmAlternativeHost->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmAlternativeHost->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmAlternativeHost->id)]) ?>
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
