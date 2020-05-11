<div class="ecmCursoPresencialTurma col-md-12">
    <h3><?= __('Turmas de Cursos Presenciais') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('produto', ['options' => $cursos, 'label' => 'Selecione um Curso Presencial']);
            echo $this->Form->input('usuario', ['label' => 'Busca Nome Instrutor']);
            echo $this->Form->input('status', ['options' => $status, 'label' => 'Status do Início do Curso']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('ecm_produto_id', 'Produto') ?></th>
                <th><?= $this->Paginator->sort('local') ?></th>
                <th><?= $this->Paginator->sort('data') ?></th>
                <th><?= $this->Paginator->sort('vagas_total') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCursoPresencialTurma as $ecmCursoPresencialTurma): ?>
            <tr>
                <td><?= $ecmCursoPresencialTurma->ecm_produto->nome ?></td>
                <td>
                    <?php
                        if(isset($ecmCursoPresencialTurma->ecm_curso_presencial_data[0])){
                            echo $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->nome;
                        }
                    ?>
                </td>
                <td>
                    <?php foreach ($ecmCursoPresencialTurma->ecm_curso_presencial_data as $ecm_curso_presencial_data): ?>
                        <?= $ecm_curso_presencial_data->datainicio->format('j/m/Y (D)') ?><br/>
                    <?php endforeach; ?>
                </td>
                <td><?= $this->Number->format($ecmCursoPresencialTurma->vagas_total) ?></td>
                <td>
                    <span id="status<?= $this->Number->format($ecmCursoPresencialTurma->id) ?>"><?= $ecmCursoPresencialTurma->status ?></span><br/>
                    <button type="button" name="status<?= $this->Number->format($ecmCursoPresencialTurma->id) ?>" id="<?= $this->Number->format($ecmCursoPresencialTurma->id) ?>"><?= $ecmCursoPresencialTurma->status == 'Ativo' ? 'Cancelar' : 'Ativar' ?></button>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'turma', 'action' => 'view', $ecmCursoPresencialTurma->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'turma', 'action' => 'edit', $ecmCursoPresencialTurma->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'turma', 'action' => 'delete', $ecmCursoPresencialTurma->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCursoPresencialTurma->id)]) ?>
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
<script>
    $(function () {
        $('button[name*="status"]').on('click', function () {
            var id = $(this).attr('id');
            var valor = $('#status'+id).text();
            $.ajax({
                type: 'POST',
                url: '',
                data: {id: id, valor: valor},
                dataType : 'json',
                success:function(data) {
                    $('#status'+id).text(data);
                    $('#'+id).text(data=='Ativo'?'Cancelar':'Ativar');
                }
            });
        });
    });
</script>