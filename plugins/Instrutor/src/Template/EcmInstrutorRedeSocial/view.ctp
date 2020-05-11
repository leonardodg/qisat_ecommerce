<div class="ecmInstrutorRedeSocial col-md-12">
    <h3><?= h($ecmInstrutorRedeSocial->ecm_rede_social->nome) ?></h3>
    <table class="vertical-table">
        <tr>
            <th></th>
            <td><?= $this->Html->image('/upload/'.$ecmInstrutorRedeSocial->ecm_instrutor->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
        </tr>
        <tr>
            <th><?= __('Instrutor') ?></th>
            <td><?= $this->Html->link($ecmInstrutorRedeSocial->ecm_instrutor->mdl_user->firstname.' '.$ecmInstrutorRedeSocial->ecm_instrutor->mdl_user->lastname,
                    ['controller' => 'EcmInstrutor', 'action' => 'view', $ecmInstrutorRedeSocial->ecm_instrutor->id])?></td>
        </tr>
        <tr>
            <th><?= __('Rede Social') ?></th>
            <td><?= $this->Html->image('/upload/'.$ecmInstrutorRedeSocial->ecm_rede_social->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
        </tr>
        <tr>
            <th><?= __('Link') ?></th>
            <td><?= $this->Html->link($ecmInstrutorRedeSocial->link, $ecmInstrutorRedeSocial->link, ['target' => 'blank']) ?></td>
        </tr>
    </table>
</div>
