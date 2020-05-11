<div class="ecmPromocao col-md-12">
    <div class="row">
        <h4><?= __('Descrição') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmPromocao->descricao)); ?>
    </div>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmPromocao->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Data de inicio') ?></th>
            <td><?= \Cake\I18n\Time::parse($ecmPromocao->datainicio)->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th><?= __('Data de fim') ?></th>
            <td><?= \Cake\I18n\Time::parse($ecmPromocao->datafim)->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th><?= __('Desconto em valor') ?></th>
            <td><?= number_format($ecmPromocao->descontovalor,2,',','.') ?></td>
        </tr>
        <tr>
            <th><?= __('Desconto em porcentagem') ?></th>
            <td><?= number_format($ecmPromocao->descontoporcentagem,2,',','.') ?></td>
        </tr>
        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmPromocao->habilitado == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
        <tr>
            <th><?= __('Arredondamento') ?></th>
            <td><?= $ecmPromocao->arredondamento == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
        <tr>
            <th><?= __('Número máximo de parcelas') ?></th>
            <td><?= $ecmPromocao->numaxparcelas ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Entidades Relacionadas') ?></h4>
        <?php if (!empty($ecmPromocao->ecm_alternative_host)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Abreviação') ?></th>
                <th><?= __('Nome') ?></th>
            </tr>
            <?php foreach ($ecmPromocao->ecm_alternative_host as $ecmAlternativeHost): ?>
            <tr>
                <td><?= h($ecmAlternativeHost->id) ?></td>
                <td><?= h($ecmAlternativeHost->shortname) ?></td>
                <td><?= h($ecmAlternativeHost->fullname) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Produtos Relacionadas') ?></h4>
        <?php if (!empty($ecmPromocao->ecm_produto)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Sigla') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmPromocao->ecm_produto as $ecmProduto): ?>
            <tr>
                <td><?= h($ecmProduto->id) ?></td>
                <td><?= h($ecmProduto->nome) ?></td>
                <td><?= h($ecmProduto->sigla) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin'=>false, 'controller' => 'produto', 'action' => 'view', $ecmProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin'=>false, 'controller' => 'produto', 'action' => 'edit', $ecmProduto->id]) ?>
                    </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
