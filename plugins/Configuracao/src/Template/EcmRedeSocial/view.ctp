<div class="ecmRedeSocial col-md-12">
    <h3><?= h($ecmRedeSocial->nome) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmRedeSocial->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Imagem') ?></th>
            <td><?= $this->Html->image('/upload/'.$ecmRedeSocial->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
        </tr>
    </table>
</div>
