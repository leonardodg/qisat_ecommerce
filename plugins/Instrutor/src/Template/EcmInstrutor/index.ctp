<div class="ecmInstrutor col-md-12">

    <?= $this->Form->create($instrutor, ['type'=>'get','url' => ['controller' => 'EcmInstrutor', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?= $this->Form->input('nome', ['label' => __('Nome do Instrutor') , 'type'=>'text']) ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <h3><?= __('Lista de Instrutores') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('MdlUser.firstname', __('Nome')) ?></th>
                <th><?= __('Imagem') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmInstrutor as $ecmInstrutor): ?>

            <?php
                $imagem = $this->Html->image('instrutor.png', ['style' => 'max-height:52px;']);

                if(!is_null($ecmInstrutor->get('ecm_imagem'))){
                    $imagem = $this->Html->image('/upload/'.$ecmInstrutor->ecm_imagem->src, ['style' => 'max-height:52px;']);
                }
            ?>
            <tr>
                <td><?= $this->Number->format($ecmInstrutor->id) ?></td>
                <td><?= $ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname?></td>
                <td><?= $imagem ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Artigo'), ['controller' => 'artigo', 'action' => 'index', $ecmInstrutor->id]) ?>
                    <?= $this->Html->link(__('Redes Sociais'), ['controller' => 'rede-social', 'action' => 'index', $ecmInstrutor->id]) ?>
                    <?= $this->Html->link(__('View'), ['controller' => '', 'action' => 'view', $ecmInstrutor->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => '', 'action' => 'edit', $ecmInstrutor->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => '', 'action' => 'delete', $ecmInstrutor->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmInstrutor->id)]) ?>
                </td>
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
