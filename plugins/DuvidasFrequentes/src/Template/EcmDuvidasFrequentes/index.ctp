<div class="ecmDuvidasFrequentes col-md-12">
    <h3><?= __('Ecm Duvidas Frequentes') ?></h3>
    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('titulo');
            echo $this->Form->input('url', ['label' => __('Link')]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <legend><?= __('Para alterar a ordem das duvidas frequentes, arraste a linha da tabela') ?></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= __('id') ?></th>
                <th><?= __('titulo') ?></th>
                <th><?= __('ordem') ?></th>
                <th><?= __('timemodified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmDuvidasFrequentes as $ecmDuvidasFrequente): ?>
            <tr>
                <td id="id"><?= $this->Number->format($ecmDuvidasFrequente->id) ?></td>
                <td><?= $this->Html->link($ecmDuvidasFrequente->titulo,
                            $ecmDuvidasFrequente->url, ['target' => '_blank']) ?></td>
                <td id="ordem"><?= $this->Number->format($ecmDuvidasFrequente->ordem) ?></td>
                <td><?= h($ecmDuvidasFrequente->timemodified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $ecmDuvidasFrequente->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmDuvidasFrequente->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmDuvidasFrequente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmDuvidasFrequente->id)]) ?>
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
    $(function(){
        var idsAntigos = [];
        $.each($("tbody tr"), function(index, value) {
            var tr = $(value);
            idsAntigos[tr.find("td#id")[0].innerHTML] = tr.find("td#ordem")[0].innerHTML;
        });
        $("tbody").sortable({
            items: "> tr",
            stop: function(evt, ui) {
                var count = 1;
                var ids = [];
                $.each($("tbody tr"), function(index, value) {
                    var tr = $(value);
                    tr.children("td#ordem")[0].innerHTML = count++;
                    ids[tr.find("td#id")[0].innerHTML] = tr.find("td#ordem")[0].innerHTML;
                });
                if(idsAntigos.toString() != ids.toString()){
                    $.post("", {"ids": ids}, function(data) {
                         if(!data.sucesso){
                            bootbox.alert(data.mensagem);
                         }
                    }, "json");
                    idsAntigos = ids;
                }
            }
        }).disableSelection();
    });
</script>