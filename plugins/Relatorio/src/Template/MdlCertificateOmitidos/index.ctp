<?= $this->MutipleSelect->getScript();?>
<?= $this->Html->script('/webroot/js/Chart.js') ?>
<?= $this->Html->script('/webroot/js/Chart.bundle.js') ?>
<?php
    $attrPermissao = array(
        'width' => '500',
        'filter' => 'true',
        'multiple' => 'true',
        'position' => '"top"',
        'multipleWidth' => '250');

    $multiselect = $this->MutipleSelect->multipleSelect('#ecm-tipo-produto-ids',$attrPermissao);
    $scripts = $this->Jquery->domReady($multiselect);
    echo $this->Html->scriptBlock($scripts);
?>
<div class="$mdlCertificate col-md-12">
    <h3><?= __('Relatório de certificados emitidos') ?></h3>
    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('inicio', ['label' => 'Busca por Data de Inicio']);
            echo $this->Form->input('fim', ['label' => 'Busca por Data de Fim']);
            echo $this->Form->input('ecm_tipo_produto._ids', ['options' => $optionsTipoProduto, 'label' => __('Selecione a Categoria')]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('Tipo do curso') ?></th>
                <th><?= __('Data') ?></th>
                <th><?= __('Quantidade') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mdlCertificates as $mdlCertificate):?>
            <tr>
                <td name="nome"><?= h($mdlCertificate['nome']) ?></td>
                <td name="data"><?= h($mdlCertificate['data']) ?></td>
                <td name="total"><?= $this->Number->format($mdlCertificate['total']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<canvas id="myChart" width="400" height="100"></canvas><br/>
<script>
    var labels = [];
    var datasets = [];
    var count = 0;
    var countObject = -1;
    $.each($("tbody tr"), function( index, value ) {
        var nome = $(value).find("td[name='nome']").text();
        var data = $(value).find("td[name='data']").text();
        var total = $(value).find("td[name='total']").text();
        if(datasets[countObject] == undefined || datasets[countObject].label != nome){
            count = 0;
            countObject++;
        }
        if(datasets[countObject] == undefined){
            datasets[countObject] = new Object();
            datasets[countObject].label = nome;
            datasets[countObject].data = [];
            datasets[countObject].fill = false;
            datasets[countObject].borderColor = 'rgba('+(Math.random() * 255)+', '+(Math.random() * 255)+', '+(Math.random() * 255) + ', 1)';
        }
        if(countObject == 0) labels[count] = data;
        datasets[countObject].data[count++] = total;
    });
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        }
    });
    $(function() {
        $("#inicio").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#fim").datepicker("option", "minDate", $(this).datepicker("getDate"));
        });
        $("#fim").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#inicio").datepicker("option", "maxDate", $(this).datepicker("getDate"));
        });
    });
</script>