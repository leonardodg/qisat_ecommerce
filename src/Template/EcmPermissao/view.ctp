<div class="ecmPermissao col-md-12">
    <h3><?= h($ecmPermissao->descricao) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Action') ?></th>
            <td><?= h($ecmPermissao->action) ?></td>
        </tr>
        <tr>
            <th><?= __('Controller') ?></th>
            <td><?= h($ecmPermissao->controller) ?></td>
        </tr>
        <tr>
            <th><?= __('Plugin') ?></th>
            <td><?= h($ecmPermissao->plugin) ?></td>
        </tr>
        <tr>
            <th><?= __('Label') ?></th>
            <td><?= h($ecmPermissao->label) ?></td>
        </tr>
        <tr>
            <th><?= __('Descrição') ?></th>
            <td><?= h($ecmPermissao->descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmPermissao->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Restrição') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmPermissao->restricao)); ?>
    </div>
    <div class="related">
        <h4><?= __('Grupo de Permissão vinculados a essa Permissão') ?></h4>
        <?php if (!empty($ecmPermissao->ecm_grupo_permissao)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Descrição') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmPermissao->ecm_grupo_permissao as $ecmGrupoPermissao): ?>
            <tr>
                <td><?= h($ecmGrupoPermissao->id) ?></td>
                <td><?= h($ecmGrupoPermissao->nome) ?></td>
                <td><?= h($ecmGrupoPermissao->descricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'grupo-permissao', 'action' => 'view', $ecmGrupoPermissao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'grupo-permissao', 'action' => 'edit', $ecmGrupoPermissao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'grupo-permissao', 'action' => 'delete', $ecmGrupoPermissao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmGrupoPermissao->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
