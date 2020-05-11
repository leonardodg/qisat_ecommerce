<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>

<div class="ecmCursoPresencialTurma col-md-12">
    <?= $this->Form->create($ecmCursoPresencialTurma) ?>
    <fieldset id="turma">
        <legend><?= __('Add Turma de Curso Presencial') ?></legend>
        <?php
            echo $this->Form->input('ecm_produto_id', ['options' => $ecmProduto, 'label' => 'Produto']);
            echo $this->Form->input('carga_horaria');
            echo $this->Form->input('vagas_total');
            echo $this->Form->input('vagas_preenchidas');
            echo $this->Form->input('valor');
            echo $this->Form->input('valor_produto', ['options' => $valor_produto, 'label' => 'Utilizar valor original do produto']);
            echo $this->Form->input('status', ['options' => $status]);
            echo $this->Form->input('ecm_instrutor_id', ['options' => $ecmInstrutor, 'label' => 'Instrutor',
                'multiple' => 'multiple']);
            echo $this->Form->hidden('row');
            echo $this->Form->button('Adicionar dia', ['type' => 'button', 'id' => 'add']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['id' => 'btEnviar']) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    RemoveTableRow = function(fieldsetId) {
        $("#turno"+fieldsetId).remove();
    };
    var row = 1;
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
    $(function() {
        $("#ecm-instrutor-id").parent().height(290);
        $.getJSON('add', function(data){
            locais = '<option value="0">Selecione um Local</option>';
            $.each(data['ecmCursoPresencialLocal'], function( index, value ) {
                locais += '<option value="'+index+'">'+value+'</option>';
            });
        });
    });
    $("select[multiple='multiple']").multiSelect({
        selectableHeader: "<input type='text' class='search-input' autocomplete='off'>",
        selectionHeader: "<input type='text' class='search-input' autocomplete='off'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });
</script>
