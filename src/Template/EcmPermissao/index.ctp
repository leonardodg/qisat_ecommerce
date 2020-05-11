<div class="ecmPermissao col-md-12">
    <h3><?= __('Permissões') ?></h3>

    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('plugin', ['options' => $plugins, 'onchange' => 'buscarControllerAction(this)']);
            echo $this->Form->input('controller', ['options' => $controllers, 'onchange' => 'buscarControllerAction(this)']);
            echo $this->Form->input('action', ['options' => $actions]);
            echo $this->Form->input('restricao', ['label' => 'Restrições', 'options' => $restricao]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>
    </div>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('action') ?></th>
                <th><?= $this->Paginator->sort('controller') ?></th>
                <th><?= $this->Paginator->sort('descricao',__('Descrição')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmPermissao as $ecmPermissao): ?>
            <tr>
                <td><?= $this->Number->format($ecmPermissao->id) ?></td>
                <td><?= h($ecmPermissao->action) ?></td>
                <td><?= h($ecmPermissao->controller) ?></td>
                <td><?= h($ecmPermissao->descricao) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller'=>'permissao', 'action' => 'view', $ecmPermissao->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller'=>'permissao', 'action' => 'edit', $ecmPermissao->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller'=>'permissao', 'action' => 'delete', $ecmPermissao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmPermissao->id)]) ?>
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
            <p><?= $this->Paginator->counter() ?></p>
        </ul>
    </div>
</div>
<script>
    $(function() {
        var plugin = $("#plugin");
        var controller = $("#controller");
        var action = $("#action");
        if(plugin.val() == 0){
            controller.parent().hide();
            action.parent().hide();
        } else if(controller.val() == 0) {
            action.parent().hide();
        }
    });
    function buscarControllerAction(inst){
        var select = $(inst);
        var isPlugin = select.attr('id') == 'plugin';
        var postData = {};
        if(isPlugin){
            postData.plugin = select.val();
            if(postData.plugin == "1")
                postData.plugin = "";
        } else {
            postData.controller = select.val();
        }
        $.post("", postData, function(data) {
            var selectFilho = $("#action");
            selectFilho.find('option').remove().end();
            selectFilho.parent().hide();
            if(isPlugin){
                selectFilho = $("#controller");
                selectFilho.find('option').remove().end();
                selectFilho.parent().hide();
            }
            $.each(data, function(key, value) {
                selectFilho.append($("<option></option>").attr("value", key).text(value));
            });
            if(select.val() != "0")
                selectFilho.parent().show();
        }, "json");
    }
</script>
