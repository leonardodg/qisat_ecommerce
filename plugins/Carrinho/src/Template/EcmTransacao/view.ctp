<div class="ecmTransacao col-md-12">
    <h3><?= h($ecmTransacao->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Ecm Transacao Status') ?></th>
            <td><?= $ecmTransacao->has('ecm_transacao_status') ? $this->Html->link($ecmTransacao->ecm_transacao_status->id, ['controller' => 'EcmTransacaoStatus', 'action' => 'view', $ecmTransacao->ecm_transacao_status->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Mdl User') ?></th>
            <td><?= $ecmTransacao->has('mdl_user') ? $this->Html->link($ecmTransacao->mdl_user->id, ['controller' => 'MdlUser', 'action' => 'view', $ecmTransacao->mdl_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Tipo Pagamento') ?></th>
            <td><?= $ecmTransacao->has('ecm_tipo_pagamento') ? $this->Html->link($ecmTransacao->ecm_tipo_pagamento->id, ['controller' => 'EcmTipoPagamento', 'action' => 'view', $ecmTransacao->ecm_tipo_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Operadora Pagamento') ?></th>
            <td><?= $ecmTransacao->has('ecm_operadora_pagamento') ? $this->Html->link($ecmTransacao->ecm_operadora_pagamento->id, ['controller' => 'EcmOperadoraPagamento', 'action' => 'view', $ecmTransacao->ecm_operadora_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Venda') ?></th>
            <td><?= $ecmTransacao->has('ecm_venda') ? $this->Html->link($ecmTransacao->ecm_venda->id, ['controller' => 'EcmVenda', 'action' => 'view', $ecmTransacao->ecm_venda->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Tid') ?></th>
            <td><?= h($ecmTransacao->tid) ?></td>
        </tr>
        <tr>
            <th><?= __('Nsu') ?></th>
            <td><?= h($ecmTransacao->nsu) ?></td>
        </tr>
        <tr>
            <th><?= __('Pan') ?></th>
            <td><?= h($ecmTransacao->pan) ?></td>
        </tr>
        <tr>
            <th><?= __('Arp') ?></th>
            <td><?= h($ecmTransacao->arp) ?></td>
        </tr>
        <tr>
            <th><?= __('Lr') ?></th>
            <td><?= h($ecmTransacao->lr) ?></td>
        </tr>
        <tr>
            <th><?= __('Url') ?></th>
            <td><?= h($ecmTransacao->url) ?></td>
        </tr>
        <tr>
            <th><?= __('Ip') ?></th>
            <td><?= h($ecmTransacao->ip) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmTransacao->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Id Integracao') ?></th>
            <td><?= $this->Number->format($ecmTransacao->id_integracao) ?></td>
        </tr>
        <tr>
            <th><?= __('Valor') ?></th>
            <td><?= $this->Number->format($ecmTransacao->valor) ?></td>
        </tr>
        <tr>
            <th><?= __('Data Envio') ?></th>
            <td><?= h($ecmTransacao->data_envio) ?></td>
        </tr>
        <tr>
            <th><?= __('Data Retorno') ?></th>
            <td><?= h($ecmTransacao->data_retorno) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Capturar') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmTransacao->capturar)); ?>
    </div>
    <div class="row">
        <h4><?= __('Descricao') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmTransacao->descricao)); ?>
    </div>
    <div class="row">
        <h4><?= __('Erro') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmTransacao->erro)); ?>
    </div>
    <div class="row">
        <h4><?= __('Teste') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmTransacao->teste)); ?>
    </div>
</div>
