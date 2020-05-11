<div class="ecmIndicacaoSegmento col-md-12">
    <h3><?= __('Ecm Indicacao Segmento') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="10%"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('segmento') ?></th>
                <th width="25%" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmIndicacaoSegmento as $ecmIndicacaoSegmento): ?>
            <tr>
                <td><?= $this->Number->format($ecmIndicacaoSegmento->id) ?></td>
                <td><?= h($ecmIndicacaoSegmento->segmento) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmIndicacaoSegmento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmIndicacaoSegmento->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmIndicacaoSegmento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmIndicacaoSegmento->id)]) ?>
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
