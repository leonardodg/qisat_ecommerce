<div class="ecmInstrutorArtigo col-md-12">
    <h3><?= __('Lista de Artigos do Instrutor').':'.$ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname ?></h3>

    <?= $this->Form->create('', ['type'=>'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?= $this->Form->input('titulo', ['label' => __('Titulo do Artigo')]) ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <?= $this->Html->link(__('Inserir novo artigo'), ['controller' =>'artigo', 'action' => 'add', $ecmInstrutor->id]) ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('titulo', __('Título')) ?></th>
                <th><?= $this->Paginator->sort('link') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmInstrutorArtigo as $ecmInstrutorArtigo): ?>
            <tr>
                <td><?= $this->Number->format($ecmInstrutorArtigo->id) ?></td>
                <td><?= h($ecmInstrutorArtigo->titulo) ?></td>
                <td><?= $this->Html->link($ecmInstrutorArtigo->link, $ecmInstrutorArtigo->link, ['target'=>'blank']) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' =>'artigo', 'action' => 'view', $ecmInstrutorArtigo->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' =>'artigo', 'action' => 'edit', $ecmInstrutorArtigo->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' =>'artigo', 'action' => 'delete', $ecmInstrutorArtigo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmInstrutorArtigo->id)]) ?>
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
