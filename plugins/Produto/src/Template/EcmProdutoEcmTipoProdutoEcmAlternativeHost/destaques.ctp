<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/ordenar-produto-sortable.css') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>

<div class="ecmProdutoEcmTipoProdutoEcmAlternativeHost col-md-12">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Gerenciamento De Destaques') ?></legend>
        <?php
            echo $this->Form->input('ecm_alternative_host_id', ['options' => $ecmAlternativeHost,
                'label' => 'Selecione a entidade']);
            echo $this->Form->input('ecm_produto._ids', ['options' => $ecmProduto,
                'label' => 'Selecione os Produtos para destaque']);
            echo $this->Form->label('Ordem dos Produtos');
            echo $this->Form->hidden('ecm_produto_ecm_tipo_produto_id', ['id' => 'ecm_produto_ecm_tipo_produto_id']);
        ?>
        <div id="ordem" style="display:none;">
            <ul id="sortable">
            <?php foreach ($ecmProdutoEcmTipoProduto as $ecmProdutoEcmTipoProduto): ?>
                <li class="ui-state-default" title="<?= $ecmProdutoEcmTipoProduto->nome ?>"
                    sigla="<?= $ecmProdutoEcmTipoProduto->sigla ?>"
                    produto="<?= $this->Number->format($ecmProdutoEcmTipoProduto->produto) ?>"
                    data-id="<?= $this->Number->format($ecmProdutoEcmTipoProduto->id) ?>"
                    tipo-id="<?= $this->Number->format($ecmProdutoEcmTipoProduto->tipo) ?>">
                    <div class="divSortable">
                        <?php if(isset($ecmProdutoEcmTipoProduto->src) &&
                            file_exists("../webroot/upload/" . $ecmProdutoEcmTipoProduto->src)): ?>
                            <img src="../webroot/upload/<?= $ecmProdutoEcmTipoProduto->src ?>">
                        <?php else: ?>
                            <img src="../webroot/img/default-img.png">
                        <?php endif; ?>
                        <br/><?= $ecmProdutoEcmTipoProduto->nome ?>
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
    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $('label[for="ecm-produto-ids"]').hide();
        $('#ms-ecm-produto-ids').hide();
        $('label[for="ordem-dos-produtos"]').hide();
        $('label[for="ordem-dos-produtos"]').css("margin-top", 270);
    });
    var destaques = undefined;
    $('#ecm-alternative-host-id').change(function() {
        $('label[for="ecm-produto-ids"]').hide();
        $('#ms-ecm-produto-ids').hide();
        $('label[for="ordem-dos-produtos"]').hide();
        $("#ordem").hide();
        var alternativehost = $("#ecm-alternative-host-id").val();
        if(alternativehost > 0) {
            $.getJSON('destaques?alternative_host='+alternativehost, function(data){
                valores = [];
                $.each(data['ecmProdutoEcmTipoProdutoEcmAlternativeHost'], function( index, value ) {
                    valores.push(value['produto']+"");
                });
                var lista = $('select[name="ecm_produto[_ids][]"]').children('option').remove();
                var listaOrdenada = [];
                $("select[multiple='multiple']").multiSelect('deselect_all');
                for(item in valores){
                    for(index in lista){
                        var li = lista.get(index);
                        if($(li).attr('value') == valores[item]+""){
                            listaOrdenada.push($(li).attr('value'));
                            $('select[name="ecm_produto[_ids][]"]').append(lista.splice(index,1));
                            break;
                        }
                    }
                }
                $('select[name="ecm_produto[_ids][]"]').append(lista);
                destaques = valores;
                $("select[multiple='multiple']").multiSelect('select',listaOrdenada);
                $('label[for="ecm-produto-ids"]').show();
                $('#ms-ecm-produto-ids').show();
                $('label[for="ordem-dos-produtos"]').show();
                $("#ordem").show();
            });
        }
    });
    $("select[multiple='multiple']").change(function() {
        console.log($(this));
        var lista = $('#sortable').children('li').remove();
        var listaOrdenada = [];
        lista.each(function(){
            $(this).hide();
        });
        if(destaques == undefined) {
            var multiSelect = $("select[multiple='multiple'] option:selected");
            destaques = [];
            multiSelect.each(function(){
                destaques.push($(this).attr('value'));
            });
        };
        for(item in destaques){
            for(index in lista){
                var li = lista.get(index);
                if($(li).attr('produto') == destaques[item]){
                    $(li).show();
                    listaOrdenada[item] = $(li);
                    lista.splice(index, 1);
                    break;
                }
            }
        }
        destaques = undefined;
        $('#sortable').append(listaOrdenada.concat(lista));
    });
    $('#id_salvar').click(function() {
        if($('#ecm-alternative-host-id').val() > 0) {
            var arr = [];
            var count = 0;
            $("#sortable li").each(function () {
                if($(this).is(":visible")){
                    arr[count++] = $(this).attr('data-id');
                }
            });
            $('#ecm_produto_ecm_tipo_produto_id').val(arr.toString());
        }
    });
    $("select[multiple='multiple']").multiSelect({
        keepOrder: true,
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