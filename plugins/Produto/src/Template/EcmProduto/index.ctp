<div class="ecmProduto col-md-12">
    <h3><?= __('Ecm Produto') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('sigla', ['options' => $sigla]);
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('visivel', ['options' => $visivel]);
            echo $this->Form->input('idtop', ['label' => 'Id do curso no Top']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:6%"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th style="width:8%"><?= $this->Paginator->sort('preco') ?></th>
                <th style="width:13%"><?= $this->Paginator->sort('sigla') ?></th>
                <th style="width:8%"><?= $this->Paginator->sort('parcela') ?></th>
                <th style="width:7%"><?= $this->Paginator->sort('idtop') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmProduto as $ecmProduto): ?>
            <tr>
                <?php
                    $td = "<td style='";
                    $td2 = "</td>";
                    $habilitado = __('Desabilitar');
                    $visivel = __('Tornar invisível');
                    if($ecmProduto->visivel != "true" && $ecmProduto->habilitado != "excluido"){
                        $td .= "color: dimgray; ";
                        $visivel = __('Tornar visível');
                    }
                    if($ecmProduto->habilitado != "true"){
                        if($ecmProduto->habilitado == "excluido"){
                            $td .= "color: red;";
                        } else {
                            $td .= "text-decoration: line-through;";
                        }
                        $habilitado = __('Habilitar');
                    }
                    $td .= "'>";
                ?>
                <?= $td.$this->Number->format($ecmProduto->id).$td2 ?>
                <?= $td.h($ecmProduto->nome).$td2 ?>
                <?= $td.$this->Number->format($ecmProduto->preco).$td2 ?>
                <?= $td.h($ecmProduto->sigla).$td2 ?>
                <?= $td.$this->Number->format($ecmProduto->parcela).$td2 ?>
                <?= $td.$this->Number->format($ecmProduto->idtop).$td2 ?>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmProduto->id]) ?>,
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmProduto->id]) ?>,
                    <?= $this->Form->postLink(__('Delete'), ['controller' => '', 'action' => 'delete', $ecmProduto->id],
                        ['confirm' => __('Are you sure you want to delete # {0}?', $ecmProduto->id)]) ?>,
                    <?php
                        if(array_search($ecmProduto->id, $ecmProdutoEcmProduto) !== false)
                            echo $this->Html->link(__('Editar Fase da Trilha'), ['controller' => 'fase', 'action' => 'edit', $ecmProduto->id]).',';
                    ?>
                    <?= $this->Html->link(__('Editar Info'), ['controller' => 'produto-info', 'action' => 'edit', $ecmProduto->id]) ?>,
                    <?= $this->Html->link($habilitado, ['controller' => '', 'action' => 'alterarStatus', $ecmProduto->id, 'habilitado', $ecmProduto->habilitado]) ?>,
                    <?= $this->Html->link($visivel, ['controller' => '', 'action' => 'alterarStatus', $ecmProduto->id, 'visivel', $ecmProduto->visivel]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
