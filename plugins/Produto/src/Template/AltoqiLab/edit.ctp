<?= $this->element('Produto.addProduto') ?>
<?= $this->element('Produto.editTipoProduto') ?>
<?= $this->element('Imagem.Imagem') ?>

<?= $this->Form->input('mdl_fase.mdl_fase_id', ['options' => $mdlFaseDependente,
    'label' => 'Selecione caso esta fase dependa de outra fase']) ?>

<?= $this->element('Produto.addProdutoCurso') ?>

<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/ordenar-produto-sortable.css') ?>

<div id="ordem" style="margin-top: 270px">
    <label for="sortable">Selecione a ordem dos cursos</label>
    <ul id="sortable">
        <?php if(isset($ecmProduto->mdl_course)): ?>
            <?php foreach ($ecmProduto->mdl_course as $mdlCourse): ?>

                <li class="ui-state-default" title="<?= $mdlCourse->fullname ?>"
                    sigla="<?= $mdlCourse->shortname ?>"
                    data-id="<?= $this->Number->format($mdlCourse->id) ?>">
                    <div class="divSortable">
                        <?php if(isset($mdlCourse->src)
                            && file_exists("../../../webroot/upload/" . $mdlCourse->src)): ?>
                            <img src="../../../webroot/upload/<?= $mdlCourse->src ?>">
                        <?php else: ?>
                            <img src="../../../webroot/img/default-img.png">
                        <?php endif; ?>
                        <br/><?= $mdlCourse->fullname ?>
                    </div>
                </li>

            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php
    echo $this->Form->input('mdl_fase.id', ['type' => 'hidden']);
    echo $this->Form->input('mdl_fase.descricao', ['label' => __('Descrição da Fase')]);
    echo $this->Form->input('mdl_fase.valor_carga_horaria', ['label' => __('Valor da carga horaria total da trilha'),
        'type' => 'number', 'step' => '0.01']);
    echo $this->Form->input('mdl_fase.enrolperiod', ['label' => __('Período de inscrição'), 'type' => 'number']);

    echo $this->Html->tag('div', null, ['class' => 'col-md-6']);
    echo $this->Form->input('ecm_produto_altoqi._ids', ['options' => $ecmProdutoAltoqi,
        'label' => 'Selecione os produtos AltoQi que fazem parte da fase da trilha']);
    echo $this->Html->tag('/div');

    echo $this->Html->tag('div', null, ['class' => 'col-md-6', 'style' => 'margin-bottom:15px;']);
    echo $this->Form->input('ecm_produto_prova._ids', ['options' => $ecmProdutoProva,
    'label' => 'Selecione as provas para essa fase']);
    echo $this->Html->tag('/div');

    echo $this->element('Produto.dependencia');
    echo $this->Form->hidden('mdl_course_ordem', ['id' => 'mdl_course_ordem']);
?>

<script>
    $("form").submit(function() {
        var arr = [];
        var count = 0;
        $("#sortable li").each(function () {
            if($(this).is(":visible"))
                arr[count++] = $(this).attr('data-id');
        });
        $('#mdl_course_ordem').val(arr.toString());
    });
    $(function() {
        var sortable = $("#sortable");
        var sortableLi = $("#sortable li");
        if(!sortableLi.size()){
            $("label[for='sortable']").hide();
        } else {
            var height = $("#sortable li:last").height() * Math.floor((3 + sortableLi.size()) / 4);
            sortable.height(height);
        }
        sortable.sortable();
        sortable.disableSelection();
        <?php if(isset($ecmProduto->ecmProdutoEcmProduto)): ?>
        <?php foreach ($ecmProduto->ecmProdutoEcmProduto as $produto): ?>
        $("select[multiple='multiple'][name='ecm_produto_altoqi[_ids][]']").multiSelect('select', '<?= $produto["id"] ?>');
        <?php endforeach; ?>
        <?php endif; ?>
    });
    $("select[multiple='multiple'][name='ecm_produto_altoqi[_ids][]']").multiSelect({
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
    $("#ecm-produto-prova-ids").multiSelect({
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

<?= $this->element('Produto.addProdutoSubmit') ?>
