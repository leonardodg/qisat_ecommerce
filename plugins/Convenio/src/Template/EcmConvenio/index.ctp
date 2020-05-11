<div class="ecmConvenio col-md-12">

    <?= $this->Form->create($convenio, ['type'=>'get','url' => ['controller' => 'EcmConvenio', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php

        echo $this->Form->input('nome', ['label' => __('Nome da Instituição') , 'type'=>'text']);
        echo $this->Form->input('situacao',['label'=> __('Situação'),
            'options' => [1 => __('Ativado'), 0 => __('Desativado')],
            'empty' => __('Todos')]);

        $attrEstado = ['options' => $listaEstado, 'empty'=> __('Selecione')];

        echo $this->Form->input('estado', $attrEstado);

        $attrCidade = ['options' => ['' => __('Selecione um Estado')] ,'disabled'];

        if(isset($listaCidade)){
            $attrCidade = ['options' => $listaCidade];
        }

        echo $this->Form->input('cidade', $attrCidade);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <h3><?= __('Ecm Convenio') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('nome_instituicao', __('Nome da Instituição')) ?></th>
                <th><?= $this->Paginator->sort('nome_responsavel', __('Nome do Responsável')) ?></th>
                <th><?= $this->Paginator->sort('EcmConvenioTipoInstituicao.descricao', __('Tipo de Instituição')) ?></th>
                <th><?= __('Situação') ?></th>
                <th><?= $this->Paginator->sort('data_registro', __('Data de Registro')) ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmConvenio as $ecmConvenio): ?>

            <?php
                $situacao = $this->Html->tag('span', __('Sem Contrato'), ['style'=>'color:red;']);

                if(!is_null($ecmConvenio->get('ecm_convenio_contrato_id'))){
                    if($ecmConvenio->get('ecm_convenio_contrato')->get('contrato_ativo') == 'true' &&
                       $ecmConvenio->get('ecm_convenio_contrato')->get('contrato_assinado') == 'true'){

                        $situacao = $this->Html->tag('span', __('Ativo'), ['style'=>'color:green;']);
                    }else{
                        $situacao = $this->Html->tag('span', __('Desativado'), ['style'=>'color:red;']);
                    }
                }
            ?>
            <tr>
                <td><?= $this->Number->format($ecmConvenio->id) ?></td>
                <td><?= h($ecmConvenio->nome_instituicao) ?></td>
                <td><?= h($ecmConvenio->nome_responsavel) ?></td>
                <td><?= $ecmConvenio->get('ecm_convenio_tipo_instituicao')->get('descricao') ?></td>
                <td><?= $situacao ?></td>
                <td><?= h($ecmConvenio->data_registro) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Lista de Interesses'), ['controller' => '', 'action' => 'lista-interesse', $ecmConvenio->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ecm_convenio', 'action' => 'edit', $ecmConvenio->id]) ?>
                    <?= $this->Html->link(__('Contrato'), ['controller'=>'convenio-contrato','action' => 'contrato', $ecmConvenio->id]) ?>
                    <?= $this->Html->link(__('Gerar Novo Contrato'), ['action' => 'gerar-contrato', $ecmConvenio->id], ['target' => '_blank']) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ecmConvenio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmConvenio->id)]) ?>
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
    <?= $this->Cidade->changeCidades('#estado', '#cidade')?>
</script>