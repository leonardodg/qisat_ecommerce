<div class="ecmTipoPagamento col-md-12">
    <?= $this->Form->create($ecmTipoPagamento) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];

            echo $this->Form->input('nome');
            echo $this->Form->input('dataname', [
                'label' => __('Nome do atribuido para realizar conexão com os mecanimos de Pagamento')
            ]);
            echo $this->Form->input('descricao', ['label' => __('Descrição')]);
            echo $this->Form->input('habilitado', ['options' => $optionsHabilitado]);
            echo $this->Form->input('ecm_forma_pagamento_id', [
                'label' => __('Forma de Pagamento'),
                'options' => $ecmFormaPagamento
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
