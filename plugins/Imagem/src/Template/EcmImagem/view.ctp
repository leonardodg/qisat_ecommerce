<div class="ecmImagem col-md-12">
    <h3><?= h($ecmImagem->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmImagem->nome) ?></td>
        </tr>
        <tr>
            <th><?= __('Src') ?></th>
            <td><?= h($ecmImagem->src) ?></td>
        </tr>
        <tr>
            <th><?= __('Descricao') ?></th>
            <td><?= h($ecmImagem->descricao) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmImagem->id) ?></td>
        </tr>
    </table>
    <?= $this->Html->image('../upload/' . $ecmImagem->src) ?>
    <?php if (!empty($ecmImagem->ecm_instrutor)): ?>
    <div class="related">
        <h4><?= __('Related Instrutor') ?></h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Mdl User Id') ?></th>
                <th><?= __('Descricao') ?></th>
                <th><?= __('Ecm Imagem Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmImagem->ecm_instrutor as $ecmInstrutor): ?>
            <tr>
                <td><?= h($ecmInstrutor->id) ?></td>
                <td><?= h($ecmInstrutor->mdl_user_id) ?></td>
                <td><?= h($ecmInstrutor->descricao) ?></td>
                <td><?= h($ecmInstrutor->ecm_imagem_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => 'Instrutor', 'controller' => 'EcmInstrutor', 'action' => 'view', $ecmInstrutor->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => 'Instrutor', 'controller' => 'EcmInstrutor', 'action' => 'edit', $ecmInstrutor->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php if (!empty($ecmImagem->ecm_operadora_pagamento)): ?>
    <div class="related">
        <h4><?= __('Related Operadora Pagamento') ?></h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Dataname') ?></th>
                <th><?= __('Descricao') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Ecm Imagem Id') ?></th>
                <th><?= __('Ecm Forma Pagamento Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmImagem->ecm_operadora_pagamento as $ecmOperadoraPagamento): ?>
            <tr>
                <td><?= h($ecmOperadoraPagamento->id) ?></td>
                <td><?= h($ecmOperadoraPagamento->nome) ?></td>
                <td><?= h($ecmOperadoraPagamento->dataname) ?></td>
                <td><?= h($ecmOperadoraPagamento->descricao) ?></td>
                <td><?= h($ecmOperadoraPagamento->habilitado) ?></td>
                <td><?= h($ecmOperadoraPagamento->ecm_imagem_id) ?></td>
                <td><?= h($ecmOperadoraPagamento->ecm_forma_pagamento_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => 'FormaPagamento', 'controller' => 'EcmOperadoraPagamento', 'action' => 'view', $ecmOperadoraPagamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => 'FormaPagamento', 'controller' => 'EcmOperadoraPagamento', 'action' => 'edit', $ecmOperadoraPagamento->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php if (!empty($ecmImagem->ecm_rede_social)): ?>
    <div class="related">
        <h4><?= __('Related Rede Social') ?></h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Ecm Imagem Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmImagem->ecm_rede_social as $ecmRedeSocial): ?>
            <tr>
                <td><?= h($ecmRedeSocial->id) ?></td>
                <td><?= h($ecmRedeSocial->nome) ?></td>
                <td><?= h($ecmRedeSocial->ecm_imagem_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => 'Configuracao', 'controller' => 'EcmRedeSocial', 'action' => 'view', $ecmRedeSocial->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => 'Configuracao', 'controller' => 'EcmRedeSocial', 'action' => 'edit', $ecmRedeSocial->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php if (!empty($ecmImagem->ecm_produto)): ?>
    <div class="related">
        <h4><?= __('Related Produto') ?></h4>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Preco') ?></th>
                <th><?= __('Sigla') ?></th>
                <th><?= __('Parcela') ?></th>
                <th><?= __('Refcurso') ?></th>
                <th><?= __('Moeda') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Visivel') ?></th>
                <th><?= __('Idtop') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmImagem->ecm_produto as $ecmProduto): ?>
            <tr>
                <td><?= h($ecmProduto->id) ?></td>
                <td><?= h($ecmProduto->nome) ?></td>
                <td><?= h($ecmProduto->preco) ?></td>
                <td><?= h($ecmProduto->sigla) ?></td>
                <td><?= h($ecmProduto->parcela) ?></td>
                <td><?= h($ecmProduto->refcurso) ?></td>
                <td><?= h($ecmProduto->moeda) ?></td>
                <td><?= h($ecmProduto->habilitado) ?></td>
                <td><?= h($ecmProduto->visivel) ?></td>
                <td><?= h($ecmProduto->idtop) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => 'Produto', 'controller' => 'EcmProduto', 'action' => 'view', $ecmProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => 'Produto', 'controller' => 'EcmProduto', 'action' => 'edit', $ecmProduto->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>
