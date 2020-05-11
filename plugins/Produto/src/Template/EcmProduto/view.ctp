<div class="ecmProduto col-md-12">
    <h3><?= h($ecmProduto->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmProduto->nome) ?></td>
        </tr>
        <tr>
            <th><?= __('Sigla') ?></th>
            <td><?= h($ecmProduto->sigla) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmProduto->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Preco') ?></th>
            <td><?= $this->Number->format($ecmProduto->preco) ?></td>
        </tr>
        <tr>
            <th><?= __('Parcela') ?></th>
            <td><?= $this->Number->format($ecmProduto->parcela) ?></td>
        </tr>
        <tr>
            <th><?= __('Idtop') ?></th>
            <td><?= $this->Number->format($ecmProduto->idtop) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Refcurso') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->refcurso)); ?>
    </div>
    <div class="row">
        <h4><?= __('Moeda') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->moeda)); ?>
    </div>
    <div class="row">
        <h4><?= __('Habilitado') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->habilitado)); ?>
    </div>
    <div class="row">
        <h4><?= __('Visivel') ?></h4>
        <?= $this->Text->autoParagraph(h($ecmProduto->visivel)); ?>
    </div>
    <?php if(isset($ecmProduto->ecm_produto_info)): ?>
        <div class="row">
            <h4><?= __('Descricao') ?></h4>
            <?= $this->element('view',['ecmProdutoInfo' => $ecmProduto->ecm_produto_info]);?>
        </div>
    <?php endif; ?>
    <div class="related">
        <h4><?= __('Related Ecm Tipo Produto') ?></h4>
        <?php if (!empty($ecmProduto->ecm_tipo_produto)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Tipo Produto') ?></th>
                <th><?= __('Ordem') ?></th>
                <th><?= __('Categoria') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmProduto->ecm_tipo_produto as $ecmTipoproduto): ?>
            <tr>
                <td><?= h($ecmTipoproduto->id) ?></td>
                <td><?= h($ecmTipoproduto->nome) ?></td>
                <?php if($ecmTipoproduto->ecm_tipo_produto_id==0): ?>
                    <td>&nbsp;</td>
                <?php else: ?>
                    <td><?= h($ecmTipoProdutoAll[$ecmTipoproduto->ecm_tipo_produto_id]) ?></td>
                <?php endif; ?>
                <td><?= h($ecmTipoproduto->ordem) ?></td>
                <td><?= h($ecmTipoproduto->categoria) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'tipo-produto', 'action' => 'view', $ecmTipoproduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'tipo-produto', 'action' => 'edit', $ecmTipoproduto->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'tipo-produto', 'action' => 'delete', $ecmTipoproduto->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmTipoproduto->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Mdl Course') ?></h4>
        <?php if (!empty($ecmProduto->mdl_course)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Category') ?></th>
                <th><?= __('Fullname') ?></th>
                <th><?= __('Shortname') ?></th>
            </tr>
            <?php foreach ($ecmProduto->mdl_course as $mdlCourse): ?>
            <tr>
                <td><?= h($mdlCourse->id) ?></td>
                <td><?= h($mdlCourse->category) ?></td>
                <td><?= h($mdlCourse->fullname) ?></td>
                <td><?= h($mdlCourse->shortname) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <?php if (!empty($ecmProduto->mdl_fase)): ?>
        <div class="related">
            <h4><?= __('Related Mdl Fase') ?></h4>
            <table class="vertical-table">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($ecmProduto->mdl_fase->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Descrição') ?></th>
                    <td><?= h($ecmProduto->mdl_fase->descricao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor da carga horaria') ?></th>
                    <td><?= h($ecmProduto->mdl_fase->valor_carga_horaria) ?></td>
                </tr>
                <tr>
                    <th><?= __('Periodo de inscrição') ?></th>
                    <td><?= $this->Number->format($ecmProduto->mdl_fase->enrolperiod) ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
    <?php if (!empty($ecmProduto->ecm_produto_ecm_produto)): ?>
        <div class="related">
            <h4><?= __('Related Ecm Produto Related') ?></h4>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Nome') ?></th>
                    <th><?= __('Sigla') ?></th>
                    <th><?= __('Habilitado') ?></th>
                    <th><?= __('Visivel') ?></th>
                </tr>
                <?php foreach ($ecmProduto->ecm_produto_ecm_produto as $ecmProdutoEcmProduto): ?>
                    <tr>
                        <td><?= h($ecmProdutoEcmProduto->ecm_produto->id) ?></td>
                        <td><?= h($ecmProdutoEcmProduto->ecm_produto->nome) ?></td>
                        <td><?= h($ecmProdutoEcmProduto->ecm_produto->sigla) ?></td>
                        <td><?= h($ecmProdutoEcmProduto->ecm_produto->habilitado) ?></td>
                        <td><?= h($ecmProdutoEcmProduto->ecm_produto->visivel) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>
