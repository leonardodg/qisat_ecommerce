<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/clipboard.min.js') ?>

<div class="ecmCarrinho medium-12 large-12 columns content">
    <?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='/mdl-user/listar-usuario'"]) ?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>
    <h3><?= __('Entidades') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->input('ecm_alternative_host_id', ['options' => $ecmAlternativeHost,
            'label' => 'Selecione a Entidade', 'onchange' => 'verificarEntidade()']) ?>
        <?= $this->Form->button('Novo Carrinho', ['type' => 'submit', 'id' => 'selecionar' , 'class' => 'right']) ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <?= $this->element('lista_carrinho',['ecmCarrinho'=>$listaCarrinho,
        'titulo' => __('Carrinhos'),'situacao' => true
    ]);?>

    <?= $this->element('lista_produtos',['listaProdutosAltoQi'=>$listaProdutosAltoQi,
        'titulo' => __('Produtos'),'situacao' => true
    ]);?>

    <?= $this->element('lista_atendimentos',['listaAtendimentosAltoQi'=>$listaAtendimentosAltoQi,
        'titulo' => __('Atendimentos'),'situacao' => true
    ]);?>

    <?= $this->element('lista_cursos',['listaCursos'=>$listaCursos,
        'titulo' => __('Cursos'),'situacao' => true
    ]);?>
</div>
<script>
    function verificarEntidade(){
        var id = $('#ecm-alternative-host-id').val();
        if(id > 1){
            $.post("", {"entidade": id}, function(data) {
                $('#selecionar').prop('disabled', !data.sucesso);
                if(!data.sucesso)
                    setTimeout(function(){ bootbox.alert(data.mensagem); }, 400);
            }, "json");
        } else {
            $('#selecionar').prop('disabled', false);
        }
    }
</script>