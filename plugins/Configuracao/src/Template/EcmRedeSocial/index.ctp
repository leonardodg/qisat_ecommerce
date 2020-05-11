<div class="ecmRedeSocial col-md-12">
    <h3><?= __('Lista de Redes Sociais') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= __('Imagem') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmRedeSocial as $ecmRedeSocial): ?>
            <tr>
                <td><?= $this->Number->format($ecmRedeSocial->id) ?></td>
                <td><?= h($ecmRedeSocial->nome) ?></td>
                <td><?= $this->Html->image('/upload/'.$ecmRedeSocial->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'rede-social', 'action' => 'view', $ecmRedeSocial->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'rede-social', 'action' => 'edit', $ecmRedeSocial->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'rede-social', 'action' => 'delete', $ecmRedeSocial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmRedeSocial->id)]) ?>
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
