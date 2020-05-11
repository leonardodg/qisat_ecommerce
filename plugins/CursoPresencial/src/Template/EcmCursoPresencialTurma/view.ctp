<div class="ecmCursoPresencialTurma col-md-12">
    <h3><?= h($ecmCursoPresencialTurma->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Produto') ?></th>
            <td><?= $ecmCursoPresencialTurma->ecm_produto->nome ?></td>
        </tr>
        <tr>
            <th><?= __('Instrutor') ?></th>
            <td><?php foreach ($ecmCursoPresencialTurma->ecm_instrutor as $ecm_instrutor): ?>
                    <?= $ecm_instrutor->mdl_user->firstname . ' ' . $ecm_instrutor->mdl_user->lastname ?><br>
                <?php endforeach; ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialTurma->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Carga Horaria') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialTurma->carga_horaria) ?></td>
        </tr>
        <tr>
            <th><?= __('Vagas Total') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialTurma->vagas_total) ?></td>
        </tr>
        <tr>
            <th><?= __('Vagas Preenchidas') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialTurma->vagas_preenchidas) ?></td>
        </tr>
        <tr>
            <th><?= __('Valor') ?></th>
            <td><?= isset($ecmCursoPresencialTurma->valor) ?
                    $this->Number->format($ecmCursoPresencialTurma->valor) :
                    $this->Number->format($ecmCursoPresencialTurma->ecm_produto->preco) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Valor Produto') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmCursoPresencialTurma->valor_produto)); ?>
    </div>
    <div class="row">
        <h4><?= __('Status') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmCursoPresencialTurma->status)); ?>
    </div>
    <div class="related">
        <h4><?= __('Datas do Curso Presencial') ?></h4>
        <?php if (!empty($ecmCursoPresencialTurma->ecm_curso_presencial_data)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Data inicial') ?></th>
                <th><?= __('Data final') ?></th>
                <th><?= __('Local do Curso Presencial') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $ecmCursoPresencialData): ?>
            <tr>
                <td><?= h($ecmCursoPresencialData->id) ?></td>
                <td><?= h($ecmCursoPresencialData->datainicio) ?></td>
                <td><?= h($ecmCursoPresencialData->datafim) ?></td>
                <td><?= h($ecmCursoPresencialData->ecm_curso_presencial_local->nome) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmCursoPresencialData', 'action' => 'view', $ecmCursoPresencialData->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmCursoPresencialData', 'action' => 'edit', $ecmCursoPresencialData->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmCursoPresencialData', 'action' => 'delete', $ecmCursoPresencialData->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCursoPresencialData->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
