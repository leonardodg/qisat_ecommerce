<div class="related">
    <h5><?= __('Comprando para o usuário') ?></h5>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="col-lg-1"><?= __('Chave') ?></th>
            <th class="col-lg-5"><?= __('Nome') ?></th>
            <th class="col-lg-3"><?= __('E-mail') ?></th>
            <th class="col-lg-2"><?= __('Usuário') ?></th>
            <th class="col-lg-1"><?= __('Ações') ?></th>
        </tr>
    </thead>
        <tr>
            <td>&nbsp <?= h($usuario->idnumber) ?></td>
            <td><?= h($usuario->firstname.' '.$usuario->lastname) ?></td>
            <td><?= h($usuario->email) ?></td>
            <td><?= h($usuario->username) ?></td>
            <td>
                <?= $this->Html->link('', ['plugin' => false, 'controller'=>'MdlUser', 'action' => 'edit', $usuario->id], ['title' => 'Editar', 'class' => 'glyphicon glyphicon-pencil']) ?>
            </td>
        </tr>
    </table>
</div>