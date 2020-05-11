<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/clipboard.min.js') ?>
<?= $this->Html->script('/webroot/js/jquery-validation/jquery.validate.min.js') ?>

<?php

echo $this->JqueryUI->getScript();

$url = \Cake\Routing\Router::url([
            'controller' => false,
            'plugin' => 'FormaPagamentoSuperPayRecorrencia',
            'action' => 'cancelar'
            ]);

$scriptVerDetalhes = 'var parentTr = $(this).parent().parent();

                      $(".tr-detalhes").fadeOut("fast",function(){
                        $(this).remove();
                      });
                      if(!parentTr.hasClass("tr-detalhes-show")){
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                        parentTr.addClass("tr-detalhes-show");
                        var texto = $(this).parent().find(".ver-detalhes-texto").html();
                        var colsTable = parentTr.find("td").length;

                        texto = "<td colspan=8 >"+texto+"</td>";
                        texto = "<tr class=\'tr-detalhes\' style=\'display:none;background: #DBD9D9;\'>"+texto+"</tr>";

                        parentTr.after(texto);
                        $(".tr-detalhes").fadeIn();
                      }else{
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                      }';

$scriptVerDetalhes = $this->Jquery->get('.ver-detalhes')->event('click',$scriptVerDetalhes);

echo $this->Html->scriptBlock($this->Jquery->domReady($scriptVerDetalhes));

$ndias = (cal_days_in_month(CAL_GREGORIAN, $datanow->format('n'), $datanow->format('Y')) -1);
$i=0;

?>


<div class="ecmPromocao col-md-12">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>

        <div class="row">

            <div class="col-md-2 col-md-offset-2">
                <?= $this->Form->input('mes',['label'=> __('Mês'), 'class'=>'reset', 'options' => $list_mes, 'empty' => __('Selecione o Mês'),'value' => $datanow->format('m/Y')]) ?>
            </div>

            <div class="col-md-2">
            <?= $this->Form->input('status', ['label'=> 'Status', 'class'=>'reset', 'options' => [['value' => 1, 'text' => 'Ativo'], ['value' => 0, 'text' => 'Desativo']], 'empty' => __('Todos os Status') ]) ?>
            </div>
        </div>
    <hr>

        <div class="row">
                      
            <div class="col-md-2 text-right">
                <strong><?= __('Busca Específica') ?></strong>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('id', [ 'label' => __('Código'), 'type'=>'text','class'=>'set' ])?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('venda', [ 'label' => __('Venda'), 'type'=>'text','class'=>'set' ])?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('pedido', [ 'label' => __('Pedido'), 'type'=>'text','class'=>'set'])?>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('proposta', [ 'label' => __('Proposta'), 'type'=>'text','class'=>'set'])?>
            </div>
        </div>

         <div class="row right">
            <div class="col-md-12">
                <?= $this->Form->button('Buscar', ['type' => 'submit', 'class' => 'right']) ?>
            </div> 
        </div>

    </fieldset>
    <?= $this->Form->end() ?>


<?php if ($ecmRecorrencia->count() > 1): ?>

<h3><?= __('Resumo Recorrências') ?></h3>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th  width="9%"> Dia </th>
                <?php 
                      for (; $i <= $ndias; $i++) { 
                          echo '<th> '.($i+1).'</th>';
                      }
                      $i=0;
                ?>
                
                <th width="5%"> Total </th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td  width="9%"> <b>Recorrencias</b> </td>
            <?php 
                    for (; $i <= $ndias; $i++) {
                        echo '<td> '.$tabelaRecorrencias[$i].'</td>';
                    }
                    $i=0;
            ?>
            <td width="5%"> <?= $tabelaTotal[0] ?> </td>
        </tr>

        <tr>
            <td  width="9%"> <b>Ativas</b> </td>
            <?php 
                    for (; $i <= $ndias; $i++) { 
                        echo '<td> '.$tabelaAtivas[$i].'</td>';
                    }
                    $i=0;
            ?>
            <td width="5%"> <?= $tabelaTotal[1] ?> </td>
        </tr>

        <tr>
            <td  width="9%"> <b>Desativas</b> </td>
                <?php 
                    for (; $i <= $ndias; $i++) { 
                        echo '<td> '.$tabelaDesativas[$i].'</td>';
                    }
                    $i=0;
                ?>
            <td width="5%"> <?= $tabelaTotal[2] ?> </td>
        </tr>

        </tbody>
    </table>

