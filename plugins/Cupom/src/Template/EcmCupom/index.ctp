<div class="ecmCupom col-md-12">
    <h3><?= __('Lista de Cupons') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('datainicio', ['label' => 'Busca pela Data Inicial']);
            echo $this->Form->input('datafim', ['label' => 'Busca pela Data Final']);
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('produto', ['options' => $produto]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-lg-1 ordenacao"><?= $this->Paginator->sort('id') ?></th>
                <th class="col-lg-3"><?= $this->Paginator->sort('nome') ?></th>
                <th class="col-lg-3"><?= $this->Paginator->sort('chave') ?></th>
                <th class="col-lg-1"><?= $this->Paginator->sort('datainicio',__('Data de Inicio')) ?></th>
                <th class="col-lg-1"><?= $this->Paginator->sort('datafim',__('Data de Fim')) ?></th>
                <th class="col-lg-1"><?= $this->Paginator->sort('descontovalor',__('Desconto')) ?></th>
                <th class="col-lg-2 actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCupom as $ecmCupom): ?>

                <?php
                    if($ecmCupom->descontovalor > 0){
                        $desconto = __('R$').' '.number_format($ecmCupom->descontovalor, 2 ,',','.');
                    }else{
                        $desconto = number_format($ecmCupom->descontoporcentagem, 2 ,',','.').'%';
                    }
                ?>
            <tr>
                <td><?= $this->Number->format($ecmCupom->id) ?></td>
                <td><?= h($ecmCupom->nome) ?></td>
                <td><?= h($ecmCupom->chave) ?></td>
                <td><?= \Cake\I18n\Time::parse($ecmCupom->datainicio)->format('d/m/Y') ?></td>
                <td><?= \Cake\I18n\Time::parse($ecmCupom->datafim)->format('d/m/Y') ?></td>
                <td><?= $desconto?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmCupom->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmCupom->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => '', 'action' => 'delete', $ecmCupom->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCupom->id)]) ?>
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
        $("#datainicio").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#datafim").datepicker("option", "minDate", $(this).datepicker("getDate"));
        });
        $("#datafim").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#datainicio").datepicker("option", "maxDate", $(this).datepicker("getDate"));
        });
    });
</script>