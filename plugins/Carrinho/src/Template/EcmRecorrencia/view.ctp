<div class="ecmRecorrencia col-md-12">
    <h3><?= h($ecmRecorrencia->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Mdl User') ?></th>
            <td><?= $ecmRecorrencia->has('mdl_user') ? $this->Html->link($ecmRecorrencia->mdl_user->id, ['controller' => 'MdlUser', 'action' => 'view', $ecmRecorrencia->mdl_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Tipo Pagamento') ?></th>
            <td><?= $ecmRecorrencia->has('ecm_tipo_pagamento') ? $this->Html->link($ecmRecorrencia->ecm_tipo_pagamento->id, ['controller' => 'EcmTipoPagamento', 'action' => 'view', $ecmRecorrencia->ecm_tipo_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Operadora Pagamento') ?></th>
            <td><?= $ecmRecorrencia->has('ecm_operadora_pagamento') ? $this->Html->link($ecmRecorrencia->ecm_operadora_pagamento->id, ['controller' => 'EcmOperadoraPagamento', 'action' => 'view', $ecmRecorrencia->ecm_operadora_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Venda') ?></th>
            <td><?= $ecmRecorrencia->has('ecm_venda') ? $this->Html->link($ecmRecorrencia->ecm_venda->id, ['controller' => 'EcmVenda', 'action' => 'view', $ecmRecorrencia->ecm_venda->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ip') ?></th>
            <td><?= h($ecmRecorrencia->ip) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmRecorrencia->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Valor') ?></th>
            <td><?= $this->Number->format($ecmRecorrencia->valor) ?></td>
        </tr>
        <tr>
            <th><?= __('Data Envio') ?></th>
            <td><?= h($ecmRecorrencia->data_envio) ?></td>
        </tr>
        <tr>
            <th><?= __('Data Retorno') ?></th>
            <td><?= h($ecmRecorrencia->data_retorno) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Capturar') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmRecorrencia->capturar)); ?>
    </div>
    <div class="row">
        <h4><?= __('Erro') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmRecorrencia->erro)); ?>
    </div>
    <div class="row">
        <h4><?= __('Teste') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmRecorrencia->teste)); ?>
    </div>
</div>
