<div class="ecmRepasseCategorias col-md-12">
    <h3><?= __('Ecm Repasse Categorias') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('categoria') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmRepasseCategorias as $ecmRepasseCategoria): ?>
            <tr>
                <td><?= $this->Number->format($ecmRepasseCategoria->id) ?></td>
                <td>
                    <?php if (!$ecmRepasseCategoria->visivel):?>
                        <span style="color:#5e6d7d"><strike>
                    <?php endif; ?>
                            <?= h($ecmRepasseCategoria->categoria) ?>
                    <?php if (!$ecmRepasseCategoria->visivel):?>
                        </strike></span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmRepasseCategoria->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmRepasseCategoria->id]) ?>
                    <?php if ($ecmRepasseCategoria->visivel):?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmRepasseCategoria->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmRepasseCategoria->id)]) ?>
                    <?php else: ?>
                        <?= $this->Form->postLink(__('Ativar'), ['action' => 'delete', $ecmRepasseCategoria->id]) ?>
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
