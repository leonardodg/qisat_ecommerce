
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Filtro') ?></legend>

            <div class="row">
                <div class="col-md-8">
                    <?= $this->Form->input('nome', ['label' => "Buscar pelo Nome ou Sigla","onkeyup" => "filter()"]) ?>
                </div>

                  <div class="col-md-2">
                    <div class="input-group"> 
                    <span class="input-group-btn">
                        <?php if ($set): ?>
                            <?= $this->Html->link('Criar Produto', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'product']),  [ 'class' => 'button' ]) ?>
                        <?php endif; ?>  
                    </span>
                    </div><!-- /input-group -->
                   </div>
            </div>
        </fieldset>
        <?= $this->Form->hidden('products', ['value' =>"[]"]) ?>

        <?php if (isset($products) && count($products) > 0): ?>

            <h3><?= __('Lista de Produtos - ContaAzul') ?></h3>

            <table id="products" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th width="15%" > Identificação  </th>
                    <th width="35%" > Nome </th>
                    <th width="20%" > Produto </th>
                    <th width="5%" > Valor </th>
                    <th width="5%" > Custo </th>
                    <th width="5%">  Ação </th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $produto): ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= h($produto['name']) ?></td>
                        <td><?= $produto['produto'] ?></td>
                        <td><?= $this->Number->currency($produto['value'], 'BRL') ?></td>
                        <td><?=  $this->Number->currency($produto['cost'], 'BRL') ?></td>
                        <td class="actions">
                            
                            <?php if ($set): ?>
                                <?= $this->Html->link('', [ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'product', $produto['id']], ['title' => 'Editar', 'class' => 'glyphicon glyphicon-pencil'] ) ?>
                            <?php endif; ?>

                            <?php if ($del): ?>
                                <?= $this->Form->postLink('', ['plugin' => 'ContaAzul','controller' => false, 'action' => 'delProduct',  $produto['id']], ['title' => 'Deletar', 'class' => 'glyphicon glyphicon-remove','confirm' => __('Confirma exclusão do Produto? #{0}?', $produto['id'])]) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>

    $(document).ready(function() {

        $(".btnContaAzul:visible").hide();

        if($('.select-export').length ==0){
            $(".btnSelectAll:visible").hide();
        }else{
            $(".btnSelectAll:visible").data('view', 'show');
        }
        
        $(".btnContaAzul").click(function() {
            var data = $("input[name='products']").val();

            var dialog = bootbox.dialog({
                title: 'Resultado do processo',
                message: '<p><i class="fa fa-spin fa-spinner"></i> Carregando...</p>'
            });

            $.ajax({
                type: "POST",
                url: '/conta-azul/export-products',
                data: { ids: data },
                dataType : 'json',
                success:function(res) {
                    var html = '';

                    res.map(function(el){ 
                        if(el && el.mensagens){
                            el.mensagens.map(function(msg){
                                html += '<div> '+msg+' </div><br>' 
                            });
                        }
                    });
                    dialog.find('.bootbox-body').html(html);
                }
            });
        });

        $( ".select-export" ).change(function() {
                var id = $(this).data('id');
                var list = $("input[name='products']").val();
                    list = JSON.parse(list);

                if($(this)[0].checked)
                    list.push(id);
                else
                    list.splice(list.indexOf(id),1);

                if(list.length){
                    $(".btnContaAzul:hidden").show();
                    $(".btnSelectAll:hidden").show();
                }else{
                    $(".btnContaAzul:visible").hide();
                    $(".btnSelectAll:visible").hide();
                }
                
                list = JSON.stringify(list);
                $("input[name='products']").val(list);
        });

    });

    function filter() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("nome");
    filter = input.value.toUpperCase();
    table = document.getElementById("products");
    tr = table.getElementsByTagName("tr");
    var i = tr.length;

        while (i--) {
            td = tr[i].getElementsByTagName("td")[1];
            td2 = tr[i].getElementsByTagName("td")[2];

            if (td || td2) {
                if ( (td.innerHTML.toUpperCase().indexOf(filter) > -1) || (td2.innerHTML.toUpperCase().indexOf(filter) > -1) ) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            } 

        }
    }
</script>