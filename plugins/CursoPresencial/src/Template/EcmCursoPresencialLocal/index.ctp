<div class="ecmCursoPresencialLocal col-md-12">
    <h3><?= __('Locais para Cursos Presenciais') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('endereco');
            echo $this->Form->input('estado', ['options' => $mdlEstado, 'onchange' => 'buscarCidades()']);
            echo $this->Form->input('cidade', ['options' => ['Selecione um Estado'], 'disabled' => 'disabled']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= $this->Paginator->sort('mdl_cidade_id', 'Cidade') ?></th>
                <th><?= $this->Paginator->sort('mdl_estado_id', 'Estado') ?></th>
                <th><?= $this->Paginator->sort('endereco') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCursoPresencialLocal as $ecmCursoPresencialLocal): ?>
            <tr>
                <td><?= $this->Number->format($ecmCursoPresencialLocal->id) ?></td>
                <td><?= h($ecmCursoPresencialLocal->nome) ?></td>
                <td><?= h($ecmCursoPresencialLocal->mdl_cidade->nome) ?></td>
                <td><?= h($ecmCursoPresencialLocal->mdl_cidade->mdl_estado->nome) ?></td>
                <td><?= h($ecmCursoPresencialLocal->endereco) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'local', 'action' => 'view', $ecmCursoPresencialLocal->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'local', 'action' => 'edit', $ecmCursoPresencialLocal->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'local', 'action' => 'delete', $ecmCursoPresencialLocal->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCursoPresencialLocal->id)]) ?>
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
    $(function() {
        var estado = $('#estado');
        if(estado.val() != "0") {
            buscarCidades();
        }
    });
    function buscarCidades(){
        var estado = $('#estado');
        var cidade = $('#cidade');
        if(estado.val() == "0") {
            cidade.find('option').remove().end();
            cidade.append($("<option></option>").attr("value", 0).text('Selecione um Estado'));
            cidade.attr('disabled', 'disabled');
        } else {
            $.post("", {id: estado.val()}, function(data) {
                cidade.find('option').remove().end();
                $.each(data, function(key, value) {
                    cidade.append($("<option></option>").attr("value", key).text(value));
                });
                cidade.removeAttr('disabled');
            }, "json");
        }
    }
</script>