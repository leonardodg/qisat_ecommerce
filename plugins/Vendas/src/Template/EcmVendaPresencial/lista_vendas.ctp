<div class="ecmVendaPresencial col-md-12">
    <legend><?= __('Controle de Curso Presencial') ?></legend>
    <h3>
        <?= $ecmCursoPresencialTurma->ecm_produto->nome ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->nome ?>
    </h3>
    <?= $this->element('carrinho_andamento',['carrinho' => $ecmCarrinho]);?>
    <!-- Vendas Através da Central -->
    <legend><?= __('Vendas Através da Central') ?></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('') ?></th>
            <th><?= $this->Paginator->sort('nome', 'Nome do Cliente') ?></th>
            <th><?= $this->Paginator->sort('data', 'Data | Hora') ?></th>
            <th><?= $this->Paginator->sort('quantidade_reserva', 'Quantidade') ?></th>
            <th><?= $this->Paginator->sort('status') ?></th>
            <th><?= $this->Paginator->sort('Editar') ?></th>
            <th><?= $this->Paginator->sort('Excluir') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $quantCentral = 0;
            foreach ($ecmVendaPresencial as $key => $ecmVendaPresencial):
                $quantCentral += $ecmVendaPresencial->quantidade_reserva; ?>
            <tr>
                <td><?= $this->Number->format($key + 1) ?></td>
                <td><?= h($ecmVendaPresencial->nome) ?></td>
                <td><?= $ecmVendaPresencial->data ?></td>
                <td><?= $this->Number->format($ecmVendaPresencial->quantidade_reserva) ?></td>
                <td>
                    <?php if($ecmVendaPresencial->status=="Reservado"): ?>
                        <div style="color:blue">
                    <?php elseif($ecmVendaPresencial->status=="Vendido"): ?>
                        <div style="color:green">
                    <?php elseif($ecmVendaPresencial->status=="Espera"): ?>
                        <div style="color:black">
                    <?php else: ?>
                        <div>
                    <?php endif; ?>
                    <?= h($ecmVendaPresencial->status) ?></div>
                </td>
                <td><?= $this->Html->image("../img/edit.gif", ['url' => ['controller' => 'presencial', 'action' => 'edit', $ecmVendaPresencial->id]]) ?></td>
                <td><?= $this->Form->create('', ['url' => ['controller' => 'presencial', 'action' => 'delete', $ecmVendaPresencial->id],
                        'name' => 'delete'.$ecmVendaPresencial->id]) ?><?= $this->Form->end() ?>
                    <?= $this->Html->image("../img/delete.gif", ['onclick' =>
                        'bootbox.confirm("Tem certeza de que deseja excluir?", function(result) {
                            if(result){document.delete'.$ecmVendaPresencial->id.'.submit();}else{}
                        });event.returnValue = false; return false;', 'style' => 'cursor: pointer;']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
            <tr>
                <td colspan="3"></td>
                <td><?= $this->Number->format($quantCentral) ?></td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    <!-- Vendas Através do Carrinho -->
    <legend><?= __('Vendas Através do Carrinho') ?></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('') ?></th>
            <th><?= $this->Paginator->sort('nome', 'Nome do Cliente') ?></th>
            <th><?= $this->Paginator->sort('data', 'Data | Hora') ?></th>
            <th><?= $this->Paginator->sort('quantidade_reserva', 'Quantidade') ?></th>
            <th><?= $this->Paginator->sort('status') ?></th>
            <th><?= $this->Paginator->sort('forma_pagamento_id', 'Forma Pagamento') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $quantCarrinho = 0;
            foreach ($ecmCarrinho as $key => $carrinho):
                if($carrinho->status!="Cancelado"){
                    $quantCarrinho += $carrinho->ecm_carrinho_item[0]->quantidade;
                } ?>
            <tr>
                <td><?= $this->Number->format($key + 1) ?></td>
                <td><?= h($carrinho->mdl_user->firstname.' '.$carrinho->mdl_user->lastname) ?></td>
                <td><?= $carrinho->data ?></td>
                <td><?= $this->Number->format($carrinho->ecm_carrinho_item[0]->quantidade) ?></td>
                <td>
                    <?php if($carrinho->status=="Em Aberto"): ?>
                        <div style="color:blue">
                    <?php elseif($carrinho->status=="Finalizado"): ?>
                        <div style="color:green">
                    <?php elseif($carrinho->status=="Cancelado"): ?>
                        <div style="color:red">
                    <?php else: ?>
                        <div>
                    <?php endif; ?>
                    <?= h($carrinho->status) ?></div>
                </td>
                <td>
                    <?php if(!empty($carrinho->ecm_venda->ecm_venda_boleto)): ?>
                        <?= count($carrinho->ecm_venda->ecm_venda_boleto) ?>x Boleto
                        <?php
                            $emAberto = 0;$pago = 0;$npago = 0;
                            foreach ($carrinho->ecm_venda->ecm_venda_boleto as $ecm_venda_boleto){
                                switch($ecm_venda_boleto->status){
                                    case "Em aberto": $emAberto++; break;
                                    case "Pago": $pago++; break;
                                    case "nPago": $npago++; break;
                                }
                            }
                        ?>
                        <?php if(!empty($emAberto)): ?>
                            <hr><div style="color:blue"><?= $emAberto ?>x Em Aberto</div>
                        <?php endif; ?>
                        <?php if(!empty($pago)): ?>
                            <hr><div style="color:green"><?= $pago ?>x Pago</div>
                        <?php endif; ?>
                        <?php if(!empty($npago)): ?>
                            <hr><div style="color:red"><?= $npago ?>x Não Pago</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"></td>
            <td><?= $this->Number->format($quantCarrinho) ?></td>
            <td colspan="2"></td>
        </tr>
        </tbody>
    </table>
    <!-- Vagas -->
    <legend>
        Número de Vagas: <?= $ecmCursoPresencialTurma->vagas_total ?> /
        Vagas Preenchidas: <?= $quantCentral+$quantCarrinho ?>
    </legend>
</div>
