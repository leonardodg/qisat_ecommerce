<div class="ecmCupom col-md-12">
    <h3><?= h($ecmCupom->nome) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmCupom->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Data de inicio') ?></th>
            <td><?= \Cake\I18n\Time::parse($ecmCupom->datainicio)->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th><?= __('Data de fim') ?></th>
            <td><?= \Cake\I18n\Time::parse($ecmCupom->datafim)->format('d/m/Y') ?></td>
        </tr>

        <tr>
            <th><?= __('Desconto') ?></th>
            <?php if($ecmCupom->descontovalor > 0):?>
                <td><?=__('R$').' '.number_format($ecmCupom->descontovalor, 2 ,',','.') ?></td>
            <?php else:?>
                <td><?= number_format($ecmCupom->descontoporcentagem, 2 ,',','.').'%';?></td>
            <?php endif;?>
        </tr>
        <tr>
            <th><?= __('Número de utilizações') ?></th>
            <td><?= $this->Number->format($ecmCupom->numutilizacoes) ?></td>
        </tr>
        <tr>
            <th><?= __('Habilitado') ?></th>
            <td><?= $ecmCupom->habilitado == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
        <tr>
            <th><?= __('Cupom referente à') ?></th>
            <td><?= $ecmCupom->tipo == 'produto'? __('Produto'):__('Tipo de Produto') ?></td>
        </tr>
        <tr>
            <th><?= __('Arredondamento') ?></th>
            <td><?= $ecmCupom->arredondamento == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
        <tr>
            <th><?= __('Desconto sobre o valor de tabela') ?></th>
            <td><?= $ecmCupom->descontosobretabela == 'true'? __('Sim'):__('Não') ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Descrição') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmCupom->descricao)); ?>
    </div>

    <?php if($ecmCupom->tipo == 'produto'):?>
    <div class="related">
        <h4><?= __('Produtos Relacionados') ?></h4>
        <?php if (!empty($ecmCupom->ecm_produto)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Sigla') ?></th>
                <th><?= __('Preco') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmCupom->ecm_produto as $ecmProduto): ?>
            <tr>
                <td><?= h($ecmProduto->id) ?></td>
                <td><?= h($ecmProduto->nome) ?></td>
                <td><?= h($ecmProduto->sigla) ?></td>
                <td><?= h($ecmProduto->preco) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => false,'controller' => 'produto', 'action' => 'view', $ecmProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => false,'controller' => 'produto', 'action' => 'edit', $ecmProduto->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

    <?php elseif($ecmCupom->tipo == 'tipo'):?>
        <div class="related">
            <h4><?= __('Tipo de Produto Relacionado') ?></h4>
            <?php if (!empty($ecmCupom->ecm_tipo_produto)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?= __('Id') ?></th>
                        <th><?= __('Nome') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                    <?php foreach ($ecmCupom->ecm_tipo_produto as $ecmTipoProduto): ?>
                        <tr>
                            <td><?= h($ecmTipoProduto->id) ?></td>
                            <td><?= h($ecmTipoProduto->nome) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['plugin' => 'produto','controller' => 'tipo-produto', 'action' => 'view', $ecmTipoProduto->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['plugin' => 'produto','controller' => 'tipo-produto', 'action' => 'edit', $ecmTipoProduto->id]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

    <?php else:?>
    <div class="related">
        <h4><?= __('Empresa Relacionada') ?></h4>
        <?php if (!empty($ecmCupom->ecm_alternative_host)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmCupom->ecm_alternative_host as $ecmAlternativeHost): ?>
            <tr>
                <td><?= h($ecmAlternativeHost->id) ?></td>
                <td><?= h($ecmAlternativeHost->fullname) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => 'produto','controller' => 'tipo-produto', 'action' => 'view', $ecmAlternativeHost->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => 'produto','controller' => 'tipo-produto', 'action' => 'edit', $ecmAlternativeHost->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
