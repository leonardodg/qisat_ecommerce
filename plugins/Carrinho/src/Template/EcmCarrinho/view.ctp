<div class="ecmCarrinho col-md-12">
    <h3><?= h('Visualizar Carrinho') ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Data de criação do Carrinho de Compras') ?></th>
            <td><?= h($ecmCarrinho->data->format('d/m/Y \à\s H:i:s')) ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Status do Carrinho') ?></th>
            <th><?= __('Status da Venda') ?></th>
        </tr>
        <tr>
            <td><?= h($ecmCarrinho->status) ?></td>
            <td>
                <?= h(isset($ecmCarrinho->ecm_venda)?
                    $ecmCarrinho->ecm_venda->ecm_venda_status->status:
                    'Venda não finalizada') ?>
            </td>
        </tr>
    </table>
    <table class="vertical-table">
        <tr>
            <th><?= __('Número do Pedido') ?></th>
            <td><?= h(isset($ecmCarrinho->ecm_venda)?$ecmCarrinho->ecm_venda->pedido:'') ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Produto') ?></th>
            <th><?= __('Quant. Inscrições') ?></th>
            <th><?= __('Valor Unitário') ?></th>
            <th><?= __('Valor') ?></th>
            <th><?= __('Desconto') ?></th>
            <th><?= __('Status') ?></th>
        </tr>
        <?php $quant = 0;
        foreach ($ecmCarrinho->ecm_carrinho_item as $ecmCarrinhoItem):

            $tipo = '';
            foreach($ecmCarrinhoItem->ecm_produto->ecm_tipo_produto as $tipoProduto){
                if(strpos(strtolower($tipoProduto->get('nome')), 'presencial') != false)
                    $tipo = __('Presencial');
                elseif(strpos(strtolower($tipoProduto->get('nome')), 'online') != false)
                    $tipo = __('Online');
            }

            $local = '';
            $data = '';
            if(!is_null($ecmCarrinhoItem->ecm_curso_presencial_turma)) {
                $dataInicio = current($ecmCarrinhoItem->ecm_curso_presencial_turma->ecm_curso_presencial_data);
                $dataFim = end($ecmCarrinhoItem->ecm_curso_presencial_turma->ecm_curso_presencial_data);

                $local = $dataInicio->get('ecm_curso_presencial_local');
                $local = $local->get('mdl_cidade')->get('nome') . '/' . $local->get('mdl_cidade')->get('mdl_estado')->uf;

                $data = $dataInicio->get('datainicio')->format('d/m/y');
                $data .= ' - ' . $dataFim->get('datainicio')->format('d/m/y');
            }

            if($ecmCarrinhoItem->status == 'Adicionado')
                $quant += $ecmCarrinhoItem->quantidade;

            $desconto = ' - ';

            if(!is_null($ecmCarrinhoItem->ecm_promocao)) {
                $promocao = $ecmCarrinhoItem->ecm_promocao;
                $desconto = is_null($promocao->get('descontovalor'))? $promocao->get('descontoporcentagem').'%':'R$ '.$promocao->get('descontovalor');
                $desconto = __('Promoção').':<br />'.$ecmCarrinhoItem->ecm_promocao->get('descricao').' ('.$desconto.')';
            }elseif(!is_null($ecmCarrinhoItem->ecm_cupom)){
                $cupom = $ecmCarrinhoItem->ecm_cupom;
                $desconto = is_null($cupom->get('descontovalor'))? $cupom->get('descontoporcentagem').'%':'R$ '.$cupom->get('descontovalor');
                $desconto = __('Cupom').':<br />'.$ecmCarrinhoItem->ecm_cupom->get('nome').' ('.$desconto.')';
            }
            ?>

            <tr>
                <td><?= $ecmCarrinhoItem->ecm_produto->nome.' ('.$tipo.')<br />'.$local.'<br />'.$data ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->quantidade) ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->valor_produto, ['pattern' => '#.###,00', 'places' => 2]) ?></td>
                <td><?= $this->Number->format($ecmCarrinhoItem->quantidade*$ecmCarrinhoItem->valor_produto_desconto, ['pattern' => '#.###,00', 'places' => 2]) ?></td>
                <td><?= $desconto?></td>
                <td style="color:<?= ($ecmCarrinhoItem->status == 'Adicionado' ? 'green' : 'red')?>;">
                    <?= h($ecmCarrinhoItem->status) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <table class="vertical-table">
        <tr>
            <th><?= __('Total') ?></th>
            <td><?= $this->Number->format($ecmCarrinho->calcularTotal(), ['pattern' => '#.###,00', 'places' => 2]) ?></td>
        </tr>
        <tr>
            <th><?= __('Total de Produtos Adquiridos') ?></th>
            <td><?= $this->Number->format($quant) ?></td>
        </tr>
        <tr>
            <th><?= __('Forma de Pagamento') ?></th>
            <td>
                    <?= isset($ecmCarrinho->ecm_venda)?h($ecmCarrinho->ecm_venda->numero_parcelas."x de ").
                    $this->Number->format($ecmCarrinho->ecm_venda->valor_parcelas,
                        ['pattern' => '#.###,00', 'places' => 2]).
                    h(" no ".$ecmCarrinho->ecm_venda->ecm_tipo_pagamento->nome):h('Venda não finalizada') ?>
            </td>
        </tr>
    </table>
    <?php if(isset($ecmCarrinho->mdl_user)): ?>
    <h4><?= __('Dados do Usuário') ?></h4>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Nome') ?></th>
            <th><?= __('E-mail') ?></th>
            <th><?= __('Chave AltoQi') ?></th>
            <th><?= __('Telefone') ?></th>
        </tr>
        <tr>
            <td><?= h($ecmCarrinho->mdl_user->firstname." ".$ecmCarrinho->mdl_user->lastname) ?></td>
            <td><?= h($ecmCarrinho->mdl_user->email) ?></td>
            <td><?= h($ecmCarrinho->mdl_user->idnumber) ?></td>
            <td><?= h($ecmCarrinho->mdl_user->phone1) ?></td>
        </tr>
    </table>
    <?php endif; ?>
</div>
