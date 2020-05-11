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
        <h3><?= __('Adicionar Serviço - ContaAzul') ?></h3>

        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Dados') ?></legend>

            <div class="row">
                <div class="col-md-8">
                    <?= $this->Form->input('name', [ 'label'=> __('Nome'), 'value' => $servico['name'], 'required' => true ]) ?>
                </div>

                <div class="col-md-2">
                    <?= $this->Form->input('value', [ 'label'=> __('Valor'), 'type' => 'number', 'placeholder'=> "0.00", 'step' => 'any', 'pattern' => '^\d+(?:\.\d{1,2})?$', 'value' => $servico['value'], 'required' => true ]) ?>
                </div>

                <div class="col-md-2">
                    <?= $this->Form->input('cost', [ 'label'=> __('Custo'), 'type' => 'number', 'placeholder'=> "0.00", 'step' => 'any', 'value' => $servico['cost'], 'required' => true ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $this->Form->input('curso',['label'=> __('Relacão com Cursos'), 'options' => $cursos, 'empty' => __('Selecione o Curso'), 'value' => $curso['id'] ]) ?>
                </div>

                <div class="col-md-4">
                    <?= $this->Form->input('produtos._ids', ['label'=> __('Relacão com Produto AltoQi'), 'options' => $produtos]) ?>
                </div>
            </div>

            <div class="row right">
                <div class="col-md-12">
                    <div class="input-group"> 
                        <span class="input-group-btn">
                                <?= $this->Html->link('Cancelar', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'listServices']),  [ 'class' => 'button' ]) ?>
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
