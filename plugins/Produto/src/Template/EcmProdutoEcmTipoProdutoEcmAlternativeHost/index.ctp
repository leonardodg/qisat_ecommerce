<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/ordenar-produto-sortable.css') ?>

<div class="ecmProdutoEcmTipoProdutoEcmAlternativeHost col-md-12">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Ordenar Produtos') ?></legend>
        <?php
            echo $this->Form->input('ecm_alternative_host_id', ['options' => $ecmAlternativeHost,
                'label' => 'Selecione a entidade']);
            echo $this->Form->label('Selecione o tipo do produto');
            echo $this->Form->select('ecm_tipo_produto._ids', $ecmTipoProduto);
            echo $this->Form->label('Ordem dos Produtos');
            echo $this->Form->hidden('ecm_produto_ecm_tipo_produto_id', ['id' => 'ecm_produto_ecm_tipo_produto_id']);
        ?>
        <div id="ordem" style="display:none;">
            <ul id="sortable">
            <?php foreach ($ecmProdutoEcmTipoProduto as $ecmProdutoEcmTipoProduto): ?>
                <li class="ui-state-default" title="<?= $ecmProdutoEcmTipoProduto->nome ?>"
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
        $('label[for="ordem-dos-produtos"]').hide();
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $('#sortable').children('li').each(function(){
            if($(this).attr("tipo-id")==32 || $(this).attr("tipo-id")==33){
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
    $('#ecm-alternative-host-id').change(function() {
        var alternativehost = $('#ecm-alternative-host-id').val();
        if(alternativehost > 0) {
            $('label[for="ordem-dos-produtos"]').show();
            $("#ordem").show();
        } else {
            $('label[for="ordem-dos-produtos"]').hide();
            $("#ordem").hide();
        }
    });
    $('select[name="ecm_tipo_produto[_ids]"]').change(function() {
        var tipo_produto = $(this).val();
        if(tipo_produto > 0){
            var alternative_host = $("#ecm-alternative-host-id").val();
            $.get('ordenar-produto?alternative_host='+alternative_host+"&tipo_produto="+tipo_produto, function(valores) {
                var lista = $('#sortable').children('li').remove();
                var listaOrdenada = [];
                var listaNaoOrdenadaShow = [];
                var listaNaoOrdenadaHide = [];
                lista.each(function(){
                    var id = valores.indexOf($(this).attr('data-id').replace(".",""));
                    if(id != -1) {
                        $(this).show();
                        listaOrdenada[id] = $(this);
                    } else if($(this).attr("tipo-id") == tipo_produto) {
                        $(this).show();
                        listaNaoOrdenadaShow.push($(this));
                    } else {
                        $(this).hide();
                        listaNaoOrdenadaHide.push($(this));
                    }
                });
                $('#sortable').append(listaOrdenada.concat(listaNaoOrdenadaShow).concat(listaNaoOrdenadaHide));
            });
        }else {
            $('#sortable').children('li').each(function () {
                if($(this).attr("tipo-id")==32 || $(this).attr("tipo-id")==33){
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }
    });
    $('#id_salvar').click(function() {
        if($('#ecm-alternative-host-id').val() > 0) {
            var arr = [];
            $("#sortable li").each(function (index) {
                if($(this).is(":visible")){
                    arr[index] = $(this).attr('data-id');
                }
            });
            $('#ecm_produto_ecm_tipo_produto_id').val(arr.toString());
        }
    });
</script>