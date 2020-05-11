<div class="ecmOperadoraPagamento col-md-12">
    <h3><?= $ecmOperadoraPagamento->nome ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= h($ecmOperadoraPagamento->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Nome atribuido para realizar conexão com os mecanimos de Pagamento') ?></th>
            <td><?= h($ecmOperadoraPagamento->dataname) ?></td>
        </tr>
        <tr>
            <th><?= __('Descrição') ?></th>
            <td><?= h($ecmOperadoraPagamento->descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Imagem') ?></th>

            <td><?= $this->Html->image('/upload/'.$ecmOperadoraPagamento->ecm_imagem->src);?></td>
        </tr>
        <tr>
            <th><?= __('Forma de Pagamento') ?></th>
            <td><?= $ecmOperadoraPagamento->has('ecm_forma_pagamento') ? $this->Html->link($ecmOperadoraPagamento->ecm_forma_pagamento->nome, ['controller' => '', 'action' => 'view', $ecmOperadoraPagamento->ecm_forma_pagamento->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmOperadoraPagamento->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmOperadoraPagamento->habilitado == 'true' ?__('Sim'):__('Não'); ?></td>
        </tr>
    </table>
</div>
