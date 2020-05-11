<?= $this->Html->script('/webroot/js/tiny_mce4/tinymce.min') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>

<div class="ecmProduto col-md-12">
    <?= $this->Form->create($ecmProduto) ?>
    <fieldset>
        <legend><?= __('Edit Ecm Produto') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('sigla');
            echo $this->Form->input('referencia', [
                'label' => [
                    'text' => __('Referência').' <b>('.__('Valor para facilitar identificação do produto').')</b>',
                    'escape' => false
                ]
            ]);
            echo $this->Form->input('moeda', ['options' => $moeda, 'label' => 'O Valor do Produto dele ser exibido em']);
            echo $this->Form->input('preco', ['label' => 'Valor do Produto']);
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('visivel', ['options' => $visivel]);
            echo $this->Form->input('parcela', ['options' => $parcela, 'label' => 'Limite de parcelas para este Produto']);
            echo $this->Form->input('idtop', ['label' => 'Código do Produto no TopMkt AltoQi']);
            echo $this->TinyMCE->editor(['selector' => 'textarea']);
        ?>
            <br/>
            <div id="selectstipos" class="selectstipos required">
                <label for="sigla">Selecione o Tipo do Produto</label>
        <?php
            $pos = array();
            foreach ($ecmTipoproduto as $key => $value){
                if($value['ecm_tipo_produto_id']==0){
                    $pos[$value['id']] = ['pos'=>0, 'referencia'=>$value['id']];
                } else {
                    $pos[$value['id']] = ['pos'=>$pos[$value['ecm_tipo_produto_id']]['pos']+1,
                        'referencia'=>$pos[$value['ecm_tipo_produto_id']]['referencia'].'_'.$value['id']];
                }
                echo $this->Form->input($value['nome'], [
                    'type' => 'checkbox',
                    'name' => 'selectTipo_'.$pos[$value['id']]['referencia'],
                    'id' => $value['id'],
                    'onclick' => 'blockCheck(this.id,"'.$value['nome'].'")',
                    'label' => [
                        'style' => 'margin-left:'.(25*$pos[$value['id']]['pos']).'px;'
                    ],
                    'data-ref' => $value['id'],
                    'checked' => $value['EcmProdutoTipoproduto']['id']?'checked':'',
                    'hiddenField' => false
                ]);
            }
        ?>
            </div>
        <br/><?php
            $enrolperiod = '';
            if(isset($ecmProduto->ecm_produto_pacote)){
                $enrolperiod = $ecmProduto->ecm_produto_pacote->enrolperiod;
            }else if(isset($ecmProduto->ecm_produto_prazo_extra)){
                $enrolperiod = $ecmProduto->ecm_produto_prazo_extra->enrolperiod;
            }
            echo $this->Form->input('enrolperiod', ['label' => 'Periodo de inscrição', 'value' => $enrolperiod]);
        ?><br/>
        <?= $this->element('Imagem.Imagem', ['imagens' => $ecmProduto['ecm_imagem']]) ?>
        <?php
            echo $this->Form->input('refcurso', ['type' => 'checkbox', 'label' => 'Produto Referência para um Curso',
                'checked' => $ecmProduto->refcurso=='true'?'checked':'']);
            echo $this->Form->input('mdl_course._ids', ['options' => $mdlCourse, 'label' => 'Selecione os Cursos']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $("select[multiple='multiple'][name='mdl_course[_ids][]']").multiSelect({
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
    $("input[type=\"checkbox\"][name=\"refcurso\"]").click(function(){
        if($(this).is(":checked")){
            var n = $(".ms-selection ul li:visible").length;
            var msg = "";
            if(n > 1){
                msg = "Mais de um curso selecionado! "+n+"\n";
            }
            alert(msg+"Selecione Apenas um Curso");
        }
    });
    function blockCheck(id,nome){
        var excecoes = ['45', '3', '4', '6', '5', '7', '8', '9',
            '34', '35', '36', '37', '38', '29', '39', '23', '24'];

        var selectstipos = $("#selectstipos").find("input");
        var elem = $("#" + id);
        var ids = elem.attr("name").split("_");
        var software = $("input[data-ref='24']");
        var teorico = $("input[data-ref='23']");
        if(nome == "Cursos Software") {
            // 24 = Cursos Teóricos
            if (elem.prop("checked")) {
                software.removeAttr("checked", false);
                software.attr("disabled", "disabled");
            } else {
                software.removeAttr("disabled");
            }
        } else if(nome == "Cursos Teóricos"){
            // 23 = Cursos Software
            if (elem.prop("checked")) {
                teorico.removeAttr("checked", false);
                teorico.attr("disabled", "disabled");
            } else {
                teorico.removeAttr("disabled");
            }
        } else if($.inArray(id, excecoes) == -1){
            // Selecionando elemento pai
            selectstipos.each(function () {
                if ($(this).attr("id") != id) {
                    var ids2 = $(this).attr("name").split("_");
                    if ($.inArray(id, ids2) == -1 && $.inArray($(this).attr("data-ref"), excecoes) == -1) {
                        if (elem.prop("checked")) {
                            $(this).attr("disabled", "disabled");
                        } else {
                            $(this).removeAttr("disabled");
                            $(this).removeAttr("checked");
                        }
                    }
                }
            });
        }
        // Bloquenaod e desbloqueando conforme seleção
        var areas = $("input[name*='selectTipo_45']");
        var cursos = $("input[name*='selectTipo_1']");
        selectstipos.each(function () {
            if ($(this).attr("id") != id) {
                if($.inArray($(this).attr("id"), ids) != -1){
                    $(this).prop("checked", $("input[data-ref='"+id+"']").prop("checked"));
                    areas.each(function () {
                        if($(this).prop("checked"))
                            $("#45").prop("checked", true);
                    });
                    cursos.each(function () {
                        if($(this).prop("checked"))
                            $("#1").prop("checked", true);
                    });
                }
            }
        });
        // Prazo extra ou Pacotes
        if ($("#16").prop("checked") || $("#17").prop("checked")) {
            $("#enrolperiod").parent().show();
            $("#enrolperiod").attr('required', 'required');
        } else {
            $("#enrolperiod").parent().hide();
            $("#enrolperiod").removeAttr('required');
        }
    }
    window.onload = function(){
        var checkbox;
        var excecoes = ['45', '3', '4', '6', '5', '7', '8', '22', '9',
            '34', '35', '36', '37', '38', '29', '39', '23', '24'];
        $("#selectstipos").find("input").each(function () {
            if($(this).prop("checked") && $.inArray($(this).attr("data-ref"), excecoes) == -1) {
                var profundidade = $(this).attr("name").split("_");
                if(checkbox == undefined || profundidade.length > checkbox.attr("name").split("_").length){
                    checkbox = $(this);
                }
            }
        });
        if(checkbox != undefined)
            blockCheck(checkbox.attr("id"),checkbox.attr("data-ref"));
        // Prazo extra ou Pacotes
        if ($("#16").prop("checked") || $("#17").prop("checked")) {
            $("#enrolperiod").parent().show();
        } else {
            $("#enrolperiod").parent().hide();
        }
        var software = $("input[data-ref='24']");
        var teorico = $("input[data-ref='23']");
        if (teorico.prop("checked")) {
            // 24 = Cursos Teóricos
            software.removeAttr("checked", false);
            software.attr("disabled", "disabled");
        } else if (software.prop("checked")) {
            // 23 = Cursos Software
            teorico.removeAttr("checked", false);
            teorico.attr("disabled", "disabled");
        }
    }
</script>