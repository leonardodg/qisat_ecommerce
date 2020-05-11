<div class="ecmVendaPresencial col-md-12">
    <legend><?= __('E-mail de Confirmação de Curso Presencial') ?></legend>
    <h3>
        <?= $ecmCursoPresencialTurma->ecm_produto->nome ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf ?> -
        <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->nome ?>
    </h3>

    <?= $this->Form->create() ?>
    <legend><?= __('Lista de Participantes') ?></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('', 'Selecionar') ?></th>
            <th><?= $this->Paginator->sort('nome', 'Nome do Cliente') ?></th>
            <th><?= $this->Paginator->sort('email', 'E-mail') ?></th>
            <th><?= $this->Paginator->sort('status') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmCarrinho as $ecmCarrinho): ?>
            <tr>
                <td><?= $this->Form->checkbox('', ['value' => $ecmCarrinho->ecm_venda->id, 'name' => 'venda[]', 'hiddenField' => false]) ?></td>
                <td><?= h($ecmCarrinho->mdl_user->firstname) ?> <?= h($ecmCarrinho->mdl_user->lastname) ?></td>
                <td>
                    <?= h($ecmCarrinho->mdl_user->email) ?>
                    <?= $this->Form->hidden('email'.$ecmCarrinho->ecm_venda->id, ['value' => $ecmCarrinho->mdl_user->email]) ?>
                </td>
                <td>
                    <?php if(empty($ecmCarrinho->ecm_venda->ecm_curso_presencial_email_confirmacao)): ?>
                        <div style="color:black">Não
                    <?php else: ?>
                        <div style="color:green">Já
                    <?php endif; ?>
                    enviado<div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($ecmVendaPresencial as $ecmVendaPresencial): ?>
            <tr>
                <td><?= $this->Form->checkbox('', ['value' => $ecmVendaPresencial->id, 'name' => 'vendaPresencial[]', 'hiddenField' => false]) ?></td>
                <td><?= h($ecmVendaPresencial->nome) ?></td>
                <td>
                    <?= h($ecmVendaPresencial->email) ?>
                    <?= $this->Form->hidden('email'.$ecmVendaPresencial->id, ['value' => $ecmVendaPresencial->email]) ?>
                </td>
                <td>
                    <?php if(empty($ecmVendaPresencial->ecm_curso_presencial_email_confirmacao)): ?>
                        <div style="color:black">Não
                    <?php else: ?>
                        <div style="color:green">Já
                    <?php endif; ?>
                    enviado<div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->button('Enviar Selecionados') ?>
    <?= $this->Form->end() ?>

    <fieldset>
        <?= $this->Form->create('', ['url' => ['controller' => 'presencial', 'action' => 'add', $ecmCursoPresencialTurma->id], 'type' => 'GET']) ?>
            <?= $this->Form->button('Vender') ?>
        <?= $this->Form->end() ?>
        <?= $this->Form->create('', ['url' => ['controller' => 'presencial', 'action' => 'lista_vendas', $ecmCursoPresencialTurma->id]]) ?>
            <?= $this->Form->button('Ver Vendas') ?>
        <?= $this->Form->end() ?>
        <?= $this->Form->create('', ['url' => ['controller' => 'presencial', 'action' => 'index']]) ?>
            <?= $this->Form->button('Lista de Cursos Presenciais') ?>
        <?= $this->Form->end() ?>
    </fieldset>
</div>
