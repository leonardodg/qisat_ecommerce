<div class="ecmGrupoPermissao col-md-12">
    <h3><?= h($ecmGrupoPermissao->nome) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmGrupoPermissao->nome) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmGrupoPermissao->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Empresa') ?></th>
            <td><?= h($ecmGrupoPermissao->ecm_alternative_host->fullname) ?></td>
        </tr>
        <tr>
            <th><?= __('Atendente') ?></th>
            <td><?= $this->Number->format($ecmGrupoPermissao->atendente) ?></td>
        </tr>
        <tr>
            <th><?= __('Acesso total') ?></th>
            <td><?= $this->Number->format($ecmGrupoPermissao->acesso_total) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Descrição') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmGrupoPermissao->descricao)); ?>
    </div>
    <div class="related">
        <h4><?= __('Permissões desse Grupo') ?></h4>
        <?php if (!empty($ecmGrupoPermissao->ecm_permissao)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Action') ?></th>
                <th><?= __('Controller') ?></th>
                <th><?= __('Descricao') ?></th>
                <th><?= __('Restricao') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmGrupoPermissao->ecm_permissao as $ecmPermissao): ?>
            <tr>
                <td><?= h($ecmPermissao->id) ?></td>
                <td><?= h($ecmPermissao->action) ?></td>
                <td><?= h($ecmPermissao->controller) ?></td>
                <td><?= h($ecmPermissao->descricao) ?></td>
                <td><?= h($ecmPermissao->restricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'permissao', 'action' => 'view', $ecmPermissao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'permissao', 'action' => 'edit', $ecmPermissao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'permissao', 'action' => 'delete', $ecmPermissao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmPermissao->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Usuários desse Grupo') ?></h4>
        <?php if (!empty($ecmGrupoPermissao->mdl_user)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Username') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('E-mail') ?></th>
            </tr>
            <?php foreach ($ecmGrupoPermissao->mdl_user as $mdlUser): ?>
            <tr>
                <td><?= h($mdlUser->username) ?></td>
                <td><?= h($mdlUser->firstname.' '.$mdlUser->lastname) ?></td>
                <td><?= h($mdlUser->email) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
