<div class="ecmCursoPresencialLocal col-md-12">
    <h3><?= h($ecmCursoPresencialLocal->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= h($ecmCursoPresencialLocal->nome) ?></td>
        </tr>
        <tr>
            <th><?= __('Cidade') ?></th>
            <td><?= $ecmCursoPresencialLocal->mdl_cidade->nome ?></td>
        </tr>
        <tr>
            <th><?= __('Estado') ?></th>
            <td><?= $ecmCursoPresencialLocal->mdl_cidade->mdl_estado->nome ?></td>
        </tr>
        <tr>
            <th><?= __('Endereco') ?></th>
            <td><?= h($ecmCursoPresencialLocal->endereco) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialLocal->id) ?></td>
        </tr>
    </table>
</div>
