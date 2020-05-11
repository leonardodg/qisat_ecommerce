<div class="ecmInstrutorRedeSocial col-md-12">
    <h3><?= __('Redes Sociais').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname ?></h3>

    <?= $this->Form->create('', ['type'=>'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?= $this->Form->input('nome', ['label' => __('Rede social'), 'options' => $ecmRedeSocial]) ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <?= $this->Html->link(__('Adicionar rede social para o instrutor'),
        ['controller' => 'rede-social', 'action' => 'add', $id]
    ) ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('EcmRedeSocial.nome', __('Rede Social')) ?></th>
                <th><?= __('Link') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmInstrutorRedeSocial as $ecmInstrutorRedeSocial):?>
            <tr>
                <td><?= $this->Number->format($ecmInstrutorRedeSocial->id) ?></td>
                <td><?= $this->Html->image('/upload/'.$ecmInstrutorRedeSocial->ecm_rede_social->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
                <td><?= $this->Html->link($ecmInstrutorRedeSocial->link, $ecmInstrutorRedeSocial->link, ['target' => 'blank']) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'rede-social', 'action' => 'view', $ecmInstrutorRedeSocial->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'rede-social', 'action' => 'edit', $ecmInstrutorRedeSocial->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'rede-social', 'action' => 'delete', $ecmInstrutorRedeSocial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmInstrutorRedeSocial->id)]) ?>
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
