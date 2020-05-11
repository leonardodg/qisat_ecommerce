<div class="ecmInstrutorArtigo col-md-12">
    <h3><?= h($ecmInstrutorArtigo->titulo) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmInstrutorArtigo->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Instrutor') ?></th>
            <td><?= $this->Html->link($ecmInstrutorArtigo->ecm_instrutor->mdl_user->firstname.' '.$ecmInstrutorArtigo->ecm_instrutor->mdl_user->lastname,
                    ['controller' => 'EcmInstrutor', 'action' => 'view', $ecmInstrutorArtigo->ecm_instrutor->id])?></td>
        </tr>
        <tr>
            <th><?= __('Imagem') ?></th>
            <td><?= $this->Html->image('/upload/'.$ecmInstrutorArtigo->ecm_instrutor->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
        </tr>
        <tr>
            <th><?= __('Título') ?></th>
            <td><?= h($ecmInstrutorArtigo->titulo) ?></td>
        </tr>
        <tr>
            <th><?= __('Link') ?></th>
            <td><?= $this->Html->link($ecmInstrutorArtigo->link, $ecmInstrutorArtigo->link, ['target'=>'blank']) ?></td>
        </tr>
    </table>
</div>
