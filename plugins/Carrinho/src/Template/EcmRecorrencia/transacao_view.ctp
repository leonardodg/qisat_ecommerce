<div class="ecmRecorrencia col-md-12" style="margin-bottom: 10px;">
    <h3>Recorrência: <?= h($ecmRecorrencia->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Mdl User') ?></th>
            <td><?= $ecmRecorrencia->has('mdl_user') ? $this->Html->link($ecmRecorrencia->mdl_user->firstname.' '.$ecmRecorrencia->mdl_user->lastname, ['controller' => 'MdlUser', 'action' => 'view', $ecmRecorrencia->mdl_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Tipo Pagamento') ?></th>
            <td><?= $ecmRecorrencia->has('ecm_tipo_pagamento') ? $this->Html->link($ecmRecorrencia->ecm_tipo_pagamento->nome, ['controller' => 'EcmTipoPagamento', 'action' => 'view', $ecmRecorrencia->ecm_tipo_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Ecm Operadora Pagamento') ?></th>
            <td><?= $ecmRecorrencia->has('ecm_operadora_pagamento') ? $this->Html->link($ecmRecorrencia->ecm_operadora_pagamento->nome, ['controller' => 'EcmOperadoraPagamento', 'action' => 'view', $ecmRecorrencia->ecm_operadora_pagamento->id]) : '' ?></td>
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
        <tr>
            <th><?= __('Número de cobranca total') ?></th>
            <td><?= h($ecmRecorrencia->numero_cobranca_total) ?></td>
        </tr>
        <tr>
            <th><?= __('Número de cobrancas restantes') ?></th>
            <td><?= h($ecmRecorrencia->numero_cobranca_restantes) ?></td>
        </tr>
        <tr>
            <th><?= __('Campainha') ?></th>
            <td><?= h($ecmRecorrencia->data_campainha) ?></td>
        </tr>
        <tr>
            <th><?= __('Capturar') ?></th>
            <td><?= h($ecmRecorrencia->capturar) ?></td>
        </tr>
        <tr>
            <th><?= __('Teste') ?></th>
            <td><?= h($ecmRecorrencia->teste) ?></td>
        </tr>
        <?php if(!is_null($ecmRecorrencia->erro)): ?>
            <tr>
                <th><?= __('Erro') ?></th>
                <td><?= $this->Text->autoParagraph(h($ecmRecorrencia->erro)) ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php foreach ($ecmRecorrencia->ecm_venda->ecm_transacao as $ecmTransacao): ?>
    <div class="ecmTransacao col-md-12">
        <h3>Transação: <?= h($ecmTransacao->id) ?></h3>
        <table class="vertical-table">
            <tr>
                <th><?= __('Status da Transação') ?></th>
                <td><?= $ecmTransacao->has('ecm_transacao_status') ? $this->Html->link($ecmTransacao->ecm_transacao_status->status, ['controller' => 'EcmTransacaoStatus', 'action' => 'view', $ecmTransacao->ecm_transacao_status->id]) : '' ?></td>
            </tr>
            <tr>
                <th><?= __('Valor') ?></th>
                <td><?= $this->Number->format($ecmTransacao->valor) ?></td>
            </tr>
            <tr>
                <th><?= __('Parcela') ?></th>
                <td><?= $this->Number->format($ecmTransacao->numero_parcela) ?></td>
            </tr>
            <tr>
                <th><?= __('Data Envio') ?></th>
                <td><?= h($ecmTransacao->data_envio) ?></td>
            </tr>
            <tr>
                <th><?= __('Data Retorno') ?></th>
                <td><?= h($ecmTransacao->data_retorno) ?></td>
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
                <th><?= __('Capturar') ?></th>
                <td><?= h($ecmTransacao->capturar) ?></td>
            </tr>
            <tr>
                <th><?= __('Teste') ?></th>
                <td><?= h($ecmTransacao->teste) ?></td>
            </tr>
            <?php if(!is_null($ecmTransacao->erro)): ?>
                <tr>
                    <th><?= __('Erro') ?></th>
                    <td><?= h($ecmTransacao->erro) ?></td>
                </tr>
            <?php endif; ?>
            <?php if(!is_null($ecmTransacao->descricao)): ?>
                <tr>
                    <th><?= __('Descricao') ?></th>
                    <td><?= h($ecmTransacao->descricao) ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
<?php endforeach; ?>
