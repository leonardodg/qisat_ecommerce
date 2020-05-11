<div class="ecmVenda col-md-12">
    <h3><?= h('Relatório detalhado da venda') ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Data de criação do Carrinho de Compras') ?></th>
            <td><?= h($ecmVenda->ecm_carrinho->data->format('d/m/Y \à\s H:i:s')) ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Status do Carrinho') ?></th>
            <th><?= __('Status da Venda') ?></th>
        </tr>
        <tr>
            <td><?= h($ecmVenda->ecm_carrinho->status) ?></td>
            <td><?= h($ecmVenda->ecm_venda_status->status) ?></td>
        </tr>
    </table>
    <table class="vertical-table">
        <tr>
            <th><?= __('Número do Pedido') ?></th>
            <td><?= h($ecmVenda->pedido) ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Produto') ?></th>
            <th><?= __('Quant. Inscrições') ?></th>
            <th><?= __('Valor Unitário') ?></th>
            <th><?= __('Valor') ?></th>
            <th><?= __('Status') ?></th>
        </tr>
        <?php $quant = 0;
        foreach ($ecmVenda->ecm_carrinho->ecm_carrinho_item as $ecmCarrinhoItem):
            $quant += $ecmCarrinhoItem->quantidade; ?>
            <tr>
                <td><?= h($ecmCarrinhoItem->ecm_produto->nome) ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->quantidade) ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->valor_produto_desconto, ['pattern' => '#.###,00', 'places' => 2]) ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->quantidade*$ecmCarrinhoItem->valor_produto_desconto, ['pattern' => '#.###,00', 'places' => 2]) ?></td>
                <td><?= h($ecmCarrinhoItem->status) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <table class="vertical-table">
        <tr>
            <th><?= __('Total') ?></th>
            <td><?= $this->Number->format($ecmVenda->ecm_carrinho->calcularTotal(), ['pattern' => '#.###,00', 'places' => 2]) ?></td>
        </tr>
        <tr>
            <th><?= __('Total de Produtos Adquiridos') ?></th>
            <td><?= $this->Number->format($quant) ?></td>
        </tr>
        <tr>
            <th><?= __('Forma de Pagamento') ?></th>
            <td><?= h($ecmVenda->numero_parcelas."x de ").$this->Number->format($ecmVenda->valor_parcelas, ['pattern' => '#.###,00', 'places' => 2]).h(" no ".$ecmVenda->ecm_tipo_pagamento->nome) ?></td>
        </tr>
    </table>
    <?php if(isset($ecmVenda->ecm_carrinho->mdl_user)): ?>
    <h4><?= __('Dados do Usuário') ?></h4>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Nome') ?></th>
            <th><?= __('E-mail') ?></th>
            <th><?= __('Chave AltoQi') ?></th>
            <th><?= __('Telefone') ?></th>
        </tr>
        <tr>
            <td><?= h($ecmVenda->ecm_carrinho->mdl_user->firstname." ".$ecmVenda->ecm_carrinho->mdl_user->lastname) ?></td>
            <td><?= h($ecmVenda->ecm_carrinho->mdl_user->email) ?></td>
            <td><?= h($ecmVenda->ecm_carrinho->mdl_user->idnumber) ?></td>
            <td><?= h($ecmVenda->ecm_carrinho->mdl_user->phone1) ?></td>
        </tr>
    </table>
    <?php endif; ?>
</div>
