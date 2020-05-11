<div class="ecmNewsletter col-md-12">
    <h3><?= __('Newsletter') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="medium-1"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('email',__('E-mail')) ?></th>
                <th><?= $this->Paginator->sort('data_registro', __('Datab de Registro')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmNewsletter as $ecmNewsletter): ?>
            <tr>
                <td><?= $this->Number->format($ecmNewsletter->id) ?></td>
                <td><?= h($ecmNewsletter->email) ?></td>
                <td><?= h($ecmNewsletter->data_registro) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmNewsletter->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmNewsletter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmNewsletter->id)]) ?>
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
