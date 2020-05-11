<div class="ecmRepasseOrigem col-md-12">
    <h3><?= __('Ecm Repasse Origem') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('origem') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmRepasseOrigem as $ecmRepasseOrigem): ?>
            <tr>
                <td><?= $this->Number->format($ecmRepasseOrigem->id) ?></td>
                <td>
                    <?php if (!$ecmRepasseOrigem->visivel):?>
                        <span style="color:#5e6d7d"><strike>
                    <?php endif; ?>
                            <?= h($ecmRepasseOrigem->origem) ?>
                    <?php if (!$ecmRepasseOrigem->visivel):?>
                        </strike></span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmRepasseOrigem->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmRepasseOrigem->id]) ?>
                    <?php if ($ecmRepasseOrigem->visivel):?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmRepasseOrigem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmRepasseOrigem->id)]) ?>
                    <?php else: ?>
                        <?= $this->Form->postLink(__('Ativar'), ['action' => 'delete', $ecmRepasseOrigem->id]) ?>
                    <?php endif; ?>
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
