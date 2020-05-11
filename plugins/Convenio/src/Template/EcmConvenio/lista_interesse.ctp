<div class="large-9 medium-8 columns content">
    <h3><?= __('Convênio').': '.$convenio->nome_instituicao ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('nome') ?></th>
            <th><?= $this->Paginator->sort('telefone') ?></th>
            <th><?= $this->Paginator->sort('email', __('E-mail')) ?></th>
            <th><?= $this->Paginator->sort('chave_altoqi', __('Chave AltoQi')) ?></th>
            <th><?= $this->Paginator->sort('data_registro', __('Data de Registro')) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ecmConvenioInteresse as $interesse): ?>
            <tr>
                <td><?= h($interesse->nome) ?></td>
                <td><?= h($interesse->telefone) ?></td>
                <td><?= h($interesse->email) ?></td>
                <td><?= h($interesse->chave_altoqi) ?></td>
                <td><?= h($interesse->data_registro) ?></td>
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