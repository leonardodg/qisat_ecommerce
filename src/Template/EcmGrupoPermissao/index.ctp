<div class="ecmGrupoPermissao col-md-12">
    <h3><?= __('Grupo Permissão') ?></h3>

    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome', ['label' => 'Nome do Grupo de Pesquisa']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= $this->Paginator->sort('ecm_alternative_host', __('Empresa')) ?></th>
                <th><?= $this->Paginator->sort('atendente') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmGrupoPermissao as $ecmGrupoPermissao): ?>
            <tr>
                <td><?= $this->Number->format($ecmGrupoPermissao->id) ?></td>
                <td><?= h($ecmGrupoPermissao->nome) ?></td>
                <td><?= h($ecmGrupoPermissao->ecm_alternative_host->shortname) ?></td>
                <td><?= $this->Number->format($ecmGrupoPermissao->atendente) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'grupo-permissao', 'action' => 'view', $ecmGrupoPermissao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'grupo-permissao', 'action' => 'edit', $ecmGrupoPermissao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'grupo-permissao', 'action' => 'delete', $ecmGrupoPermissao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmGrupoPermissao->id)]) ?>
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
