<div class="related">
    <h4><?= __('Carrinhos em andamento com esse curso') ?></h4>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Data | Hora') ?></th>
            <th><?= __('Usuário') ?></th>
            <th><?= __('Total') ?></th>
            <th><?= __('Status') ?></th>
        </tr>
        <?php foreach ($ecmCarrinho as $carrinho):
            if($carrinho->status=="Em Aberto"): ?>
        <tr>
            <td><?= h($carrinho->edicao) ?></td>
            <td><?= h($carrinho->mdl_user->firstname.' '.$carrinho->mdl_user->lastname) ?></td>
            <td><?= $this->Number->precision($carrinho->calcularTotal(), 2) ?></td>
            <td><div style="color:blue"><?= h($carrinho->status) ?></div></td>
        </tr>
        <?php endif;
            endforeach; ?>
    </table>
</div>