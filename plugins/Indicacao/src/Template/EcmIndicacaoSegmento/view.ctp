<div class="ecmIndicacaoSegmento col-md-12">
    <h3><?= h($ecmIndicacaoSegmento->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Segmento') ?></th>
            <td><?= h($ecmIndicacaoSegmento->segmento) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmIndicacaoSegmento->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Ecm Indicacao Curso') ?></h4>
        <?php if (!empty($ecmIndicacaoSegmento->ecm_indicacao_curso)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Mdl User Id') ?></th>
                <th><?= __('Ecm Indicacao Segmento Id') ?></th>
                <th><?= __('Tema') ?></th>
                <th><?= __('Timemodified') ?></th>
                <th><?= __('Ecm Alternative Host Id') ?></th>
                <th><?= __('Nome Base Antiga') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmIndicacaoSegmento->ecm_indicacao_curso as $ecmIndicacaoCurso): ?>
            <tr>
                <td><?= h($ecmIndicacaoCurso->id) ?></td>
                <td><?= h($ecmIndicacaoCurso->mdl_user_id) ?></td>
                <td><?= h($ecmIndicacaoCurso->ecm_indicacao_segmento_id) ?></td>
                <td><?= h($ecmIndicacaoCurso->tema) ?></td>
                <td><?= h($ecmIndicacaoCurso->timemodified) ?></td>
                <td><?= h($ecmIndicacaoCurso->ecm_alternative_host_id) ?></td>
                <td><?= h($ecmIndicacaoCurso->nome_base_antiga) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EcmIndicacaoCurso', 'action' => 'view', $ecmIndicacaoCurso->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EcmIndicacaoCurso', 'action' => 'edit', $ecmIndicacaoCurso->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EcmIndicacaoCurso', 'action' => 'delete', $ecmIndicacaoCurso->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmIndicacaoCurso->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
