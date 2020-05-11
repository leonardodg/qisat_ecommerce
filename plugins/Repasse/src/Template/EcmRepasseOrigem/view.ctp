<div class="ecmRepasseOrigem col-md-12">
    <h3><?= h($ecmRepasseOrigem->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Origem') ?></th>
            <td><?= h($ecmRepasseOrigem->origem) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmRepasseOrigem->id) ?></td>
        </tr>
    </table>
    <tr>
        <th><?= __('Visivel') ?></th>
        <td><?= $this->Number->format($ecmRepasseOrigem->visivel) ?></td>
    </tr>
    <div class="related">
        <h4><?= __('Related Ecm Repasse') ?></h4>
        <?php if (!empty($ecmRepasseOrigem->ecm_repasse)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Assunto Email') ?></th>
                <th><?= __('Chave') ?></th>
                <th><?= __('Data Registro') ?></th>
                <th><?= __('Mdl User Id') ?></th>
                <th><?= __('Mdl Usermodified Id') ?></th>
                <th><?= __('Equipe') ?></th>
                <th><?= __('Data Modificacao') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Ecm Alternative Host Id') ?></th>
                <th><?= __('Observacao') ?></th>
                <th><?= __('Ecm Repasse Categorias Id') ?></th>
                <th><?= __('Ecm Repasse Origem Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmRepasseOrigem->ecm_repasse as $ecmRepasse): ?>
            <tr>
                <td><?= h($ecmRepasse->id) ?></td>
                <td><?= h($ecmRepasse->assunto_email) ?></td>
                <td><?= h($ecmRepasse->chave) ?></td>
                <td><?= h($ecmRepasse->data_registro) ?></td>
                <td><?= h($ecmRepasse->mdl_user_id) ?></td>
                <td><?= h($ecmRepasse->mdl_usermodified_id) ?></td>
                <td><?= h($ecmRepasse->equipe) ?></td>
                <td><?= h($ecmRepasse->data_modificacao) ?></td>
                <td><?= h($ecmRepasse->status) ?></td>
                <td><?= h($ecmRepasse->ecm_alternative_host_id) ?></td>
                <td><?= h($ecmRepasse->observacao) ?></td>
                <td><?= h($ecmRepasse->ecm_repasse_categorias_id) ?></td>
                <td><?= h($ecmRepasse->ecm_repasse_origem_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmRepasse', 'action' => 'view', $ecmRepasse->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmRepasse', 'action' => 'edit', $ecmRepasse->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmRepasse', 'action' => 'delete', $ecmRepasse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmRepasse->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
