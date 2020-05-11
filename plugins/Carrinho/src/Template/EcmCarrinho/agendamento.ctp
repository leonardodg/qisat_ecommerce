<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<div class="ecmCarrinho col-md-12">
    <h3><?= __('Agende aqui seus cursos a Distância') ?></h3>
    <?= $this->element('etapa',['etapa'=>4]);?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>
    <?= $this->Form->create() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('ecm_produto_id', 'Selecione os Produtos') ?></th>
                <th><?= $this->Paginator->sort('data', 'Inicio') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php $desabled = false;
        $count = 1;
        foreach ($aDistancia as $key => $item):
            if($key != 0): ?>
                <tr>
                    <td><?= $this->Form->input('distancia'.$count, ['label' => false, 'disabled' => $desabled,
                            'data-id' => $count, 'options' => $aDistancia]) ?></td>
                    <?php $desabled = true; ?>
                    <td>
                        <?= $this->Form->input('date'.$count, ['label' => false, 'disabled' => $desabled,
                            'data-id' => $count]) ?>
                        <?= $this->Form->hidden('item'.$count, ['id' => 'item'.$count]) ?>
                        <?= $this->Form->hidden('enrolperiod'.$count, ['id' => 'enrolperiod'.$count]) ?>
                    </td>
                </tr>
            <?php $count++;
            endif;
        endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->button(__("Cadastrar"), ["type" => "submit", "onclick" => "enviar();"]) ?>
    <?= $this->Form->button(__("Limpar Dados"), ["type" => "reset", "onclick" => "onClickReset();"]) ?>
    <?= $this->Form->end() ?>
    <h3><?= __('Confira a data dos seus cursos presenciais') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('Curso') ?></th>
                <th><?= $this->Paginator->sort('Data') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($presencial as $carrinho_item): ?>
            <tr>
                <td><?= $carrinho_item->ecm_produto->nome ?></td>
                <td><?= $carrinho_item->ecm_curso_presencial_turma->ecm_curso_presencial_data[0]->datainicio ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    function distanciaChange(){
        var id = parseInt($(this).attr('data-id'));
        var dateInput = $("#date"+id);
        var dateInputAnterior = $("#date"+(id-1));
        var idProduto = $(this).val();
        if(idProduto == 0) {
            dateInput.attr('disabled', true);
            dateInputAnterior.removeAttr('disabled');
        } else {
            dateInputAnterior.attr('disabled', true);
            var data2 = new Date();
            if(id > 1){
                data2 = dateInputAnterior.datepicker("option", "maxDate");
            }
            $.ajax({
                type: "POST",
                url: 'agendamento',
                data: {id: idProduto},
                dataType : 'json',
                success:function(data) {
                    $("#item"+id).val(data.item);
                    $("#enrolperiod"+id).val(data.enrolperiod);
                    data2.setDate(data2.getDate() + data.enrolperiod);
                    dateInput.datepicker("option", "maxDate", data2);
                    dateInput.removeAttr('disabled');
                }
            });
        }
    }
    function datepickerChange(){
        var id = parseInt($(this).attr('data-id'));
        if($(this).val().length == 0) {
            $("#distancia"+id).removeAttr('disabled');
            $("#distancia"+(++id)).attr('disabled', true);
        } else {
            var distancia1 = $("#distancia"+id);
            var distancia2 = $("#distancia"+(id+1));
            var options = $("#distancia"+id+' option').clone();
            var value = distancia1.val();
            distancia1.attr('disabled', true);
            $("#distancia"+(id+1)+" option").remove();
            distancia2.append(options);
            $("#distancia"+(id+1)+" option[value='"+value+"']").remove();
            distancia2.removeAttr('disabled');
        }
    }
    $(function() {
        $("input[name*='date']").datepicker({dateFormat: 'dd/mm/yy', minDate: '+3'}).change(datepickerChange);
        $("select[name*='distancia']").change(distanciaChange);
    });
    function onClickReset(){
        $("input[name*='date']").attr('disabled', true);
        $("select[name*='distancia']").attr('disabled', true);
        $("#distancia1").removeAttr('disabled');
    }
    function enviar(){
        $("input[name*='date']").removeAttr('disabled');
        $("select[name*='distancia']").removeAttr('disabled');
    }
</script>