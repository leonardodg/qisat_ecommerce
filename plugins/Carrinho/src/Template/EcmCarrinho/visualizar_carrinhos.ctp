<?= $this->MutipleSelect->getScript();?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>

<?php  

    $attrPermissao = array(
        'width' => '460',
        'filter' => 'true',
        'multiple' => 'true',
        'multipleWidth' => '250');
    $scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#ecm-produto-ids', $attrPermissao));
    echo $this->Html->scriptBlock($scripts);

?>

<div class="ecmVenda col-md-12">
    <h3><?= __('Vendas') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <div class="row">
            <div class="col-md-5">
                <?= $this->Form->input('idnumber', ['label' => 'Chave AltoQi ou Nome do Usuário']); ?>
            </div>

            <div class="col-md-4">
                <?= $this->Form->input('alternativehost', ['label' => 'Selecione uma Entidade', 'options' => $ecmAlternativeHost]) ?>
            </div>

            <div class="col-md-1">
                <?= $this->Form->input('user', ['label' => 'Visitantes', 'options' => $user]) ?>
            </div>

            <div class="col-md-2">
                <?=  $this->Form->input('status', ['options' => $status]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $this->Form->input('inicio', ['label' => 'Busca por Data de Inicio']) ?>
            </div>

             <div class="col-md-3">
                <?= $this->Form->input('fim', ['label' => 'Busca por Data de Fim']) ?>
            </div>

             <div class="col-md-6">
                <?= $this->Form->input('ecm_produto._ids', ['options' => $produtos, 'label' => __('Selecione os Produtos')]) ?>
            </div>
        </div>

        <div class="row right">
            <div class="col-md-12">
                <?= $this->Form->button('Buscar', ['type' => 'submit', 'class' => 'right']) ?>
            </div> 
        </div>   
    </fieldset>
    <?= $this->Form->end() ?>
    <table class="vertical-table">
        <tr>
            <th><?= __('Total') ?></th>
            <td><?= $this->Number->format($total, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('data', __('Data | Hora')) ?></th>
                <th><?= $this->Paginator->sort('pedido') ?></th>
                <th><?= $this->Paginator->sort('mdl_user_id', __('Usuário')) ?></th>
                <th><?= $this->Paginator->sort('total') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('itens') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCarrinho as $ecmCarrinho): ?>
            <tr>
                <td><?= h($ecmCarrinho->data->format('d/m/Y H:i:s')) ?></td>
                <td>
                    <?= h('Pedido: '.(isset($ecmCarrinho->ecm_venda) && isset($ecmCarrinho->ecm_venda->pedido) ?
                        $ecmCarrinho->ecm_venda->pedido : '--')) ?><br/>
                    <?= h('Origem: '.$ecmCarrinho->ecm_alternative_host->shortname) ?>
                </td>
                <td>
                    <?php
                        $nome = isset($ecmCarrinho->mdl_user_id)?
                            h($ecmCarrinho->mdl_user->firstname.' '.$ecmCarrinho->mdl_user->lastname):
                            'Visitante';
                        echo $this->Html->link($nome, ['controller' => false, 'action' => 'view', $ecmCarrinho->id]);
                    ?>
                </td>
                <td>
                    <?= $this->Number->format($ecmCarrinho->calcularTotal(),
                        ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?>
                    <?php if(isset($ecmCarrinho->ecm_venda)): ?>
                        <br/>
                        <?= h('('.$this->Number->format($ecmCarrinho->ecm_venda->numero_parcelas).'x de '.
                            $this->Number->format($ecmCarrinho->ecm_venda->valor_parcelas,
                                ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' no '.
                            h($ecmCarrinho->ecm_venda->ecm_tipo_pagamento->nome).')') ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($ecmCarrinho->status == "Finalizado"): ?>
                        <div style="color:green">
                    <?php elseif($ecmCarrinho->status == "Cancelado"): ?>
                        <div style="color:red">
                    <?php else: ?>
                        <div style="color:blue">
                    <?php endif; ?>
                            <?= h($ecmCarrinho->status) ?>
                        </div>
                </td>
                <td>
                    <?php foreach ($ecmCarrinho->ecm_carrinho_item as $ecmCarrinhoItem): ?>
                        <?php
                            $tipo = '';
                            $produtoAltoqi = false;
                            foreach($ecmCarrinhoItem->ecm_produto->ecm_tipo_produto as $tipoProduto){
                                if(strpos(strtolower($tipoProduto->get('nome')), 'presencial') != false)
                                    $tipo = __('Presencial');
                                elseif(strpos(strtolower($tipoProduto->get('nome')), 'online') != false)
                                    $tipo = __('Online');
                                elseif(strpos(strtolower($tipoProduto->get('nome')), 'produtos altoqi') !== false)
                                    $produtoAltoqi = true;
                            }
                        ?>
                <?php if($ecmCarrinhoItem->status == "Removido"): ?>
                <strike>
                <?php endif; ?>
                    <?= $ecmCarrinhoItem->ecm_produto->sigla.' <b>('.$tipo.')</b>' ?>
                    <?php if($produtoAltoqi): ?>
                    <?= $this->Form->button('Detalhar', ['type' => 'button', 'onclick' => 'detalhar('.$ecmCarrinhoItem->id.','.$ecmCarrinhoItem->ecm_produto->id.');']) ?>
                    <?php endif; ?>
                    <br/>
                <?php if($ecmCarrinhoItem->status == "Removido"): ?>
                </strike>
                <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
<script>
    $(function() {
        $("#inicio").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#fim").datepicker("option", "minDate", $(this).datepicker("getDate"));
        });
        $("#fim").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#inicio").datepicker("option", "maxDate", $(this).datepicker("getDate"));
        });
    });
    function detalhar(item, produto){
        $.post("", {'item' : item, 'produto' : produto}, function( data ) {
       
            bootbox.alert(data['descricao']);
        }, "json");
    }
</script>