<?php
$imagem = $this->Html->image('instrutor.png', ['style' => 'max-height:52px;']);

if(!is_null($ecmInstrutor->get('ecm_imagem'))){
    $imagem = $this->Html->image('/upload/'.$ecmInstrutor->ecm_imagem->src, ['style' => 'max-height:52px;']);
}
?>
<div class="ecmInstrutor col-md-12">
    <h3><?= $ecmInstrutor->mdl_user->firstname.' '.$ecmInstrutor->mdl_user->lastname ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?=  $ecmInstrutor->id ?></td>
        </tr>
        <tr>
            <th><?= __('Imagem') ?></th>
            <td><?= $imagem ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Descrição') ?></h4>
        <?= $this->Text->autoParagraph($ecmInstrutor->descricao); ?>
    </div>
    <div class="related">
        <h4><?= __('Artigos Relacionados') ?></h4>
        <?php if (!empty($ecmInstrutor->ecm_instrutor_artigo)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Titulo') ?></th>
                <th><?= __('Link') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmInstrutor->ecm_instrutor_artigo as $ecmInstrutorArtigo): ?>
            <tr>
                <td><?= h($ecmInstrutorArtigo->id) ?></td>
                <td><?= h($ecmInstrutorArtigo->titulo) ?></td>
                <td><?= $this->Html->link($ecmInstrutorArtigo->link, $ecmInstrutorArtigo->link, ['target' => 'blank']) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'artigo', 'action' => 'view', $ecmInstrutorArtigo->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'artigo', 'action' => 'edit', $ecmInstrutorArtigo->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Redes Sociais') ?></h4>
        <?php if (!empty($ecmInstrutor->ecm_instrutor_rede_social)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Rede Social') ?></th>
                <th><?= __('Link') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmInstrutor->ecm_instrutor_rede_social as $ecmInstrutorRedeSocial): ?>
            <tr>
                <td><?= $this->Html->image('/upload/'.$ecmInstrutorRedeSocial->ecm_rede_social->ecm_imagem->src, ['style' => 'max-height:52px;']); ?></td>
                <td><?= $this->Html->link($ecmInstrutorRedeSocial->link, $ecmInstrutorRedeSocial->link, ['target' => 'blank']) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'rede-social', 'action' => 'view', $ecmInstrutorRedeSocial->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'rede-social', 'action' => 'edit', $ecmInstrutorRedeSocial->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Produtos Relacionados') ?></h4>
        <?php if (!empty($ecmInstrutor->ecm_produto)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Nome') ?></th>
                <th><?= __('Sigla') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ecmInstrutor->ecm_produto as $ecmProduto): ?>
            <tr>
                <td><?= h($ecmProduto->id) ?></td>
                <td><?= h($ecmProduto->nome) ?></td>
                <td><?= h($ecmProduto->sigla) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['plugin' => false, 'controller' => 'produto', 'action' => 'view', $ecmProduto->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['plugin' => false, 'controller' => 'produto', 'action' => 'edit', $ecmProduto->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
