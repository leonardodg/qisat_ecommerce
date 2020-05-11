<?= $this->MutipleSelect->getScript();?>
<?= $this->JqueryUI->getScript();?>
<?= $this->JqueryMask->getScript();?>

<?php
$atributos = array (
    'changeMonth' => true,
    'changeYear' => true,
    'numberOfMonths' => 2,
    'minDate' => 0,
    'showButtonPanel' => true
);

$atributos ['onClose'] = 'function( selectedDate ) {
							 $( "#datafim" ).datepicker( "option", "minDate", selectedDate );
						 }';

$atributosDate ['#datainicio'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
							$( "#datainicio" ).datepicker( "option", "maxDate", selectedDate );
						 }';

$atributosDate ['#datafim'] = $atributos;

$datePicker = $this->JqueryUI->datePicker ( array (
    '#datainicio',
    '#datafim'
), $atributosDate );

$attrMultiSelect = array(
    'width' => '460',
    'filter' => 'true',
    'multiple' => 'true',
    'position' => '"top"',
    'multipleWidth' => '105');



$scripts = $this->JqueryMask->mask(['#datainicio','#datafim'],['00/00/0000']);
$scripts .= $this->JqueryMask->mask('#descontovalor',['#.##0,00', ['reverse' => true]]);
$scripts .= $this->JqueryMask->mask('#descontoporcentagem',['00,00']);
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-produto-ids',$attrMultiSelect);

$attrMultiSelect['multipleWidth'] = '250';
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-tipo-produto-ids',$attrMultiSelect);
$scripts .= $this->MutipleSelect->multipleSelect('#ecm-alternative-host-ids',$attrMultiSelect);
$scripts .= $this->MutipleSelect->multipleSelect('#mdl-user-ids',$attrMultiSelect);

$scripts .= $datePicker;

$scriptDesabilitarCampo = 'if($(this).val().length > 0){
                               elemento.attr("disabled", "disabled");
                               elemento.val("");
                           }else{
                               elemento.removeAttr("disabled");
                           }';
$scripts .= $this->Jquery->get('#descontovalor')->event(
    'keyup',
    'var elemento = $("#descontoporcentagem");'.$scriptDesabilitarCampo
);

$scripts .= $this->Jquery->get('#descontoporcentagem')->event(
    'keyup',
    'var elemento = $("#descontovalor");'.$scriptDesabilitarCampo
);

$scriptTipoCupom = 'var produto = $("#ecm-produto-ids").parent();
                    var tipo = $("#ecm-tipo-produto-ids").parent();
                    if($("#tipo").val() == "produto"){
                        produto.fadeIn();
                        tipo.fadeOut();
                    }else{
                        tipo.fadeIn();
                        produto.fadeOut();
                    }';

$scripts .= $this->Jquery->get('#tipo')->event('change',$scriptTipoCupom);
$scripts .= $this->Jquery->get('window')->event('load',$scriptTipoCupom);

$scriptTipoAquisicao = 'var chave = $("#chave").parent();
                    var email = $("#email").parent();
                    email.fadeOut();
                    if($("#tipo-aquisicao").val() > 1){
                        chave.fadeIn();
                        if($("#tipo-aquisicao").val() == 2)
                            email.fadeIn();
                    }else{
                        chave.fadeOut();
                    }';

$scripts .= $this->Jquery->get('#tipo-aquisicao')->event('change',$scriptTipoAquisicao);
$scripts .= $this->Jquery->get('window')->event('load',$scriptTipoAquisicao);

$scriptUsuarios = 'if($("#tipo-aquisicao").val() == 0){
                       $("#mdl-user-ids").parent().parent().fadeIn();
                   }else{
                       $("#mdl-user-ids").parent().parent().fadeOut();
                   }';
$scripts .= $this->Jquery->get('#tipo-aquisicao')->event('change',$scriptUsuarios);
$scripts .= $this->Jquery->get('window')->event('load',$scriptUsuarios);

$scripts = $this->Jquery->domReady($scripts);
echo $this->Html->scriptBlock($scripts);
?>

<div class="ecmCupom col-md-12">
    <?= $this->Form->create($ecmCupom) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            $optionsHabilitado = ['true'=>__('Sim'),'false'=>__('Não')];
            $optionsTipo = ['produto'=>__('Produto'),'tipo'=>__('Tipo de Produto')];

            echo $this->Form->input('nome');
            echo $this->Form->input('datainicio', ['label' => __('Data de Inicio'),'type'=>'text']);
            echo $this->Form->input('datafim', ['label' => __('Data de Fim'),'type'=>'text']);
            echo $this->Form->input('descontovalor', ['label' => __('Desconto em Valor'),'type'=>'text']);
            echo $this->Form->input('descontoporcentagem', ['label' => __('Desconto em Porcentagem'),'type'=>'text']);
            echo $this->Form->input('descricao', ['label' => __('Descrição')]);
            echo $this->Form->input('habilitado', ['options'=>$optionsHabilitado]);
            echo $this->Form->input('numutilizacoes', ['label' => __('Número de utilizações para esse cupom')]);
            echo $this->Form->input('numutilizacoesuser', ['label' => __('Número de utilizações por usuario'), 'default' => 2]);
            echo $this->Form->input('arredondamento', ['options'=>$optionsHabilitado]);
            echo $this->Form->input('descontosobretabela', ['label' => __('Desconto sobre o valor de tabela'), 'options'=>$optionsHabilitado]);

            echo $this->Form->input('ecm_alternative_host.ids', ['label' => __('Empresa'), 'options' => $ecmAlternativeHost]);

            echo $this->Form->input('tipo', ['label'=>__('Cupom referente à'), 'options' => $optionsTipo]);
            echo $this->Form->input('ecm_produto._ids', ['label' => __('Produto'), 'options' => $ecmProduto]);
            echo $this->Form->input('ecm_tipo_produto._ids', ['label' => __('Tipo de Produto'), 'options' => $ecmTipoProduto]);

            echo $this->Form->input('tipo_aquisicao', ['label' => __('Tipos de aquisições'),
                'options' => \Cupom\Model\Entity\EcmCupom::TIPOS_AQUISICOES]);
            echo $this->Form->input('chave', ['label' => __('Chave - Codigo de acesso ao cupom digitado pelo cliente')]);
            echo $this->Form->input('email', ['label' => __('Emails - Separados por vírgulas. Exemplo:(aluno1@qisat.com.br,aluno2@qisat.com.br)'),
                'type' => 'email', 'multiple']);

            echo $this->Html->tag('fieldset');

            echo $this->Html->tag('legend', __('Selecionar Usuário'));

            echo $this->Html->tag('div',__('Atenção: para selecionar os usuários faça uma busca pelo nome do usuário e
                selecione no campo "Selecione o Usuário", abaixo será listado os campos selecionados em "Usuários Selecionados"'));

            echo $this->Html->tag('br /', null);

            echo $this->Form->input('buscar_usuario', ['label' => __('Buscar Usuário')]);

            $options = [];

            if(isset($usuariosSelecionados)){
                $options = $usuariosSelecionados;
            }

            echo $this->Form->input('select_usuario', ['label'=>__('Selecione o Usuário'), 'options' => []]);

            echo $this->Form->input('mdl_user._ids', ['label'=>__('Usuários Selecionados'), 'options' => $options]);
            echo $this->Html->tag('/fieldset');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->scriptBlock($this->UserScript->selectUserAjax('#buscar-usuario', '#select-usuario', '#mdl-user-ids')); ?>