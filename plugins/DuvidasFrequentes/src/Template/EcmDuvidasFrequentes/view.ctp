<div class="ecmDuvidasFrequentes col-md-12">
    <h3><?= h($ecmDuvidasFrequente->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Titulo') ?></th>
            <td><?= h($ecmDuvidasFrequente->titulo) ?></td>
        </tr>
        <tr>
            <th><?= __('Url') ?></th>
            <td><?= h($ecmDuvidasFrequente->url) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmDuvidasFrequente->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Ordem') ?></th>
            <td><?= $this->Number->format($ecmDuvidasFrequente->ordem) ?></td>
        </tr>
        <tr>
            <th><?= __('Timemodified') ?></th>
            <td><?= h($ecmDuvidasFrequente->timemodified) ?></td>
        </tr>
    </table>
</div>
