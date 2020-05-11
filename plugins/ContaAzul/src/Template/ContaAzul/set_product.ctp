<?= $this->MutipleSelect->getScript();?>

<?php
$attrPermissao = array(
    'width' => '250',
    'filter' => 'true',
    'multiple' => 'true',
    'multipleWidth' => '250'
);

$scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#produtos-ids',$attrPermissao));
echo $this->Html->scriptBlock($scripts);
?>

<script>
    var selects = <?= $setSelects ?>;

    $(document).ready(function () {
        var a = $('#produtos-ids').multipleSelect('setSelects', selects);
    });
</script>


<div class="row">
    <div class="col-md-12">
        <h3><?= __('Adicionar Produto - ContaAzul') ?></h3>

        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Dados') ?></legend>

            <div class="row">
                <div class="col-md-4">
                    <?= $this->Form->input('name', [ 'label'=> __('Nome'), 'value' => $product['name'], 'required' => true ]) ?>
                </div>

                <div class="col-md-1">
                    <?= $this->Form->input('value', [ 'label'=> __('Valor'), 'type' => 'number', 'placeholder'=> "0.00", 'step' => 'any', 'pattern' => '^\d+(?:\.\d{1,2})?$', 'required' => true, 'value' => $product['value']]) ?>
                </div>

                <div class="col-md-1">
                    <?= $this->Form->input('cost', [ 'label'=> __('Custo'), 'type' => 'number', 'placeholder'=> "0.00", 'step' => 'any', 'value' => $product['cost'], 'required' => true ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $this->Form->input('category_id', ['label'=> __('Categoria do Produto'), 'options' => $cats, 'empty' => __('Selecione a categoria'), 'value' => $product['category']['id']  ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $this->Form->input('produtos._ids', ['label'=> __('Relacão com Produto'), 'options' => $produtos]) ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-2">
                    <?= $this->Form->input('code', [ 'label'=> __('Código'), 'value' => $product['code']]) ?>
                </div>

                <div class="col-md-2">
                    <?= $this->Form->input('barcode', [ 'label'=> __('Código da Barra'), 'value' => $product['barcode']]) ?>
                </div>

                <div class="col-md-2">
                    <?= $this->Form->input('ncm_code', [ 'label'=> __('NCM do produto'), 'value' => $product['ncm_code']]) ?>
                </div>

                <div class="col-md-2">
                    <?= $this->Form->input('cest_code', [ 'label'=> __('CEST do produto'), 'value' => $product['cest_code']]) ?>
                </div>

                <div class="col-md-1">
                    <?= $this->Form->input('available_stock', [ 'label'=> __('Estoque'), 'type' => 'number', 'placeholder'=> "0", 'step' => '1', 'value' => $product['available_stock']]) ?>
                </div>

                <div class="col-md-1">
                    <?= $this->Form->input('net_weight', [ 'label'=> __('P. Líquido'), 'type' => 'number', 'placeholder'=> "0", 'step' => '1', 'value' => $product['net_weight']]) ?>
                </div>

                <div class="col-md-1">
                    <?= $this->Form->input('gross_weight', [ 'label'=> __('P. Bruto'), 'type' => 'number', 'placeholder'=> "0", 'step' => '1', 'value' => $product['gross_weight']]) ?>
                </div>

            </div>

            <div class="row right">
                <div class="col-md-12">
                    <div class="input-group"> 
                        <span class="input-group-btn">
                                <?= $this->Html->link('Cancelar', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'listProducts']),  [ 'class' => 'button' ]) ?>
                        </span>
                        &nbsp
                        <span class="input-group-btn">
                            <?= $this->Form->button(__('Submit')) ?>
                        </span>
                    </div> 
               </div> 
          </div>  

        </fieldset>

        <?= $this->Form->end() ?>
    </div>
</div>
