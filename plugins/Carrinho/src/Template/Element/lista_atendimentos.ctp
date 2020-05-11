<div class="related">
    <?php if($listaAtendimentosAltoQi && count($listaAtendimentosAltoQi) > 0):?>
        <h4><?= __('Lista de Produtos AltoQi') ?></h4>
        <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveUserListCalls">
            <thead>
            <tr>
                <th><?= __('Categoria') ?></th>
                <th><?= __('Data') ?></th>
                <th><?= __('Descrição') ?></th>
                <th><?= __('Status') ?></th>
            </tr>
        </thead>
            <?php foreach ($listaAtendimentosAltoQi as $call): ?>
            <tr>
                <td><?= h($call['id_categoria_contato']) ?></td>
                <td><?= h($call['data_contato']) ?></td>
                <td><?= h($call['situacao']) ?></td>
                <td><?= h($call['ligacao_produtiva']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
     <?php else: ?>
        <h5><?= __('Nenhum Atendimento AltoQi') ?></h5>
    <?php endif; ?>
</div>