<?php endif; ?>



<h3><?= __('Lista de Recorrências') ?></h3>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="5%" > Código </th>
                <th width="5%"> Status </th>
                <th width="30%" > Cliente </th>
                <th width="10%"> valor </th>
                <th width="5%"> Parcelas </th>
                <th width="5%"> Restantes </th>
                <th width="10%"> Data </th>
                <th class="actions" width="5%" > Ações </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmRecorrencia as $ecmRecorrencia): ?>
            <?php
                $openDiv = '<div class="large-2" style="float: left;margin: 20px 0;">';

                $detalhamento = $openDiv.'<strong>'.__('Venda').'</strong> '.h($ecmRecorrencia->ecm_venda_id).'</br>';
                $detalhamento .= ($ecmRecorrencia->ecm_venda->pedido) ? '<strong>'.__('Pedido').'</strong> '.h($ecmRecorrencia->ecm_venda->pedido).'</br>'  : '';
                $detalhamento .= ($ecmRecorrencia->ecm_venda->proposta) ? '<strong>'.__('Proposta').'</strong> '.h($ecmRecorrencia->ecm_venda->proposta).'</div>' : '</div>' ;

                $detalhamento .= $openDiv.'<strong>'.__('Forma De Pagamento').'</strong> <br>'.$ecmRecorrencia->ecm_tipo_pagamento->ecm_forma_pagamento->nome.'</div>';
                $detalhamento .= $openDiv.'<strong>'.__('Operadora').'</strong> <br>'.$ecmRecorrencia->ecm_operadora_pagamento->nome.'</div>';
              
                $detalhamento .= $openDiv.'<strong>'.__('Data Retorno:').'</strong> '.(($ecmRecorrencia->data_retorno) ? $ecmRecorrencia->data_retorno->format('d/m/Y') : '') .'<br>';
                $detalhamento .= '<strong>'.__('Primeira Cobrança:').'</strong> '.(($ecmRecorrencia->data_primeira_cobranca) ? $ecmRecorrencia->data_primeira_cobranca->format('d/m/Y') : '').'</div>';

                $detalhamento .= '<div class="large-3" style="float: left;margin: 20px 0;">'.(($ecmRecorrencia->mensagem_venda) ? '<strong>'.__('Mensagem:').'</strong> '.$ecmRecorrencia->mensagem_venda : '') .'<br>';
                $detalhamento .= ( ($ecmRecorrencia->erro) ? '<strong>'.__('Erro:').'</strong> '.$ecmRecorrencia->erro : '').'</div>';


                $tablePagamentos = '<br> <table class="table table-striped">';
                $tablePagamentos .= '<thead><tr>';
                $tablePagamentos .= '<th > Parcelas </th>';
                for ($i=1; $i <= 12; $i++) { 
                    $tablePagamentos .= '<th> '.($i).'</th>';
                }
                $tablePagamentos .= '</tr></thead>';

                $tablePagamentos .= '<tr> <td> </td>';

                $table2 = '<br> <table class="table table-striped">';
                $table2 .= '<thead><tr>';
                $table2 .= '<th width="4%" > id </th>';
                $table2 .= '<th width="6%" > Estabelecimento </th>';
                $table2 .= '<th width="10%" > Data </th>';
                $table2 .= '<th width="4%" > Valor </th>';
                $table2 .= '<th width="3%" > Parcela </th>';
                $table2 .= '<th width="3%" > Status </th>';
                $table2 .= '<th width="25%" > Dados </th>';
                $table2 .= '</tr></thead>';

                foreach ($ecmRecorrencia->ecm_transacao as $transacao) {

                    $status = 'Não Pago';
                    if(($transacao->id == $transacao->id_integracao) || strpos(strval($transacao->id_integracao), strval($transacao->ecm_recorrencia_id)) !== false )
                        $status = $transacao->getStatusV3($transacao->ecm_transacao_status_id);
                    else if(  ($transacao->ecm_tipo_pagamento_id == 1) && array_key_exists($transacao->ecm_operadora_pagamento_id, [1,2,3]) && $transacao->ecm_transacao_status_id  )
                        $status = $transacao->getStatusV1($transacao->ecm_transacao_status_id);
                    else if(is_null($transacao->data_retorno))
                        $status = 'Sem Retorno';


                    $tablePagamentos .= '<td> '.$status .' - '. ( (!is_null($transacao->data_cobranca)) ? $transacao->data_cobranca->format('d/m/Y') : '') .'</td>';

                    $table2 .= '<tr>';
                        $table2 .= '<td> '.$transacao->id .'</td>';
                        $table2 .= '<td> '.$transacao->estabelecimento.' </td>';
                        $table2 .= '<td> '.(($transacao->data_envio) ?'<strong>Envio:</strong>'.$transacao->data_envio->format('d/m/Y') : '')  .'<br>'.
                        (($transacao->data_retorno) ? '<strong>Retorno:</strong>'.$transacao->data_retorno->format('d/m/Y') : '')  .'<br>'.
                    (($transacao->data_cobranca) ?  '<strong>Cobrança:</strong>'.$transacao->data_cobranca->format('d/m/Y') : '') 
                                            .' </td>';
                        $table2 .= '<td> '.$this->Number->currency( $transacao->valor , 'BRL').' </td>';
                        $table2 .= '<td> '. $transacao->parcela .' </td>';
                        $table2 .= '<td> '.$status.' </td>';
                        $table2 .= '<td> ';
                        $table2 .= (($transacao->arp) ? '<strong>Autorização:</strong> '.$transacao->arp : '');
                        $table2 .= (($transacao->nsu) ? '<strong> NSU:</strong> '.$transacao->nsu : '');
                        $table2 .= (($transacao->pan) ? '<strong> PAN:</strong> '.$transacao->pan : '');
                        $table2 .=  (($transacao->tid) ? '<strong> TID:</strong> '.$transacao->tid : '');
                        $table2 .= (($transacao->erro) ? '<br><strong>Mensagem: </strong> '.$transacao->erro : '</td>');
                    $table2 .= '</tr>';

                }
                $table2 .= '</table>';

                $tablePagamentos .= '</tr>';
                $tablePagamentos .= '</table>';
                $detalhamento .= $tablePagamentos;
                $detalhamento .= $table2;

            ?>
            <tr>
                <td><?= $ecmRecorrencia->id ?></td>
                <td><?= ($ecmRecorrencia->status) ? 'Ativa' : 'Desativada' ?></td>
                <td><?= $ecmRecorrencia->mdl_user->idnumber.' - '.$ecmRecorrencia->mdl_user->firstname.' '.$ecmRecorrencia->mdl_user->lastname?></td>
                <td><?=  $this->Number->format($ecmRecorrencia->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?></td>
                <td><?= $ecmRecorrencia->quantidade_cobrancas ?></td>
                <td><?= $ecmRecorrencia->numero_cobranca_restantes ?></td>
                <td><?= h($ecmRecorrencia->data_envio) ?></td>

                <td> 
                    <?= $this->Html->link('', '#',['title' => __('Ver Detalhes'), 'class' => 'glyphicon glyphicon-eye-open ver-detalhes' ]) ?>
                    <?= $this->Html->tag('div', $detalhamento, ['style' => 'display:none;', 'class' => 'large-12 ver-detalhes-texto']); ?>
                    <?= ($ecmRecorrencia->status) ? $this->Html->link('', '#',['title' => __('Desativar'), 'class' => 'glyphicon glyphicon-remove ativeRecorrencia', 'title' => 'Desativar', 'data-id' => $ecmRecorrencia->id ]) : ''; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $('.reset').change(function(){
        $('.set').val('');
    });

    $( ".ativeRecorrencia" ).click(function() {

        var id = $(this).data('id');
        var params = { "id": id };
        var url = '<?= $url ?>';
        var el = $(this);

        var callback = function(result){
            if(result){
                $.post(url, params, function( data ) {
                    if(data && data.retorno && data.retorno.sucesso){
                        bootbox.alert('Sucesso!');
                        el.hide();
                    }else if(data && data.retorno && data.retorno.mensagem)
                        bootbox.alert('Falha: '+data.retorno.mensagem);
                    else
                        bootbox.alert('Falha!');
                }, "json");
            }
        };

        if(id)
            bootbox.confirm("Confirma Desativar Recorrência? "+id, callback);
    });
</script>