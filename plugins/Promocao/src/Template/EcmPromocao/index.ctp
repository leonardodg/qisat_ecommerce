<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>

<div class="ecmPromocao col-md-12">
    <h3><?= __('Lista de Promoções') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('datainicio', ['label' => 'Busca por Data de Inicio']);
            echo $this->Form->input('datafim', ['label' => 'Busca por Data de Fim']);
            echo $this->Form->input('descricao', ['label' => 'Busca pela Descrição']);
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('datainicio',__('Data de Inicio')) ?></th>
                <th><?= $this->Paginator->sort('datafim',__('Data de Fim')) ?></th>
                <th><?= $this->Paginator->sort('descontovalor',__('Desc. Valor')) ?></th>
                <th><?= $this->Paginator->sort('descontoporcentagem',__('Desc. Porcentagem')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmPromocao as $ecmPromocao): ?>
            <tr>
                <td><?= $this->Number->format($ecmPromocao->id) ?></td>
                <td><?= \Cake\I18n\Time::parse($ecmPromocao->datainicio)->format('d/m/Y') ?></td>
                <td><?= \Cake\I18n\Time::parse($ecmPromocao->datafim)->format('d/m/Y') ?></td>
                <td><?= number_format($ecmPromocao->descontovalor,2,',','.') ?></td>
                <td><?= number_format($ecmPromocao->descontoporcentagem,2,',','.') ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmPromocao->id]) ?>
                    <?= $this->Html->link(__('Copiar'), ['controller' => '', 'action' => 'add', $ecmPromocao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmPromocao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => '', 'action' => 'delete', $ecmPromocao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmPromocao->id)]) ?>
                </td>
            </tr>
            <?php endforeach; new \Cake\I18n\Number()?>
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
        $("#datainicio").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#datafim").datepicker("option", "minDate", $(this).datepicker("getDate"));
        });
        $("#datafim").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#datainicio").datepicker("option", "maxDate", $(this).datepicker("getDate"));
        });
    });
</script>
