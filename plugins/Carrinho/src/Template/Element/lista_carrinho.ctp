<h4><?= __('Lista de Carrinhos') ?> </h4>

    <?php if(count($ecmCarrinho->toArray()) > 0):?>

<table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveCarrinhoList">
        <thead>
            <tr>
                <th><?=  __('Data | Hora') ?></th>
                <th><?=  __('Usuário') ?></th>
                <th><?= __('Total') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Itens') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCarrinho as $ecmCarrinho): ?>
            <tr>
                <td><?= h($ecmCarrinho->data->format('d/m/Y H:i:s')) ?></td>
                <td>
                    <?php
                        $nome = isset($ecmCarrinho->mdl_user_id)?
                            h($ecmCarrinho->mdl_user->firstname.' '.$ecmCarrinho->mdl_user->lastname):
                            'Visitante';

                        echo h($nome);
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
                    <?php foreach ($ecmCarrinho->ecm_carrinho_item as $ecmCarrinhoItem): ?>
                        <?php
                            $tipo = '';
                            foreach($ecmCarrinhoItem->ecm_produto->ecm_tipo_produto as $tipoProduto){
                                if(strpos(strtolower($tipoProduto->get('nome')), 'presencial') != false)
                                    $tipo = __('Presencial');
                                elseif(strpos(strtolower($tipoProduto->get('nome')), 'online') != false)
                                    $tipo = __('Online');
                            }
                        ?>
                        <?= $ecmCarrinhoItem->ecm_produto->sigla.' <b>('.$tipo.')</b>' ?><br/>
                    <?php endforeach; ?>
                </td>

                <td>
                    <?php if($ecmCarrinho->status == "Finalizado"): ?>
                        <div style="color:green">
                    <?php elseif($ecmCarrinho->status == "Cancelado"): ?>
                        <div style="color:red">

                                <?= $this->Form->create() ?>
                                <?= $this->Form->hidden("edit", [ 'value' => $ecmCarrinho->id ]) ?>
                                <?= $this->Form->button('Editar', ['type' => 'submit', 'class' => 'edit']) ?>
                                <?= $this->Form->end() ?>

                    <?php else: ?>
                        <div style="color:blue">
                                <?= $this->Form->create() ?>
                                <?= $this->Form->hidden("edit", [ 'value' => $ecmCarrinho->id ]) ?>
                                <?= $this->Form->button('Editar', ['type' => 'submit', 'class' => 'edit']) ?>
                                <?= $this->Form->end() ?>
                    <?php endif; ?>
                            <?= h($ecmCarrinho->status) ?>
                        </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <h5><?= __('Nenhum Carrinho') ?> </h5>  
<?php endif; ?>