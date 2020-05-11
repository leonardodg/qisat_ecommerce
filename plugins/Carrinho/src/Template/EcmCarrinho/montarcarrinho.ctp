<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<div class="ecmCarrinho medium-12 large-12 columns content">
    <?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='listaprodutos'"]) ?>
    <?= $this->Form->button(__("Retorna á Lista Usuários"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='../mdl-user/listar-usuario'"]) ?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>

    <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveCarrinho">
        <thead>
            <tr>
                <th><?= __('Produto') ?></th>
                <th><?= __('Quant. Inscrições') ?></th>
                <th><?= __('Remover') ?></th>
                <th><?= __('Valor Unitário') ?></th>
                <th><?= __('Valor') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmCarrinho->ecm_carrinho_item as $id => $carrinho_item): ?>
            <?php if($carrinho_item->status == "Adicionado"): ?>
            <tr>
                <td>
                    <div>
                        <?php if($carrinho_item->categoria): ?>
                            <?= $carrinho_item->categoria ?>:
                        <?php endif; ?>
                        <?= $carrinho_item->ecm_produto->nome ?>
                    </div></td>
                <td >
                    <?php if((!isset($carrinho_item->prazoExtra) || $carrinho_item->prazoExtra) &&
                            (!isset($carrinho_item->categoria) || $carrinho_item->categoria != 'Produtos AltoQi')): ?>
                        <input type="button" value="-" onclick="alterarQuantidade('<?= $id ?>', false)">
                    <?php endif; ?>
                    <input maxlength="3" size="2" value="<?= $carrinho_item->quantidade ?>" readonly="readonly" id="quant<?= $id ?>">
                    <?php if((!isset($carrinho_item->prazoExtra) || $carrinho_item->prazoExtra) &&
                            (!isset($carrinho_item->categoria) || $carrinho_item->categoria != 'Produtos AltoQi')): ?>
                        <input type="button" value="+" onclick="alterarQuantidade('<?= $id ?>', true)">
                    <?php endif; ?>
                </td>
                <td><input type="button" value="x" onclick="remover(this, '<?= $id ?>')"></td>
                <td>

                    <?php $pacote = false; ?>
                    <?php if(!empty($carrinho_item->modulos)): ?>
                        <?php foreach ($carrinho_item->modulos as $modulo): ?>
                            <?php if(!isset($modulo->vl_sugerido) || $modulo->vl_sugerido > 0): ?>
                                <?php $pacote = true; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <span id="preco<?= $id ?>" style="display: none" ><?= $this->Number->precision($carrinho_item->valor_produto_desconto, 2) ?></span>

                    <?php if($pacote): ?>
                        <button type="button" name="desconto" class="btn btn-success" data-id="<?= $id ?>">Desconto</button>
                    <?php else: ?>
                        R$ <input id="valor-<?= $id ?>" type="text" value="<?= $this->Number->precision($carrinho_item->valor_produto_desconto, 2) ?>" size="6" style="width:80px" data-edit="<?= $id ?>" data-permissao="<?= $carrinho_item->editValor ?>" data-valor="<?= $carrinho_item->valor_produto ?>" data-limit="<?= ($carrinho_item->isAltoqi) ? $carrinho_item->limit_desconto_altoqi : $carrinho_item->limit_desconto_qisat ?>">
                    <?php endif; ?>

                </td>
                <td>R$ <span id="total<?= $id ?>"></span></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
        <thead>
            <tr>
                <th colspan="4">Total</th>
                <th>R$ <span id="somaCarrinho"></span></th>
            </tr>
        </thead>
    </table>
    <?= $this->Form->button(__("Acrescentar outro curso"), ["type" => "button", "onclick" => "location.href='listaprodutos'"]) ?>
    <?= $this->Html->link('Acrescentar Produto AltoQi', '/carrinho/produtos_altoqi/', ['class' => 'button']) ?>
    <?= $this->Form->button(__("Avançar >>>"), ["type" => "button", "onclick" => "location.href='confirmardados'", 'class' => 'right' ]) ?>
</div>

<div id="descontoModal" class="modal fade" role="dialog">  
    <div class="modal-dialog">  
        <div class="modal-content">  
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal">&times;</button>  
                <h4 class="modal-title">Desconto</h4>  
            </div>  
            <div class="modal-body">  

                <span id="item" style="display: none"></span>
                <table style="box-shadow: none !important;"> 
                    <tr style="display: none">
                        <td style="width:70%">
                            <span id="id" style="display: none"></span>
                            <span id="descricao">descricao</span>
                        </td>
                        <td>
                            R$ <input id="valor" type="text" value="250" size="6" style="width: 100px; display: inline-block;">
                        </td>
                    </tr>
                </table>
                <button type="button" name="desconto" class="btn btn-warning">Salvar</button>  

            </div>  
        </div>  
    </div>  
</div>  
<script>  
    $(document).ready(function(){  
        $.fn.modal.Constructor.prototype.setScrollbar = function () { };
    });  
    $('button[name="desconto"]').click(function(){  
        var produto = $(this).data("id");  
        if(produto != undefined) {  
            $.ajax({  
                url:"edit",  
                method:"GET",  
                data: {produto:produto},  
                success:function(data)  {  
                    var modal = $('.modal-body');
                    modal.find('#item').text(produto);
                    modal.find("table tr:gt(0)").remove();
                    for (let i in data) {
                        var tr = modal.find("table tr:first").clone();
                        if(data[i].mdl_course_id != undefined){
                            tr.find("#id").data("tipo", "mdl_course_id");
                            tr.find("#id").text(data[i].mdl_course_id);
                        }else{
                            tr.find("#id").data("tipo", "ecm_produto_ecm_aplicacao_id");
                            tr.find("#id").text(data[i].ecm_produto_ecm_aplicacao_id);
                        }
                        tr.find("#descricao").text(data[i].descricao);
                        tr.find("#valor").val(accounting.formatNumber(data[i].valor, 2, ".", ","));
                        tr.show();
                        modal.find("table").append(tr);
                    } 
                    $('#descontoModal').modal('show');
                } 
            }); 
        } else { 
            var modal   = $('.modal-body');  
            produto     = modal.find('#item').text();  
            var modulos = []; 
            var data    = modal.find("table tr:visible");
            var valor = 0;
            for (let i in data) {
                if(!isNaN(i)){
                    var tipo = $(data[i]).find("#id").data("tipo");
                    var valorModulo = $(data[i]).find("#valor").val().replace(".", "").replace(",", ".");
                    var modulo = {
                        [tipo]:  $(data[i]).find("#id").text(),
                        "valor": valorModulo
                    }; 
                    valor += parseFloat(valorModulo);
                    modulos[i] = modulo;
                }
            }
            
            if(produto != '' && modulos != []) {  
                $.ajax({  
                    url:"edit",  
                    method:"POST",  
                    data: {
                        produto:produto, 
                        modulos:modulos
                    },  
                    success:function(data) {  
                        if(data.sucesso){
                            $('#preco'+produto).text(accounting.formatNumber(valor, 2, ".", ","));
                            calcular(produto);
                            calcularTotal();
                            $('#descontoModal').modal('hide');
                        } else 
                            alert("Não foi possivel efetuar a operação");  
                    }
                });  
            } else {  
                alert("Não foi possivel efetuar a operação");  
            }
        }  
    }); 
</script>  

<script>
    function calcularTotal(){
        var total = 0;
        $.each($("span[id*='total']"), function() {
            total += accounting.unformat($(this).text(), ",");
        });
        $("span[id='somaCarrinho']").text(accounting.formatNumber(total, 2, ".", ","));
    }
    function calcular(id){
        var quant = $("input[id='quant"+id+"']").val();
        var preco = parseFloat(accounting.unformat($("span[id='preco"+id+"']").text(), ","));
        var total = quant * preco;
        $("span[id='total"+id+"']").text(accounting.formatNumber(total, 2, ".", ","));
    }
    $(function() {
        $.each($("span[id*='total']"), function() {
            var id = $(this).attr("id").substr(5);
            calcular(id);
        });
        calcularTotal();
    });
    function alterarQuantidade(id, somar){  
        var quant = parseInt($("#quant"+id).val());
        quant += somar ? 1 : -1;
        var params = {"produto": id};
        var ids = id.split("-");
        if(ids.length == 2){
            params["produto"] = ids[0];
            params["presencial"] = ids[1];
        }
        if(somar){
            var botao = $("#quant"+id).parent().find("input[value$='+']");
            botao.attr('disabled','disabled');

            params["quantidade"] = quant;
            $.post("add", params, function( data ) {
                botao.removeAttr('disabled');
                if(data.sucesso){
                    $("#quant"+id).val(quant);
                    calcular(id);
                    calcularTotal();
                } else {
                    bootbox.alert(data.mensagem);
                }
            }, "json");
        } else if(quant){
            $("#quant"+id).val(quant);
            params["remover_tudo"] = 0;
            $.post("remove", params, function( data ) {
                if(data.sucesso){
                    calcular(id);
                    calcularTotal();
                } else {
                    bootbox.alert(data.mensagem);
                }
            }, "json");
        }
    }
    function remover(handler, id){
        var params = {"produto": id, "remover_tudo": 1};
        var ids = id.split("-");
        if(ids.length == 2){
            params["produto"] = ids[0];
            params["presencial"] = ids[1];
        }
        $.post("remove", params, function( data ) {
            if(data.sucesso){
                var tr = $(handler).closest('tr');
                tr.fadeOut(200, function(){
                    tr.remove();
                    calcularTotal();
                });
                if(data.refresh){
                    window.location.href = '/carrinho/montarcarrinho'; 
                }else if(data.dependencias){
                    $.each(data.dependencias , function(index, val) { 
                        var tr = $('#quant'+val).parent().parent().closest('tr');
                        tr.fadeOut(200, function(){
                            tr.remove();
                            calcularTotal();
                        });
                    });
                }
            } else {
                bootbox.alert(data.mensagem);
            }
        }, "json");
    }

    function edit(id){
        var valor, ids, params = { "produto": id };
        
        if( typeof id == 'number')
            id = id.toString();
        ids = id.split("-A");
        valor = accounting.unformat($("#valor-"+id).val(), ",");
        params['valor'] = valor;

        $("span[id='preco"+id+"']").text(accounting.formatNumber(valor, 2, ".", ","));
        if(ids.length > 1){
            params["modulos"] = [{ 'ecm_produto_ecm_aplicacao_id': ids[1], 'valor': valor }];
        }
        $.post("edit", params, function( data ) {
            if(data.sucesso){
                calcular(id);
                calcularTotal();
            }else 
                bootbox.alert('Falha: '+data.mensagem);
        }, "json");
    }

    $( "input[id^=valor]" ).change(function() {
        var id = $(this).data('edit'),
            valorTabela = parseFloat(accounting.unformat($(this).data('valor'), ",")),
            valorEdit = parseFloat( accounting.unformat($(this).val(), ",")),
            limit = parseFloat(accounting.unformat($(this).data('limit'), ",")),
            permissao = $(this).data('permissao'),
            valorDesconto = (valorTabela - (valorTabela * (limit/100)));

        if(permissao){
            if(limit){
                if(valorEdit < valorDesconto){
                    bootbox.alert('Atenção! Desconto limite de em '+limit+"%");
                    $(this).val(accounting.formatMoney(valorDesconto, "", 2, ".", ","));
                }
            }
            edit(id);
        }else{
            bootbox.alert('Atenção! Sem permissão para Alterar Preço de tabela!');
            $(this).val(accounting.formatMoney(valorTabela, "", 2, ".", ","));
        }
    });
</script>