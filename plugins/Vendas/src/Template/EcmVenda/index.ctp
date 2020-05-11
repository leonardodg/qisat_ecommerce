<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>

<?php 

    $scriptVerDetalhes = '  var parentTr = $(this).parent().parent();
                            var parentZero = $(this).parent();
                            var exibirHide = parentZero.find(".exibir-ocultas");
                            
                        $(".tr-detalhes").fadeOut("fast",function(){
                            $(this).remove();
                            exibirHide.hide();
                        });

                        if(!parentTr.hasClass("tr-detalhes-show")){
                            $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                            parentTr.addClass("tr-detalhes-show");
                            var texto = $(this).parent().find(".ver-detalhes-texto").html();
                            var colsTable = 8;

                            texto = "<td colspan=\'"+colsTable+"\'>"+texto+"</td>";
                            texto = "<tr class=\'tr-detalhes\' style=\'display:none;background: #DBD9D9;\'>"+texto+"</tr>";

                            parentTr.after(texto);
                            $(".tr-detalhes").fadeIn();
                            exibirHide.show();
                        }else{
                            $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                            
                        }';

    $scriptVerDetalhes = $this->Jquery->get('.ver-detalhes')->event('click',$scriptVerDetalhes);

    echo $this->Html->scriptBlock($this->Jquery->domReady($scriptVerDetalhes));

    $ndias = (cal_days_in_month(CAL_GREGORIAN, $datanow->format('n'), $datanow->format('Y')) -1);
?>

<div class="ecmVenda col-md-12">
    <h3><?= __('Vendas') ?></h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>

        <div class="row">
            <div class="col-md-2">
                <?= $this->Form->input('codigo', [
                                            'label' => [
                                                // 'text' => __('Código da Venda').' <b>('.__('Ao informar esse campo os outros filtros serão ignorados').')</b>',
                                                'escape' => false
                                            ]
                                        ]) ?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('pedido', [
                                                    'label' => [
                                                        // 'text' => __('Pedido Online').' <b>('.__('Ao informar esse campo os outros filtros serão ignorados').')</b>',
                                                        'escape' => false
                                                    ]
                                                ]) ?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('proposta', ['label' => 'Proposta']) ?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('idnumber', ['label' => 'ID ou Chave Usuário']) ?>
            </div>
                                                        
            <div class="col-md-2 col-md-offset-2">
                <?= $this->Form->input('mes',['label'=> __('Mês'), 'class'=>'reset', 'options' => $list_mes, 'empty' => __('Selecione o Mês'),'value' => $datanow->format('m/Y')]) ?>
            </div>
        </div>

        <div class="row">
             <div class="col-md-3">
                <?= $this->Form->input('status', ['options' => $ecmVendaStatus, 'label' => 'Selecione o Status Da Venda', 'default' => 2]) ?>
            </div>
             <div class="col-md-3">
                <?= $this->Form->input('tipo', ['options' => $ecmTipoPagamento]) ?>
            </div>
            <div class="col-md-3">
                <?= $this->Form->input('nome', ['options' => $ecmOperadoraPagamento, 'label' => 'Selecione a Operadora']) ?>
            </div>

            <div class="col-md-3">
                <?= $this->Form->input('fullname', ['options' => $ecmAlternativeHost, 'label' => 'Selecione uma Entidade'])?>
            </div>
        </div>

        <div class="row right">

            <div class="col-md-2">
                <label for="dbaqi"> 
                    <?= $this->Form->checkbox('dbaqi', ['default'=> true]); ?>
                    Exibir Pedidos TOP (DbaQI) 
                </label>
            </div> 

            <div class="col-md-10">
                <?= $this->Form->button('Buscar', ['type' => 'submit', 'class' => 'right']) ?>
            </div> 
        </div>   
    </fieldset>

    <div class="row">
        <div class="medium-12 large-12 columns">
            <?= $this->Form->button(__('Selecionar Todos'), ['class' => 'btnSelectAll', 'type' => 'button', 'title' => 'Selecionar Todos']) ?>
            <?= $this->Form->button(__('Exportar ContaAzul'), ['class' => 'btnContaAzul', 'type' => 'button']) ?>
        </div>    
    </div>

    <h3><?= __('Resumo Vendas') ?></h3>

