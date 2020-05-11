<?= $this->Html->script('/webroot/js/clipboard.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<div class="ecmCarrinho medium-12 large-12 columns content">
    <?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='montarcarrinho'"]) ?>
    <?= $this->Form->button(__("Retorna á Lista Usuários"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='../mdl-user/listar-usuario'"]) ?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>
    <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveCarrinho2">
        <thead>
            <tr>
                <th style="width:50%;"><?= __('Produto') ?></th>
                <th style="width:18%;"><?= __('Quant. Inscrições') ?></th>
                <th style="width:16%;"><?= __('Valor Unitário') ?></th>
                <th style="width:16%;"><?= __('Valor') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmCarrinho->ecm_carrinho_item as $carrinho_item): ?>
            <?php if($carrinho_item->status == "Adicionado"): ?>
            <tr>
                <td><div style="position:relative;float:left;">
                    <div> <?= $carrinho_item->ecm_produto->nome ?> </div></td>
                <td><input maxlength="3" size="2" style="text-align:center;" value="<?= $carrinho_item->quantidade ?>" readonly="readonly" id="quant<?= $carrinho_item->ecm_produto->id ?>" disabled ></td>
                <td>
                    R$ <span id="preco<?= $carrinho_item->ecm_produto->id ?>"><?= $this->Number->precision($carrinho_item->valor_produto_desconto, 2) ?></span>
                </td>
                <td>R$ <span id="total<?= $carrinho_item->ecm_produto->id ?>"></span></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
        <thead>
            <tr>
                <th colspan="3">Total</th>
                <th>R$ <span id="somaCarrinho"></span></th>
            </tr>
        </thead>
    </table>

    <?= $this->Form->input("tipoPagamento", ['options' => [
        0 => 'Escolha opção de conclusão',
        1 => 'Enviar proposta',
        2 => 'Concluir compra'
    ], 'label' => 'Finalizar Comprar']) ?>

    <div id="concluircompra" style="display: none">
        <h4><?= __('Forma de Pagamento') ?></h4>
        <fieldset>
            <?= $this->Form->input("formaPagamento", ['options' => $formaPagamento, 'label' => 'Selecione a Forma de Pagamento']) ?>

        <?= $this->Form->create('', ['hidden' => true, 'id' => 'central']) ?>

            <?= $this->Form->hidden("contrato", [ 'value' => 1 ]) ?>
            <?= $this->Form->input("valorParcelas", ['options' => $valorParcelas, 'label' => 'Selecione o valor das Parcelas']) ?>
            <div id="infoRecorrencia" style="display: none">
                <?= $this->Form->input("cartao.nome", ['label' => 'Nome impresso no cartão']) ?>
                <?= $this->Form->input("cartao.numero", ['label' => 'Número do cartão']) ?>
                <?= $this->Form->input("cartao.mesSelect", ['options' => $mes, 'label' => 'Mês de vencimento impresso no cartão']) ?>
                <?= $this->Form->input("cartao.anoSelect", ['options' => $ano, 'label' => 'Ano de vencimento impresso no cartão']) ?>
                <?= $this->Form->input("cartao.codigo", ['label' => 'Codigo de segurança do cartão']) ?>
            </div>
            <?= $this->Form->button(__("Finalizar"), ['name' => 'avancar',"type" => "submit"]) ?>
        <?= $this->Form->end() ?>

        <?= $this->Form->create('', ['hidden' => true, 'id' => 'boleto', 'url' => 'https://mpag.bb.com.br/site/mpag/']) ?>

            <?= $this->Form->hidden("idConv",     ['label' => 'Numero do convênio', 'value' => 123456]) ?>
            <?= $this->Form->hidden("refTran",    ['label' => 'Número da transação', 'value' => 123456]) ?>
            <?= $this->Form->hidden("valor",      ['label' => 'Valor', 'value' => $ecmCarrinho->calcularTotal()]) ?>
            <?= $this->Form->hidden("dtVenc",     ['label' => 'Data de vencimento', 'value' => date('dmY', strtotime('+3 days'))]) ?>
            <?= $this->Form->hidden("urlRetorno", ['label' => 'Url Retorno',
                'value' => \Cake\Routing\Router::url([
                    'plugin' => 'FormaPagamentoBoletoRegistrado',
                    'controller' => false,
                    'action' => 'retorno',
                ], true)]) ?>

            <?= $this->Form->button(__("Finalizar"), ['name' => 'avancar', "type" => "submit"]) ?>
        <?= $this->Form->end() ?>
        </fieldset>
    </div>

    <h4 id="link-titulo" style="display: none"><?= __('Enviar proposta') ?></h4>
    <fieldset style="display:none" id="link">
        <?= $this->Form->link('linkPagamento', ['id' => 'linkPagamento', 'value' => $link,
            'readonly' => true, 'style' => 'width:50%']) ?><br/><br/>
        <?= $this->Form->button(__("Copiar link"), ["type" => "button", 'class' => 'btn',
            'data-clipboard-target' => '#linkPagamento']) ?>
    </fieldset>

</div>
<script>
    jQuery('#central').on('submit', function(event){
        var formapagamento = $("select[name='formaPagamento'] option:selected").text();
        $(this).append("<input id='formapagamento' name='formaPagamento' value='"+formapagamento+"' type='hidden'/>");
        //console.log($(this));
        //event.preventDefault();
    });
    $(function() {
        new Clipboard('.btn');
        $.each($("span[id*='total']"), function() {
            var id = $(this).attr("id").substr(5);
            calcular(id);
        });
        calcularTotal();
    });
    $("select[name='tipoPagamento']").change(function() {
        var link = $('#link');
        var titulo = $('#link-titulo');
        var concluircompra = $('#concluircompra');
        link.hide();
        titulo.hide();
        concluircompra.hide();
        var id = $(this).val();
        if(id == 1){
            link.show();
            titulo.show();
        }else if(id == 2){
            concluircompra.show();
        }
    });
    $("select[name='formaPagamento']").change(function() {
        var id = $(this).val();
        var formapagamento = $("select[name='formaPagamento'] option:selected").text();
        if(formapagamento.indexOf("Selecione") > -1){
            $("#central").hide();
            $("#boleto").hide();
        } else if(formapagamento.indexOf("Registrado") > -1){
            $("#central").hide();
            $("#boleto").show();
        } else {
            $("#central").show();
            $("#boleto").hide();
        }
        if(($("select[name='formaPagamento'] option:selected").text().indexOf("Recorrência") > -1) || $("select[name='formaPagamento'] option:selected").val() == 3 ){
            $("#infoRecorrencia").show();
            $("#cartao-nome").attr('required', 'true');
            $("#cartao-numero").attr('required', 'true');
            $("#cartao-messelect").attr('required', 'true');
            $("#cartao-anoselect").attr('required', 'true');
            $("#cartao-codigo").attr('required', 'true');
        }else{
            $("#cartao-nome").removeAttr('required');
            $("#cartao-numero").removeAttr('required');
            $("#cartao-messelect").removeAttr('required');
            $("#cartao-anoselect").removeAttr('required');
            $("#cartao-codigo").removeAttr('required');
            $("#infoRecorrencia").hide();
        }
        $.post("confirmardados", {"formaPagamento": id}, function( data ) {
            $("input[name='operadora']").parent().remove().end();
            if(Object.keys(data.operadora).length != 0){
                var operadora = '<div class="input select"><label>Selecione a Operadora</label>';
                var checked = "";
                if(Object.keys(data.operadora).length == 1){
                    checked = ' checked="checked"';
                }
                for (x in data.operadora) {
                    operadora += '<input type="radio" id="radio'+x+'" value="'+x+'" name="operadora"' + checked + '>' +
                        '<label for="radio'+x+'"><img src="../webroot/upload/'+data.operadora[x]+'"></label></input>';
                };
                $("label[for='valorparcelas']").parent().before(operadora+'</div>');
            }
            $("select[name='tipoPagamento']").parent().remove().end();
            if(Object.keys(data.tipoPagamento).length == 2) {
                var tipoPagamento = Object.keys(data.tipoPagamento).pop();
                var tipo = '<div class="input hidden"><input type="hidden" id="tipopagamento" ' +
                    'name="tipoPagamento" value="'+tipoPagamento+'"></div>';
                $("label[for='valorparcelas']").parent().before(tipo);
            } else if(Object.keys(data.tipoPagamento).length != 0) {
                var tipoPagamento = '<div class="input select"><label>Selecione o tipo do pagamento</label>' +
                    '<select id="tipopagamento" name="tipoPagamento">';
                for (x in data.tipoPagamento) {
                    tipoPagamento += '<option value="'+x+'">'+data.tipoPagamento[x]+'</option>';
                };
                $("label[for='valorparcelas']").parent().before(tipoPagamento+'</select></div>');
            }
            $("select[name='valorParcelas']").find('option').remove().end();
            for (x in data.valorParcelas) {
                if(parseInt(x)){
                    $("select[name='valorParcelas']").append('<option value="'+x+'">'+x+' X '+
                        accounting.formatMoney(data.valorParcelas[x], "", 2, ".", ",")+'</option>');
                }else{
                    $("select[name='valorParcelas']").append('<option value="'+x+'">'+data.valorParcelas[x]+'</option>');
                }
            };
        }, "json");
    });
    function calcularTotal(){
        var total = 0;
        $.each($("span[id*='total']"), function() {
            total += accounting.unformat($(this).text(), ",");
        });
        $("span[id='somaCarrinho']").text(accounting.formatMoney(total, "", 2, ".", ","));
    }
    function calcular(id){
        var quant = $("input[id='quant"+id+"']").val();
        var preco = accounting.unformat($("span[id='preco"+id+"']").text(), ",");
        var total = quant * preco;
        $("span[id='total"+id+"']").text(accounting.formatMoney(total, "", 2, ".", ","));
    }
</script>