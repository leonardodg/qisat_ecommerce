<?= $this->Html->script('/webroot/js/tiny_mce4/tinymce.min') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>

<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/ordenar-produto-sortable.css') ?>

<div class="mdlFase col-md-12">
    <?= $this->Form->create($mdlFase) ?>
    <fieldset>
        <legend><?= __('Fase da Trilha') ?></legend>
        <?php
            echo $this->Form->input('descricao', ['label' => __('Descrição da Fase')]);
            echo $this->Form->input('valor_carga_horaria', ['label' => __('Valor da carga horaria total da trilha'),
                'type' => 'number', 'step' => '0.01']);
            echo $this->Form->input('enrolperiod', ['label' => __('Período de inscrição')]);

            $optionsFator = [];
            for($i = 1; $i >= 0.1;$i = $i - 0.1){
                $optionsFator[(string)$i] = $i;
            }

            echo $this->Form->input('fator_correcao',
                [
                    'label' => __('Fator de correção da meta de dedicação'),
                    'options' => $optionsFator
                ]);
            echo $this->element('Produto.dependencia');
            echo $this->Form->input('ecm_produto._ids', ['options' => $ecmProduto,
                'label' => 'Selecione os produtos AltoQi que fazem parte da fase da trilha']);
            echo $this->Form->hidden('mdl_course', ['id' => 'mdl_course']);
        ?>
        <div id="ordem" style="margin-top: 270px">
            <label for="sortable">Selecione a ordem dos cursos</label>
            <ul id="sortable">
            <?php foreach ($mdlCourse as $mdlCourse): ?>
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
            </ul>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit'),['id'=>'id_salvar']) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $('#id_salvar').click(function() {
        var arr = [];
        var count = 0;
        $("#sortable li").each(function () {
            if($(this).is(":visible"))
                arr[count++] = $(this).attr('data-id');
        });
        $('#mdl_course').val(arr.toString());
    });
    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        <?php foreach ($ecmProdutoEcmProduto as $produto): ?>
            $("select[multiple='multiple'][name='ecm_produto[_ids][]']").multiSelect('select', '<?= $produto->ecm_produto_relacionamento_id ?>');
        <?php endforeach; ?>
    });
    $("select[multiple='multiple'][name='ecm_produto[_ids][]']").multiSelect({
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