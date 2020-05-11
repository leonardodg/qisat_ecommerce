<?= $this->JqueryMask->getScript();?>
<?= $this->MutipleSelect->getScript();?>

<?php
$scripts = $this->JqueryMask->mask('#parcelas',['##', ['reverse' => true]]);
$scripts = $this->Jquery->domReady($scripts);

$attrPermissao = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'position' => '"top"',
    'multipleWidth' => '250');

$multiselect = $this->MutipleSelect->multipleSelect('#ecm-tipo-produto-ids',$attrPermissao);

$scriptsCheckTipos = 'if($("#todos-tipos").is(":checked"))
                        $("#ecm-tipo-produto-ids").multipleSelect("disable");
                      else
                        $("#ecm-tipo-produto-ids").multipleSelect("enable")';
$checkTipos = $this->Jquery->get('#todos-tipos')
    ->event('change', $scriptsCheckTipos);
$checkTipos .= $this->Jquery->get('window')
    ->event('load', $scriptsCheckTipos);

$scripts .= $this->Jquery->domReady($multiselect.$checkTipos);
echo $this->Html->scriptBlock($scripts);

?>

<div class="ecmFormaPagamento col-md-12">
    <?= $this->Form->create($ecmFormaPagamento) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];

            echo $this->Form->input('nome');
            echo $this->Form->input('dataname', [
                'label' => __('Nome atribuido para realizar conexão com os mecanimos de Pagamento')
            ]);
            echo $this->Form->input('descricao', ['label' => __('Descrição')]);
            echo $this->Form->input('habilitado', ['options' => $optionsHabilitado]);
            echo $this->Form->input('parcelas', ['type' => 'text']);
            echo $this->Form->input('tipo', ['options' =>[
                'boleto' => __('Boleto'),
                'cartao' => __('Cartão'),
                'cartao_recorrencia' => __('Cartão Recorrência'),
                'online' => __('Pagamento Online')
            ]]);
            echo $this->Form->input('controller');

            $atributos = ['label' => __('Todos tipos'), 'type' => __('checkbox')];

            if(count($ecmFormaPagamento->get('ecm_tipo_produto')) == 0)
                $atributos['checked'] = 'checked';

            echo $this->Form->input('todos_tipos', $atributos);
            echo $this->Form->input('ecm_tipo_produto._ids', ['label'=>__('Tipo de produto'), 'options' => $optionsTipoProduto]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
