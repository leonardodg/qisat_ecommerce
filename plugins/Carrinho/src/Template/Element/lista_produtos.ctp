<div class="related">
    <?php if($listaProdutosAltoQi && count($listaProdutosAltoQi) > 0):?>
        <h4><?= __('Lista de Produtos AltoQi') ?></h4>
        <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveUserProdutosList">
            <thead>
            <tr>
                <th><?= __('Pedido') ?></th>
                <th><?= __('Nome Curso') ?></th>
                <th><?= __('Aplicação') ?></th>
                <th><?= __('Modulos') ?></th>
            </tr>
        </thead>
            <?php foreach ($listaProdutosAltoQi as $produto): ?>
            <tr>
                <td><?= h($produto['pedido_id']) ?></td>
                <td><?= ((isset($produto['sigla'])) ? h($produto['sigla']) : '').' - '. h($produto['nome']) ?></td>
                <td><?= h($produto['aplicacao']) ?></td>
                <td>
                    <?php foreach ($produto['modulos'] as $modulo): ?>
                       <?= h($modulo['nome']) ?> <br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
     <?php else: ?>
        <h5><?= __('Nenhum Produtos AltoQi') ?></h5>
    <?php endif; ?>
</div>