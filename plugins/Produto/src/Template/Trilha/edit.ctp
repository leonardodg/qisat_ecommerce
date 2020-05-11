<?= $this->element('Produto.addProduto') ?>
<?= $this->element('Produto.editTipoProduto') ?>
<?= $this->element('Imagem.Imagem', ['imagens' => $ecmProduto['ecm_imagem']]) ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>

<?php
echo $this->Form->input('ecm_produto._ids', ['options' => $ecmProdutoFase,
    'label' => 'Selecione os produtos AltoQi que fazem parte da fase da trilha', 'value' => $ecmProduto->get('ecm_produto_relacionamento')]);
echo $this->Form->hidden('ecm_produto_ordem', ['id' => 'ecm_produto_ordem']);
?>
<?= $this->Html->css('/webroot/css/ordenar-produto-sortable.css') ?>

<div id="ordem" style="margin-top: 270px">
    <label for="sortable">Selecione a ordem dos cursos</label>
    <ul id="sortable">
        <?php
            if(!is_null($ecmProduto->get('ecm_produto_ecm_produto'))){
                foreach($ecmProduto->get('ecm_produto_ecm_produto') as $produto) {
                    echo '<li class="ui-state-default ui-sortable-handle" title="Trilha teste habilitação"
                              data-id="'.$produto->get('ecm_produto')->get('id').'">
                            '.$produto->get('ecm_produto')->get('nome').'
                          </li>';
                }
            }
        ?>
    </ul>
</div>
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0;}
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; height: 90px; text-align: center; }
    #sortable .ui-state-highlight { width: 24%; margin: 3px 3px 3px 0; padding: 1px; float: left; height: 90px; text-align: center; }
</style>
<script>
    $(function() {
        var sortable = $("#sortable");
        sortable.sortable({
            placeholder: "ui-state-highlight"
        });

        $("form").submit(function() {
            var arr = [];
            var count = 0;
            $("#sortable li").each(function () {
                    arr[count++] = $(this).data().id;
            });
            $('#ecm_produto_ordem').val(arr.toString());
        });
    });
    $("#ecm-produto-ids").multiSelect({
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
        afterSelect: function(value){
            this.qs1.cache();
            this.qs2.cache();

            var multi = $("select[id='ecm-produto-ids']");

            var nomeProduto = multi.find('option[value="'+value+'"]').text();

            var li = $("<li />", {
                class: 'ui-state-default ui-sortable-handle',
                title: nomeProduto
            });
            li.attr("data-id", value);
            li.text(nomeProduto);

            $("#sortable").append(li);
            var sortable = $("#sortable");
            sortable.append(li);
        },
        afterDeselect: function(value){
            this.qs1.cache();
            this.qs2.cache();

            $("#sortable li[data-id='"+value+"']").remove();
        }
    });
</script>

<?= $this->element('Produto.addProdutoSubmit') ?>