<div class="ecmPublicidade col-md-12">
    <h3><?= __('Publicidade') ?></h3>
    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome', ['label' => 'Nome da publicidade']);
            echo $this->Form->input('tipo', ['options' => $tipo, 'label' => 'Tipo de publicidade']);
            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('ecm_produto_id', ['options' => $ecmProduto, 'label' => 'Selecione um produto']);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= $this->Paginator->sort('tipo') ?></th>
                <th><?= $this->Paginator->sort('habilitado') ?></th>
                <th><?= $this->Paginator->sort('arquivos') ?></th>
                <th><?= $this->Paginator->sort('convites') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmPublicidade as $ecmPublicidade): ?>
            <tr>
                <td><?= $this->Number->format($ecmPublicidade->id) ?></td>
                <td><?= h($ecmPublicidade->nome) ?></td>
                <td><?= h($ecmPublicidade->tipo) ?></td>
                <td>
                    <?php if($ecmPublicidade->habilitado == 1): ?>
                        <div style="color:green">Sim
                    <?php else: ?>
                        <div style="color:red">Não
                    <?php endif; ?>
                    </div>
                </td>
                <td><?= $this->Html->image("arquivos.png", ['title' => 'Arquivos', 'style' => 'max-width:20px',
                        'url' => ['controller' => '', 'action' => 'arquivos', $ecmPublicidade->id]]) ?></td>
                <td id="message<?= $ecmPublicidade->id ?>">
                    <?php if($ecmPublicidade->tipo == 'Convite'): ?>
                        <?= $this->Html->image("message.gif", ['title' => 'Convites', 'style' => 'max-width:16px;cursor:pointer',
                            'onclick' => 'buscarEdicoes('.$ecmPublicidade->ecm_produto_id.', this, "'.
                                $dominioPublicidade . $ecmPublicidade->src . '/' . $ecmPublicidade->arquivo.'")']) ?>
                    <?php else: ?>
                        <?= $this->Html->image("message.gif", ['title' => 'Convites', 'style' => 'max-width:16px',
                            'url' => $dominioPublicidade . $ecmPublicidade->src . "/" . $ecmPublicidade->arquivo])
                        ?>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ecmPublicidade->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmPublicidade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmPublicidade->id)]) ?>
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
<script>
    $(function() {
        $("td[id*='message']").find('a').attr('target', '_blank');
    });
    function buscarEdicoes(id, img, view){
        $.post("", {"id": id}, function(data) {
            if(data.length>0){
                var tr = '<tr id="tr-aberta" class="r0"><td colspan="7"><div id="edicoes-curso">' +
                    '<h2 class="main">Edições</h2><div class="convite-edicoes generalbox box">' +
                    '<div class="cancel-convites">' +
                    '<img onclick="fecharEdicoes()" class="icon" src="img/delete.gif" style="cursor:pointer;" title="Fechar">' +
                    '</div><table class="table_convites"><tbody><tr style="text-align:center">' +
                    '<th>Local</th><th>Data</th></tr>';
                for(x in data){
                    var idTurma = data[x]['id'];
                    var dataTurma = data[x]['ecm_curso_presencial_data'];
                    var cidade = dataTurma[0]['ecm_curso_presencial_local']['mdl_cidade'];
                    tr += '<tr style="text-align:center"><td><a target="_blank" href="' + view + '?turma=' + idTurma + '">' +
                        cidade['nome'] + "/" + cidade['mdl_estado']['uf'] + '</a></td><td>' +
                            '<a target="_blank" href="' + view + '?turma=' + idTurma + '">';
                    for(y in dataTurma){
                        tr += dataTurma[y]['datainicio'] + '<br/>';
                    }
                }
                tr += '</a></td></tr>';
                $(img).parent().parent().after(tr);
            } else {
                window.open(view, '_blank');
            }
        }, "json");
    }
    function fecharEdicoes(){
        $('#tr-aberta').remove();
    }
</script>