<table cellpadding="0" cellspacing="0" class="table table-condensed table-responsive">
    <thead>
        <tr>
            <th  width="9%"> Dia </th>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++)
                    echo '<th> '.($i+1).'</th>';
            ?>
            
            <th width="5%"> Total </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td  width="9%" > 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="venda" ></span>
                <b>Vendas</b> 
            </td>

            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['venda']['data'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['venda']['total'][0] ?> </td>
        </tr>

        <tr class="venda active">
            <td  width="9%"> Transações </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['venda']['transacao'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['venda']['total'][1] ?> </td>
        </tr>

        <tr class="venda active">
            <td  width="9%"> Recorrências </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['venda']['recorrencia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['venda']['total'][2] ?> </td>
        </tr>

        <tr class="venda active">
            <td  width="9%"> Boletos </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['venda']['boleto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['venda']['total'][3] ?> </td>
        </tr>

        <tr class="venda active">
            <td  width="9%"> TOP </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['venda']['top'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['venda']['total'][4] ?> </td>
        </tr>

        <tr>
            <td  width="9%"> 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="finalizada" ></span>
                <b>Finalizada</b> 
            </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['finalizada']['data'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['finalizada']['total'][0] ?> </td>
        </tr>

        <tr class="finalizada active">
            <td  width="9%"> Transações </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['finalizada']['transacao'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['finalizada']['total'][1] ?> </td>
        </tr>

        <tr class="finalizada active">
            <td  width="9%"> Recorrências </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['finalizada']['recorrencia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['finalizada']['total'][2] ?> </td>
        </tr>

        <tr class="finalizada active">
            <td  width="9%"> Boletos </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['finalizada']['boleto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['finalizada']['total'][3] ?> </td>
        </tr>

        <tr class="finalizada active">
            <td  width="9%"> TOP </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['finalizada']['top'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['finalizada']['total'][4] ?> </td>
        </tr>

        <tr>
            <td  width="9%"> 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="andamento" ></span>
                <b>Em Aberta</b> 
            </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['andamento']['data'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['andamento']['total'][0] ?> </td>
        </tr>

        <tr class="andamento active">
            <td  width="9%"> Transações </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['andamento']['transacao'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['andamento']['total'][1] ?> </td>
        </tr>

        <tr class="andamento active">
            <td  width="9%"> Recorrências </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['andamento']['recorrencia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['andamento']['total'][2] ?> </td>
        </tr>

        <tr class="andamento active">
            <td  width="9%"> Boletos </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['andamento']['boleto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['andamento']['total'][3] ?> </td>
        </tr>

        <?php if ($tabelas['estornada']['total'][0] > 0): ?>
        <tr>
            <td  width="9%"> 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="estornada" ></span>
                <b>Estornada</b>
            </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['estornada']['data'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['estornada']['total'][0] ?> </td>
        </tr>

        <tr class="estornada active">
            <td  width="9%"> Transações </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['estornada']['transacao'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['estornada']['total'][1] ?> </td>
        </tr>

        <tr class="estornada active">
            <td  width="9%"> Recorrências </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['estornada']['recorrencia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['estornada']['total'][2] ?> </td>
        </tr>

        <tr class="estornada active">
            <td  width="9%"> Boletos </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['estornada']['boleto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['estornada']['total'][3] ?> </td>
        </tr>

        <?php endif; ?>

         <?php if ($tabelas['cancelada']['total'][0] > 0): ?>
        <tr>
            <td  width="9%"> 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="cancelada" ></span>
                <b>Cancelada</b> 
            </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cancelada']['data'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cancelada']['total'][0] ?> </td>
        </tr>


        <tr class="cancelada active">
            <td  width="9%"> Transações </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cancelada']['transacao'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cancelada']['total'][1] ?> </td>
        </tr>

        <tr class="cancelada active">
            <td  width="9%"> Recorrências </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cancelada']['recorrencia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cancelada']['total'][2] ?> </td>
        </tr>

        <tr class="cancelada active">
            <td  width="9%"> Boletos </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cancelada']['boleto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cancelada']['total'][3] ?> </td>
        </tr>

        <?php endif; ?>
    </tbody>
</table>

    <div class="row">
        <div class="medium-2 large-2 columns">
            <h4><?= __('Lista de Vendas') ?> </h4>
        </div>
    </div>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= __('Código da Venda').'<br />('.('Pedido Online').')'.'<br />('.('ContaAzul').')' ?></th>
                <th width='25%'> Usuário </th>
                <th> Pagamento </th>
                <th width='5%' > Origem </th>
                <th width='7%' > Data </th>
                <th width='10%'> Valor </th>
                <th> Status </th>
                <th class="actions"> Actions </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmVendas as $ecmVenda): ?>
            <tr>
            <?php

                    $carrinho = $ecmVenda->get('ecm_carrinho');
                    $list_servicos = [];
                    $list_produtos = [];
                    $tableP = '';
                    $tableS = '';
                    $valorItens = 0;

                    foreach($carrinho->ecm_carrinho_item as $item){
                        if($item->status == 'Adicionado'){
                            $item_produto = $item->get('ecm_produto');
                            $item_apps = $item->get('ecm_carrinho_item_ecm_produto_aplicacao');
                            $item_cursos = $item->get('ecm_carrinho_item_mdl_course');
                            $produtoAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 48; }); // produto AltoQi
                            $labAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 47; }); // produto AltoQi
                            
                            if( count($item_cursos) == 0  && count($produtoAltoQi) == 0 ) {
                                array_push($list_servicos, $item);
                            }else if(count($item_cursos) > 0){
                                foreach( $item->course_products as $item_curso ){
                                    array_push($list_servicos, $item_curso);
                                }
                            }
                            
                            if(count($item_apps) > 0 ){
                                foreach($item_apps as $item_app){
                                    $ecm_produto_ecm_app = $item_app->get('ecm_produto_ecm_aplicacao');
                                    $app_produto = $ecm_produto_ecm_app->get('ecm_produto');

                                    //if( !is_null($item_app->valor) && $item_app->valor > 0 ){
                                        $item_app->quantidade = $item->quantidade;
                                        array_push($list_produtos, $item_app);
                                    //}
                                }
                            }
                        }
                    }

                    if ( count($list_produtos) > 0){

                        $tableP = '<br> <table class="table table-striped">';
                        $tableP .= '<thead><tr>';
                            $tableP .= '<th width="50%" > Produto </th>';
                            $tableP .= '<th width="15%" > Sigla </th>';
                            $tableP .= '<th  width="15%" > Valor </th>';
                            $tableP .= '<th  width="20%" > Codigo_TW </th>';
                        $tableP .= '</tr></thead>';

                        foreach ($list_produtos as $item) {

                            $valorItens += ($item->quantidade * $item->valor);

                            $app = $item->get('ecm_produto_ecm_aplicacao');
                            $aplicacao = $app->get('ecm_produto_aplicacao');
                            $produto = $app->get('ecm_produto');
                            $tableP .= '<tr>';
                                if(is_null($aplicacao) || (!is_null($item->valor) && $item->valor > 0))
                                    $tableP .= '<td> '.h($produto->nome).'</td>';
                                else
                                    $tableP .= '<td> '.h($aplicacao->descricao).'</td>';

                                $tableP .= '<td> '.h($produto->sigla).' </td>';
                                $tableP .= '<td> '.$item->quantidade.' x '.$this->Number->format($item->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' </td>';
                                $tableP .= '<td>' .$app->conta_azul_produto.' </td>';
                            $tableP .= '<tr>';
                        }

                        $tableP .= '</table>';
                    }

                    if( count($list_servicos) > 0){
                        $tableS = '<br> <table class="table table-striped">';
                        $tableS .= '<thead><tr>';
                            $tableS .= '<th width="50%" > Curso </th>';
                            $tableS .= '<th width="15%" > Sigla </th>';
                            $tableS .= '<th  width="15%" > Valor </th>';
                            $tableS .= '<th  width="20%" > ContaAzul </th>';
                        $tableS .= '</tr></thead>';

                        foreach ($list_servicos as $item) {
                            $valorItens += ($item->quantidade * $item->valor_produto_desconto);

                            $tableS .= '<tr>';
                                $tableS .= '<td> '.h($item->ecm_produto->nome).'</td>';
                                $tableS .= '<td> '.h($item->ecm_produto->sigla).' </td>';
                                $tableS .= '<td> '. $item->quantidade.' x '.$this->Number->format($item->valor_produto_desconto, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' </td>';
                                $tableS .= '<td> '.$item->ecm_produto->conta_azul.'</td>';
                            $tableS .= '<tr>';
                        }
                        $tableS .= '</table>';
                    }

                    $openDiv = '<div class="large-2" style="float: left;margin-top:5px;">';
                    $detalhamento = $openDiv.'<strong>'.__('Pedido Online:').'</strong> '.h($ecmVenda->pedido).'</br>';
                    $detalhamento .= '<strong>'.__('Valor Itens:').'</strong> '.$this->Number->format($valorItens, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).'</br>';
                    $detalhamento .= '<br></div>';

                    $detalhamento .= $tableP;
                    $detalhamento .= $tableS;

                ?>
                <td><?= $ecmVenda->id.'<br /><b>('.(is_null($ecmVenda->pedido) ? __('Não Importado') : $ecmVenda->pedido).')</b>'
                    .'<br /><b>('.(is_null($ecmVenda->conta_azul) ? __('Não Exportado') : $ecmVenda->conta_azul).')</b>'  ?></td>
                <td><?= h($ecmVenda->mdl_user->idnumber.' - '.$ecmVenda->mdl_user->firstname." ".$ecmVenda->mdl_user->lastname) ?></td>
                <td><?= h("Tipo: ".$ecmVenda->ecm_tipo_pagamento->nome) ?><br/>
                    <?= h("Operadora: ".$ecmVenda->ecm_operadora_pagamento->nome) ?></td>
                <td><?= h($ecmVenda->ecm_carrinho->ecm_alternative_host->shortname) ?></td>
                <td><?= h($ecmVenda->data->format('d/m/Y')) ?></td>

                <td> <?= $ecmVenda->numero_parcelas .' x '.$this->Number->format($ecmVenda->valor_parcelas, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) .'<br>'.$this->Number->format($ecmVenda->ecm_carrinho->calcularTotal(), ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?> </td>
                <td>
                    <div name="statusVenda">
                        <span>
                            <?= h($ecmVendaStatus[$ecmVenda->ecm_venda_status_id]) ?>
                        </span>
                            <?= $this->Form->hidden('id', ['value' => $ecmVenda->id]) ?>
                            <?= $this->Form->input('statusid', ['options' => $ecmVendaStatus, 'label' => false,
                                'value' => $ecmVenda->ecm_venda_status_id]) ?>
                    </div>
                </td>
                <td class="actions">
                    <?= $this->Html->link('', '',['title' => __('Ver Detalhes'), 'class' => 'glyphicon  glyphicon-list-alt ver-detalhes' ]) ?>
                    <?= $this->Html->link('', '',['title' => __('Mostra Ocultas Detalhes'), 'class' => 'glyphicon glyphicon-eye-open exibir-ocultas', 'data-eye' => 'show' ]) ?>
                    <?= $this->Html->tag('div', $detalhamento, ['style' => 'display:none;', 'class' => 'large-12 ver-detalhes-texto']); ?>

                    <div name="editar" style="cursor:pointer; display:inline ;" onclick="editarStatus(this);">
                        <?= $this->Html->image("edit.gif", ['title' => 'Editar']) ?>
                    </div>
                    <div name="detalhar" style="cursor:pointer;width:20px;margin-left:5px; display:inline">
                        <?= $this->Html->image("detalhar.png", ['title' => 'Detalhar', 'url' => ['controller' => '', 'action' => 'view', $ecmVenda->id]]) ?>
                    </div>

                    <?= (is_null($ecmVenda->conta_azul) && $ecmVenda->ecm_venda_status_id == 2) ? $this->Form->checkbox('export', ['hiddenField' => false, 'class' => 'select-export', 'data-id' => $ecmVenda->id, 'value' => 0, 'title' => 'Exportar lista ContaAzul' ]) : '' ?>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (isset($dbaVendas) && count($dbaVendas) > 0): ?>
                <?php foreach ($dbaVendas as $dbaVenda): ?>

                <?php

                    $valorItens = 0;
                    $tableS = '';
                    $tableP = '';
                    $tablePA = '';

                    if (isset($dbaVenda['dba_vendas_produtos']) && count($dbaVenda['dba_vendas_produtos']) > 0){

                        $tablePA = '<br> <table class="table table-striped">';
                        $tablePA .= '<thead><tr>';
                            $tablePA .= '<th width="6%" > Aplicaçãod </th>';
                            $tablePA .= '<th width="10%" > CodigoTW </th>';
                            $tablePA .= '<th width="15%" > ContaAzul </th>';
                            $tablePA .= '<th width="15%" > Tecnologia </th>';
                            $tablePA .= '<th width="5%" > Software </th>';
                            $tablePA .= '<th width="5%" > Aplicacao </th>';
                            $tablePA .= '<th width="5%" > Conexao </th>';
                            $tablePA .= '<th width="5%" > Licenca </th>';
                            $tablePA .= '<th width="10%" > Modulos </th>';
                            $tablePA .= '<th width="10%" > Ativacao </th>';
                            $tablePA .= '<th width="10%" > Especiais </th>';
                            $tablePA .= '<th width="10%" > Update </th>';
                        $tablePA .= '</tr></thead>';

                        $tableP = '<br> <table class="table table-striped">';
                        $tableP .= '<thead><tr>';
                            $tableP .= '<th width="40%" > Produto </th>';
                            $tableP .= '<th width="10%" > Sigla </th>';
                            $tableP .= '<th width="6%" > Edicao </th>';
                            $tableP .= '<th width="8%" > Aplicacao </th>';
                            $tableP .= '<th width="10%" > Modulos </th>';
                            $tableP .= '<th width="6%" > Rede </th>';
                            $tableP .= '<th width="10%" > Aquisição </th>';
                            $tableP .= '<th  width="15%" > Valor </th>';
                            $tableP .= '<th  width="20%" > Protetor </th>';
                        $tableP .= '</tr></thead>';

                        foreach ($dbaVenda['dba_vendas_produtos'] as $dba_prod) {
                            $valorItens += $dba_prod->valor;

                            if(isset($dba_prod->ecm_produto_ecm_aplicacao) && count($dba_prod->ecm_produto_ecm_aplicacao)){
                                foreach ($dba_prod->ecm_produto_ecm_aplicacao as $app) {
                                        $tablePA .= '<tr> <td> '.((isset($dba_prod->ecm_produto))?$dba_prod->ecm_produto->sigla:'').'</td>';
                                        $tablePA .= '<td> '.$app->codigo_tw.'</td>';
                                        $tablePA .= '<td> '.$app->conta_azul_produto.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->tecnologia.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->software.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->aplicacao.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->conexao.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->licenca.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->modulos_linha.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->ativacao.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->especiais.'</td>';
                                        $tablePA .= '<td>'. $app->ecm_produto_aplicacao->mudanca_de_aplicacao.'</td>';
                                        $tablePA .= '</tr>';
                                }
                            }

                            $tableP .= '<tr '.(($dba_prod->valor == 0) ? 'class="valorzero" style="display:none"' : '').' >';
                                $tableP .= '<td> '. ( isset($dba_prod->ecm_produto) ? h($dba_prod->ecm_produto->nome) : '').'</td>';
                                $tableP .= '<td> '.h($dba_prod->sigla).' </td>';
                                $tableP .= '<td> '.h($dba_prod->edicao).' </td>';
                                $tableP .= '<td> '.h($dba_prod->aplicacao).' </td>';
                                $tableP .= '<td> '.h($dba_prod->modulos).' </td>';
                                $tableP .= '<td> '.h($dba_prod->pontos_rede).' </td>';
                                $tableP .= '<td> '.h($dba_prod->tipo_aquisicao).' </td>';
                                $tableP .= '<td> '.$this->Number->format($dba_prod->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' </td>';
                                $tableP .= '<td> '.h($dba_prod->tipo_protecao.' '.$dba_prod->numero_protetor).'</td>';
                            $tableP .= '<tr>';
                        }
                        $tableP .= '</table>';
                        $tablePA .= '</table>';
                    }

                    if (isset($dbaVenda['dba_vendas_servicos']) && count($dbaVenda['dba_vendas_servicos']) > 0){

                        $tableS = '<br> <table class="table table-striped">';
                        $tableS .= '<thead><tr>';
                            $tableS .= '<th width="50%" > Servico </th>';
                            $tableS .= '<th width="15%" > Sigla </th>';
                            $tableS .= '<th  width="15%" > Valor </th>';
                            $tableS .= '<th  width="20%" > Tipo </th>';
                        $tableS .= '</tr></thead>';

                        foreach ($dbaVenda['dba_vendas_servicos'] as $dba_serv) {
                            $valorItens += $dba_serv->valor;

                            $tableS .= '<tr '.(($dba_serv->valor == 0) ? 'class="valorzero" style="display:none"': '').' >';
                                $tableS .= '<td> '.h($dba_serv->ecm_produto->nome).'</td>';
                                $tableS .= '<td> '.h($dba_serv->ecm_produto->sigla).' </td>';
                                $tableS .= '<td> '.$this->Number->format($dba_serv->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' </td>';
                                $tableS .= '<td> '.(($dba_serv->tipo_top_id == 2) ? 'Curso' : 'Suporte').'</td>';
                            $tableS .= '<tr>';
                        }
                        $tableS .= '</table>';
                    }

                    $openDiv = '<div class="large-2" style="float: left;margin-top:5px;">';
                    $detalhamento = $openDiv.'<strong>'.__('Proposta:').'</strong> '.h($dbaVenda->pedido).'</br>';

                    $valorItens += $dbaVenda->valor_frete;

                    if( $valorItens == $dbaVenda->valor)
                        $detalhamento .= '<strong>'.__('Valor Itens + frete:').'</strong> '.$this->Number->format($valorItens, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).'</br>';
                    else
                        $detalhamento .= '<strong>'.__('Valor Itens + frete:').'</strong > <div style="color:red">  '.$this->Number->format($valorItens, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).' </div> </br>';
                        
                    $detalhamento .= '<br></div>';
                    $detalhamento .= $tableP;
                    $detalhamento .= $tablePA;
                    $detalhamento .= $tableS;

                ?>

                <tr> 
                    <td> <?= $dbaVenda->pedido ?> <br><b> (Venda TOP) </b> <br> <?= '<b>('.(is_null($dbaVenda->conta_azul) ? __('Não Exportado') : $dbaVenda->conta_azul).')</b>' ?>  </td>
                    <td> <?= h($dbaVenda->mdl_user->idnumber.' - '.$dbaVenda->mdl_user->firstname." ".$dbaVenda->mdl_user->lastname) ?> </td>
                    <td> <?= h($dbaVenda->forma_pagamento) ?> </td>
                    <td> <?= h($dbaVenda->empresa) ?> </td>
                    <td> <?= h($dbaVenda->data_venda->format('d/m/Y')) ?> </td>
                    <td> Parcelas: <?= $dbaVenda->parcelas .' <br> '.$this->Number->format($dbaVenda->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) .' <br> Frete:'.$this->Number->format($dbaVenda->valor_frete, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?> </td>
                    <td> <?= h($dbaVenda->tipo) ?> </td>
                    <td>
                        <?= $this->Html->link('', '#',['title' => __('Ver Detalhes'), 'class' => 'glyphicon glyphicon-list-alt ver-detalhes' ]) ?>
                        <?= $this->Html->link('', '#',['title' => __('Mostra Ocultas Detalhes'), 'class' => 'glyphicon glyphicon-eye-close exibir-ocultas', 'data-eye' => 'show' ]) ?>
                        <?= $this->Html->tag('div', $detalhamento, ['style' => 'display:none;', 'class' => 'large-12 ver-detalhes-texto']); ?>
                        <?= (is_null($dbaVenda->conta_azul)) ? $this->Form->checkbox('export', ['hiddenField' => false, 'class' => 'select-export-pedido', 'data-pedido' => $dbaVenda->pedido, 'value' => 0, 'title' => 'Exportar lista ContaAzul' ]) : '' ?>
                    </td>
                </tr> 
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?= $this->Form->hidden('sales', ['value' =>"[]"]) ?>
    <?= $this->Form->hidden('pedidos', ['value' =>"[]"]) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $(function() {

        var check_mes = false;

        $(".venda").hide();
        $(".estornada").hide();
        $(".finalizada").hide();
        $(".cancelada").hide();
        $(".andamento").hide();
        $(".exibir-ocultas").hide();

        $("input[type='text']").map(function(){
                if($(this).val() != '')
                    check_mes = true;
        });

        $("#mes option:first").val(0);
        if(check_mes) 
            $("#mes option:first").prop("selected", true);

        $("input[type='text']").change(function(){
                var val = $(this).val();

                if(val != '')
                    $("select").val(0);
        });

        $("select").change(function(){
                var val = $(this).val();

                if(val != '')
                    $("input[type='text']").val("");
        });

        $(".action").click(function(){
            var el = $(this);
            var action = el.data('action');
            var visible = el.data('visible');

            if(action == 'show'){
                $("."+visible).show();
                el.data('action', 'hide');
                el.removeClass('glyphicon-eye-open')
                el.addClass('glyphicon-eye-close')
            }else{
                $("."+visible).hide();
                el.data('action', 'show');     
                el.addClass('glyphicon-eye-open')
                el.removeClass('glyphicon-eye-close')      
            }
        });

        $(".exibir-ocultas").click(function(){
            var els = $("tr.valorzero");

            if($(this).data('eye') == 'show'){
                els.hide();
                $(this).data('eye', 'not');
                $(this).removeClass('glyphicon-eye-open');
                $(this).addClass('glyphicon-eye-close');
            }else{
                els.show();
                $(this).data('eye', 'show');
                $(this).addClass('glyphicon-eye-open');
                $(this).removeClass('glyphicon-eye-close');
            }

            return false;
        });

        $(".btnContaAzul:visible").hide();

        if($('.select-export').length ==0 & $('.select-export-pedido').length ==0){
            $(".btnSelectAll:visible").hide();
        }else{
            $(".btnSelectAll:visible").data('view', 'show');
        }

        $(".btnContaAzul").click(function() {
            var data = $("input[name='sales']").val();
            var data2 = $("input[name='pedidos']").val();
            
            $('<form>', {
                "id": "sendSales",
                "html": '<input type="hidden" id="ids" name="ids" value="' + data + '" />'+'<input type="hidden" id="pedidos" name="pedidos" value="' + data2 + '" />',
                "action": '/conta-azul/export-sales',
                "method" : 'POST'
            }).appendTo(document.body).submit();
        
        });

        $(".btnSelectAll").on('click',function() {
            var ids = '';
            var pedidos = '';

            if($(this).data('view') == 'show'){
                $('.select-export').prop('checked','checked');
                $('.select-export-pedido').prop('checked','checked');

                $(this).data('view', 'hide');
                ids = $('.select-export').map(function() {
                                                    return $(this).data('id');
                                                }).get().join();
                
                pedidos = $('.select-export-pedido').map(function() {
                                                    return $(this).data('pedido');
                                                }).get().join();
                $(".btnContaAzul:hidden").show();     
                $(".btnSelectAll").html('Deselecionar Todos');
            }else{
                $(this).data('view', 'show');
                $('.select-export').removeAttr('checked');
                $('.select-export-pedido').removeAttr('checked');
                $(".btnContaAzul:visible").hide();
                $(".btnSelectAll").html('Selecionar Todos');
            }

            ids = JSON.parse('['+ids+']');
            ids = JSON.stringify(ids);
            $("input[name='sales']").val(ids);

            pedidos = JSON.parse('['+pedidos+']');
            pedidos = JSON.stringify(pedidos);
            $("input[name='pedidos']").val(pedidos);
        });

        $( ".select-export" ).change(function() {
                var id = $(this).data('id');
                var list = $("input[name='sales']").val();
                    list = JSON.parse(list);

                var list2 = $("input[name='pedidos']").val();
                    list2 = JSON.parse(list2);

                if($(this)[0].checked)
                    list.push(id);
                else
                    list.splice(list.indexOf(id),1);

                if(list.length | list2.length){
                    $(".btnContaAzul:hidden").show();
                    $(".btnSelectAll").html('Deselecionar Todos');
                    $(".btnSelectAll").data('view', 'hide');
                }else{
                    $(".btnContaAzul:visible").hide();
                    $(".btnSelectAll").html('Selecionar Todos');
                    $(".btnSelectAll").data('view', 'show');
                }
                
                list = JSON.stringify(list);
                $("input[name='sales']").val(list);
        });

        $( ".select-export-pedido" ).change(function() {

                var id = $(this).data('pedido');
                var list = $("input[name='pedidos']").val();
                    list = JSON.parse(list);

                var list2 = $("input[name='sales']").val();
                    list2 = JSON.parse(list2);

                if($(this)[0].checked)
                    list.push(id);
                else
                    list.splice(list.indexOf(id),1);

                if(list.length | list2.length){
                    $(".btnContaAzul:hidden").show();
                    $(".btnSelectAll").html('Deselecionar Todos');
                    $(".btnSelectAll").data('view', 'hide');
                }else{
                    $(".btnContaAzul:visible").hide();
                    $(".btnSelectAll").html('Selecionar Todos');
                    $(".btnSelectAll").data('view', 'show');
                }
                
                list = JSON.stringify(list);
                $("input[name='pedidos']").val(list);
        });

        $("#inicio").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#fim").datepicker("option", "minDate", $(this).datepicker("getDate"));
        });
        $("#fim").datepicker({dateFormat: 'dd/mm/yy'}).change(function () {
            $("#inicio").datepicker("option", "maxDate", $(this).datepicker("getDate"));
        });
        select = $('div[name="statusVenda"]').find(".input select");
        select.find('option[value="0"]').remove();
        select.hide();
        select.change(function() {
            var id = select.parent().parent().find('input[name="id"]').val();
            var status = select.val();
            $.ajax({
                type: "POST",
                url: '',
                data: {id: id, status: status},
                dataType : 'json',
                success:function(data) {
                    if(data)
                        select.parent().parent().parent().find("span").text(select.find('option:selected').text());
                },
                complete:function() {
                    select.hide();
                    $('div[name="statusVenda"]').find("span").show();
                }
            });
        });
    });
    function editarStatus(div){
        select = $(div).parent().parent().find(".input select");
        visivel = select.is(":visible");
        statusVenda = $('div[name="statusVenda"]');
        statusVenda.find(".input select").hide();
        statusVenda.find("span").show();
        if(!visivel){
            select.show();
            $(div).parent().parent().find("span").hide();
        }
    }
</script>