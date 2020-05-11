Prezados,
<br/><br/>


<?php if ($falha): ?>
    Atualização pagamento/vencimento da Recorrência <strong><?= (is_object($recorrencia)) ? $recorrencia->id : $recorrencia ?> (FALHA). </strong> <br> <br>
<?php else: ?>

    Atualização pagamento/vencimento da Recorrência <strong><?= (is_object($recorrencia)) ? $recorrencia->id : $recorrencia ?> </strong> <br> <br>

    <?php if(is_object($recorrencia)): ?>
        <strong>Cliente:</strong> <?= $recorrencia->get('mdl_user')->get('idnumber').' - '.$recorrencia->get('mdl_user')->get('firstname').' '.$recorrencia->get('mdl_user')->get('lastname')?> <br>
        <strong>Venda:</strong> <?= $recorrencia->get('ecm_venda')->id ?> <br>
        <strong>Pedido Online:</strong> <?= $recorrencia->get('ecm_venda')->pedido ?> <br>
        <strong>Proposta:</strong> <?= $recorrencia->get('ecm_venda')->proposta ?> <br>
        <strong>Parcela:</strong> <?= ($recorrencia->quantidade_cobrancas - $recorrencia->numero_cobranca_restantes) .'/'. $recorrencia->get('ecm_venda')->numero_parcelas ?> <br>
    <?php endif; ?>

    <?php if(isset($transacao)): ?>
        <strong>Status Pagamento: </strong> <?= strtoupper($transacao->getStatus($transacao->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller)) ?> <br>
        <?php ($transacao->arp) ? '<strong>Código de Autorização:</strong>'.$transacao->arp.'<br>' : '' ?>
        <strong>Data da Atualização:</strong> <?= h($transacao->data_campainha->format('d/m/Y H:i:s')) ?><br>
        <?php ($transacao->arp) ? '<strong>TID:</strong>'.$transacao->tid.'<br>' : '' ?>
        <strong>Valor:</strong> <?=  $this->Number->format($transacao->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?><br>
    <?php endif; ?>

<?php endif; ?>

<?php if(count($msg)): ?>
    <strong>Mensagens de Erros:</strong> <br>
    <?php foreach ($msg as $value):?> 
            <?= $value ?> <br>
    <?php endforeach;?>  
<?php endif; ?>
