<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<?= $this->Html->script('/webroot/js/timepicker/ui-1.10.0/jquery.ui.core.min.js') ?>
<?= $this->Html->script('/webroot/js/timepicker/ui-1.10.0/jquery.ui.position.min.js') ?>
<?= $this->Html->script('/webroot/js/timepicker/ui-1.10.0/jquery.ui.tabs.min.js') ?>
<?= $this->Html->script('/webroot/js/timepicker/ui-1.10.0/jquery.ui.widget.min.js') ?>
<?= $this->Html->script('/webroot/js/timepicker/jquery.ui.timepicker.js') ?>
<?= $this->Html->css('/webroot/js/timepicker/jquery.ui.timepicker.css') ?>
<div class="ecmCursoPresencialTurma col-md-12">
    <?= $this->Form->create($ecmCursoPresencialTurma) ?>
    <fieldset>
        <legend><?= __('Editar Turma do Curso Presencial') ?></legend>
        <?php
            $precoProduto = number_format($ecmCursoPresencialTurma["ecm_produto"]["preco"], 2, ',', '.');

            echo $this->Form->input('ecm_produto_id', ['options' => $ecmProduto, 'label' => 'Produto']);
            echo $this->Form->input('carga_horaria');
            echo $this->Form->input('vagas_total');
            echo $this->Form->input('vagas_preenchidas');
            echo $this->Form->input('valor');
            echo $this->Form->input('valor_produto',
                [
                    'options' => $valor_produto,
                    'label' => 'Utilizar valor original do produto de '.$precoProduto
                ]
            );
            echo $this->Form->input('status', ['options' => $status]);
            echo $this->Form->input('ecm_instrutor_id', ['options' => $ecmInstrutor, 'label' => 'Instrutor',
                'multiple' => 'multiple', 'default' => $ecmInstrutorSelected]);
            echo $this->Form->hidden('row');
            echo $this->Form->button('Adicionar dia', ['type' => 'button', 'id' => 'add']);
        ?>
    </fieldset>
    <?php
        foreach ($ecmCursoPresencialData as $ecmCursoPresencialData){
            echo '<fieldset id="turno'.$ecmCursoPresencialData->id.'"><legend>Turno</legend>';
            echo $this->Form->input('local' . $ecmCursoPresencialData->id, ['options' => $ecmCursoPresencialLocal,
                'label' => 'Local', 'required' => 'required', 'value' => $ecmCursoPresencialData->ecm_curso_presencial_local_id]);
            echo $this->Form->input('datepicker'.$ecmCursoPresencialData->id.'1', ['label' => 'Data inicial',
                'required' => 'required', 'value' => $ecmCursoPresencialData->datainicio->format('d/m/Y')]);
            echo $this->Form->input('datepicker'.$ecmCursoPresencialData->id.'2', ['label' => 'Data final',
                'required' => 'required', 'value' => $ecmCursoPresencialData->datainicio->format('d/m/Y')]);
            echo $this->Form->input('timepicker'.$ecmCursoPresencialData->id.'1', ['label' => 'Hora inicial',
                'required' => 'required', 'value' => $ecmCursoPresencialData->datainicio->format('H:i')]);
            echo $this->Form->input('timepicker'.$ecmCursoPresencialData->id.'2', ['label' => 'Hora 1° intervalo',
                'required' => 'required', 'value' => $ecmCursoPresencialData->saidaintervalo->format('H:i')]);
            echo $this->Form->input('timepicker'.$ecmCursoPresencialData->id.'3', ['label' => 'Hora 2° intervalo',
                'required' => 'required', 'value' => $ecmCursoPresencialData->voltaintervalo->format('H:i')]);
            echo $this->Form->input('timepicker'.$ecmCursoPresencialData->id.'4', ['label' => 'Hora final',
                'required' => 'required', 'value' => $ecmCursoPresencialData->datafim->format('H:i')]);
            echo $this->Form->button('Remover', ['type' => 'button', 'onclick' => 'RemoveTableRow('.$ecmCursoPresencialData->id.')']);
            echo '</fieldset>';
        }
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    var row;
    $(function() {
        $.each($('fieldset'), function( index, value ) {
            if($(this).attr("id") != undefined){
                row = parseInt($(this).attr("id").substr(5));
                $("#datepicker" + row + "1").datepicker({dateFormat: 'dd/mm/yy', minDate: new Date()})
                    .change(function () {
                        var num = $(this).attr("change")+"2";
                        $("#datepicker" + num).datepicker("option", "minDate", $(this).datepicker("getDate"));
                    });
                $("#datepicker" + row + "2").datepicker({dateFormat: 'dd/mm/yy',
                    minDate: $("#datepicker" + row + "1").datepicker("getDate")})
                    .change(function () {
                        var num = $(this).attr("change")+"1";
                        $("#datepicker" + num).datepicker("option", "maxDate", $(this).datepicker("getDate"));
                    });
                $("#datepicker" + row + "1").datepicker("option", "maxDate", $("#datepicker" + row + "2").datepicker("getDate"));
                $("#timepicker" + row + "1").timepicker({showPeriodLabels: false,
                    onClose: function(time, inst) {
                        editTimepicker($(this).attr("change"), time);
                    }});
                $("#timepicker" + row + "2").timepicker({showPeriodLabels: false,
                    onClose: function(time, inst) {
                        editTimepicker($(this).attr("change"), time);
                    }});
                $("#timepicker" + row + "3").timepicker({showPeriodLabels: false,
                    onClose: function(time, inst) {
                        editTimepicker($(this).attr("change"), time);
                    }});
                $("#timepicker" + row + "4").timepicker({showPeriodLabels: false,
                    onClose: function(time, inst) {
                        editTimepicker($(this).attr("change"), time);
                    }});
                var tempo1 = $("#timepicker" + row + "1").val().split(":");
                var tempo2 = $("#timepicker" + row + "2").val().split(":");
                var tempo3 = $("#timepicker" + row + "3").val().split(":");
                var tempo4 = $("#timepicker" + row + "4").val().split(":");
                $("#timepicker" + row + "1").timepicker('option', {
                    maxTime: {hour: parseInt(tempo2[0]), minute: parseInt(tempo2[1])}
                });
                $("#timepicker" + row + "2").timepicker('option', {
                    minTime: {hour: parseInt(tempo1[0]), minute: parseInt(tempo1[1])},
                    maxTime: {hour: parseInt(tempo3[0]), minute: parseInt(tempo3[1])}
                });
                $("#timepicker" + row + "3").timepicker('option', {
                    minTime: {hour: parseInt(tempo2[0]), minute: parseInt(tempo2[1])},
                    maxTime: {hour: parseInt(tempo4[0]), minute: parseInt(tempo4[1])}
                });
                $("#timepicker" + row + "4").timepicker('option', {
                    minTime: {hour: parseInt(tempo3[0]), minute: parseInt(tempo3[1])}
                });
                $("input[name='row']").val(row++);
            }
        });
        if(row == undefined){
            row = 1;
        }

        $("#ecm-instrutor-id").parent().height(290);
        $.getJSON('', function(data){
            locais = '<option value="0">Selecione um Local</option>';

            if(data && data['ecmCursoPresencialLocal']){
                $.each(data['ecmCursoPresencialLocal'], function( index, value ) {
                locais += '<option value="'+index+'">'+value+'</option>';
            });
            }

         
        });
    });
    function editTimepicker(change, time){
        var tempo = time.split(":");
        var row = parseInt(change)+1;
        while(row%10<5) {
            $("#timepicker" + row++).timepicker('option', {
                minTime: {hour: parseInt(tempo[0]), minute: parseInt(tempo[1])}
            });
        }
        row = parseInt(change)-1;
        while(row%10>0) {
            $("#timepicker" + row--).timepicker('option', {
                maxTime: {hour: parseInt(tempo[0]), minute: parseInt(tempo[1])}
            });
        }
    }
    RemoveTableRow = function(fieldsetId) {
        $("#turno"+fieldsetId).remove();
    };
    $('#add').click(function() {
        if(locais) {
            var cols = '<fieldset id="turno' + row + '">';
            cols += '<legend>Turno</legend>';
            cols += '<div class="input select required">';
            cols += '<label for="local' + row + '">Local</label>';
            cols += '<select id="local' + row + '" name="local' + row + '">' + locais + '</select></div>';
            cols += '<div class="input text required">';
            cols += '<label for="datepicker' + row + '1">Data inicial</label>';
            cols += '<input type="text" id="datepicker' + row + '1" name="datepicker' + row + '1" change="' + row + '" required="required"></div>';
            cols += '<div class="input text required">';
            cols += '<label for="datepicker' + row + '2">Data final</label>';
            cols += '<input type="text" id="datepicker' + row + '2" name="datepicker' + row + '2" change="' + row + '" required="required"></div>';
            cols += '<div class="input text required">';
            cols += '<label for="timepicker' + row + '1">Hora inicial</label>';
            cols += '<input type="text" id="timepicker' + row + '1" name="timepicker' + row + '1" change="' + row + '1" required="required"></div>';
            cols += '<div class="input text required">';
            cols += '<label for="timepicker' + row + '2">Hora 1° intervalo</label>';
            cols += '<input type="text" id="timepicker' + row + '2" name="timepicker' + row + '2" change="' + row + '2" required="required"></div>';
            cols += '<div class="input text required">';
            cols += '<label for="timepicker' + row + '3">Hora 2° intervalo</label>';
            cols += '<input type="text" id="timepicker' + row + '3" name="timepicker' + row + '3" change="' + row + '3" required="required"></div>';
            cols += '<div class="input text required">';
            cols += '<label for="timepicker' + row + '4">Hora final</label>';
            cols += '<input type="text" id="timepicker' + row + '4" name="timepicker' + row + '4" change="' + row + '4" required="required"></div>';
            cols += '<button onclick="RemoveTableRow(' + row + ')" type="button">Remover</button>';
            cols += '</fieldset>';
            $("#btEnviar").before(cols);
            $("#datepicker" + row + "1").datepicker({dateFormat: 'dd/mm/yy', minDate: new Date()})
                .change(function () {
                    var num = $(this).attr("change")+"2";
                    $("#datepicker" + num).datepicker("option", "minDate", $(this).datepicker("getDate"));
                });
            $("#datepicker" + row + "2").datepicker({dateFormat: 'dd/mm/yy', minDate: new Date()})
                .change(function () {
                    var num = $(this).attr("change")+"1";
                    $("#datepicker" + num).datepicker("option", "maxDate", $(this).datepicker("getDate"));
                });
            $("#timepicker" + row + "1").timepicker({showPeriodLabels: false,
                onClose: function(time, inst) {
                    editTimepicker($(this).attr("change"), time);
                }});
            $("#timepicker" + row + "2").timepicker({showPeriodLabels: false,
                onClose: function(time, inst) {
                    editTimepicker($(this).attr("change"), time);
                }});
            $("#timepicker" + row + "3").timepicker({showPeriodLabels: false,
                onClose: function(time, inst) {
                    editTimepicker($(this).attr("change"), time);
                }});
            $("#timepicker" + row + "4").timepicker({showPeriodLabels: false,
                onClose: function(time, inst) {
                    editTimepicker($(this).attr("change"), time);
                }});
            $("input[name='row']").val(row++);
        }
    });
    $('#ecm-produto-id').change(function() {
        var produto = $(this).val();
        if(produto > 0) {
            $.getJSON('add?produto='+produto, function(data){
                $('#valor').val(data['ecmProduto'][0].preco);
            });
        } else {
            $('#valor').val("");
        }
    });
    $("#vagas-preenchidas").change(function() {
        if ($("#vagas-total").val() == "") {
            alert("Favor, preencha primeiro o valor: Vagas Total");
            $(this).val("");
        }
        if (parseInt($(this).val()) > parseInt($("#vagas-total").val())) {
            alert("O valor das vagas preenchidas deve ser menor que o valor de vagas totais");
            $(this).val("");
        }
    });
    $("select[multiple='multiple']").multiSelect({
        selectableHeader: "<input type='text' class='search-input' autocomplete='off'>",
        selectionHeader: "<input type='text' class='search-input' autocomplete='off'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';
            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
</script>
