<div class="ecmTipoProduto col-md-12">
    <h3><?= h($ecmTipoProduto->nome) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmTipoProduto->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmTipoProduto->nome) ?></td>
        </tr>
        <?php
            if(!is_null($ecmTipoProduto->EcmTipoProduto)){
                echo '<tr>
                        <th>'.__('Tipo de produto relacionado').'</th>
                        <td>'.$ecmTipoProduto->EcmTipoProduto->nome.'</td>
                    </tr>';
            }
        ?>

        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmTipoProduto->habilitado == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
        <tr>
            <th><?= __('Bloqueio de Exclusão') ?></th>
            <td><?= $ecmTipoProduto->blocked == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Produtos Relacionados') ?></h4>
        <?php if (!empty($ecmTipoProduto->ecm_produto)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Nome') ?></th>
                <th><?= __('Sigla') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmTipoProduto->ecm_produto as $ecmProduto): ?>
            <tr>
                <td><?= h($ecmProduto->nome) ?></td>
                <td><?= h($ecmProduto->sigla) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmProduto->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
