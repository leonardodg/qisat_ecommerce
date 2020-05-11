<div class="ecmVendaPresencial col-md-12">
    <h3><?= __('Visualizar Turma Presencial') ?></h3>
    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('produto', ['options' => $cursos, 'label' => 'Selecione um Curso Presencial']);
            echo $this->Form->input('status', ['options' => $status, 'label' => 'Status do Curso']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('id', 'Curso') ?></th>
                <th><?= $this->Paginator->sort('ecm_curso_presencial_data_id', 'Data') ?></th>
                <th><?= $this->Paginator->sort('ecm_curso_presencial_local_id', 'Local') ?></th>
                <th><?= $this->Paginator->sort('vagas_total', 'V. Total') ?></th>
                <th><?= $this->Paginator->sort('vagas_preenchidas', 'V. Usadas') ?></th>
                <th><?= $this->Paginator->sort('Vender') ?></th>
                <th><?= $this->Paginator->sort('Vendas') ?></th>
                <th><?= $this->Paginator->sort('E-mail') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCursoPresencialTurma as $ecmCursoPresencialTurma): ?>
            <tr>
                <td><?= $this->Number->format($ecmCursoPresencialTurma->id) ?></td>
                <td><?= $this->Html->link($ecmCursoPresencialTurma->ecm_produto->nome.' ('.$ecmCursoPresencialTurma->ecm_produto->sigla.')',
                        ['plugin' => 'CursoPresencial', 'controller' => 'turma', 'action' => 'view', $ecmCursoPresencialTurma->id]) ?>
                    <?php if($ecmCursoPresencialTurma->status=="Cancelado"): ?>
                        <div style="color:red">(Cancelado)</div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $ecmCursoPresencialData): ?>
                        <?= h($ecmCursoPresencialData->datainicio->format("d/m/Y (D)")) ?><br/>
                    <?php endforeach; ?>
                </td>
                <td><?= h($ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->nome) ?></td>
                <td><?= $this->Number->format($ecmCursoPresencialTurma->vagas_total) ?></td>
                <td><?= $this->Number->format($ecmCursoPresencialTurma->vagas_preenchidas) ?></td>
                <td>
                    <?php if($ecmCursoPresencialTurma->status=="Ativo"): ?>
                        <?php if($ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->datainicio < new DateTime()): ?>
                            Curso já Iniciado<br/>
                        <?php endif; ?>
                        <?= $this->Html->image("../img/USD.gif", ['url' => ['controller' => 'presencial',
                            'action' => 'add', $ecmCursoPresencialTurma->id]]) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= $this->Html->image("../img/detalhar.png", ['url' => ['controller' => 'presencial',
                        'action' => 'lista_vendas', $ecmCursoPresencialTurma->id]])?></td>
                <td>
                    <?php if($ecmCursoPresencialTurma->status=="Ativo"): ?>
                        <?= $this->Html->image("../img/send_email.png", ['url' => ['controller' => 'presencial',
                            'action' => 'lista_email', $ecmCursoPresencialTurma->id]])?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?php
                $url = ['model' => 'EcmCursoPresencialTurma'];
            ?>
            <?= $this->Paginator->prev('< ' . __('previous'), ['model' => 'EcmCursoPresencialTurma', 'url' => $url]) ?>
            <?= $this->Paginator->numbers(['model' => 'EcmCursoPresencialTurma', 'url' => $url]) ?>
            <?= $this->Paginator->next(__('next') . ' >', ['model' => 'EcmCursoPresencialTurma', 'url' => $url]) ?>
        </ul>
        <p><?= $this->Paginator->counter(['model' => 'EcmCursoPresencialTurma']) ?></p>
    </div>
    <!-- Tabela de Reservas Pendentes -->
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('ecm_curso_presencial_turma_id', 'Turma') ?></th>
            <th><?= $this->Paginator->sort('nome', 'Nome do Cliente') ?></th>
            <th><?= $this->Paginator->sort('data', 'Data | Hora') ?></th>
            <th><?= $this->Paginator->sort('quantidade_reserva', 'Quantidade de Reservas') ?></th>
            <th><?= $this->Paginator->sort('status') ?></th>
            <th><?= $this->Paginator->sort('editar') ?></th>
            <th><?= $this->Paginator->sort('excluir') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmVendaPresencial as $ecmVendaPresencial): ?>
            <tr>
                <td><?= $this->Number->format($ecmVendaPresencial->ecm_curso_presencial_turma_id) ?></td>
                <td><?= h($ecmVendaPresencial->nome) ?></td>
                <td><?= $ecmVendaPresencial->data ?></td>
                <td><?= $this->Number->format($ecmVendaPresencial->quantidade_reserva) ?></td>
                <td><?= h($ecmVendaPresencial->status) ?></td>
                <td><?= $this->Html->image("../img/edit.gif", ['url' => ['controller' => 'presencial', 'action' => 'edit', $ecmVendaPresencial->id]]) ?></td>
                <td><?= $this->Form->create('', ['url' => ['controller' => 'presencial', 'action' => 'delete', $ecmVendaPresencial->id],
                        'name' => 'delete'.$ecmVendaPresencial->id]) ?><?= $this->Form->end() ?>
                    <?= $this->Html->image("../img/delete.gif", ['onclick' =>
                        'bootbox.confirm("Tem certeza de que deseja excluir?", function(result) {
                            if(result){document.delete'.$ecmVendaPresencial->id.'.submit();}else{}
                        });event.returnValue = false; return false;', 'style' => 'cursor: pointer;']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?php
                $url = ['model' => 'EcmVendaPresencial'];
            ?>
            <?= $this->Paginator->prev('< ' . __('previous'), ['model' => 'EcmVendaPresencial', 'url' => $url]) ?>
            <?= $this->Paginator->numbers(['model' => 'EcmVendaPresencial', 'url' => $url]) ?>
            <?= $this->Paginator->next(__('next') . ' >', ['model' => 'EcmVendaPresencial', 'url' => $url]) ?>
        </ul>
        <p><?= $this->Paginator->counter(['model' => 'EcmVendaPresencial']) ?></p>
    </div>
</div>
