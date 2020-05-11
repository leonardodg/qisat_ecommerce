<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/clipboard.min.js') ?>
<?= $this->Html->script('/webroot/js/jquery-validation/jquery.validate.min.js') ?>
<?php


$scriptVerDetalhes = 'var parentTr = $(this).parent().parent();

                      $(".tr-detalhes").fadeOut("fast",function(){
                        $(this).remove();
                      });
                      if(!parentTr.hasClass("tr-detalhes-show")){
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                        parentTr.addClass("tr-detalhes-show");
                        var texto = $(this).parent().find(".ver-detalhes-texto").html();
                        var colsTable = 6;

                        texto = "<td colspan=\'"+colsTable+"\'>"+texto+"</td>";
                        texto = "<tr class=\'tr-detalhes\' style=\'display:none;background: #DBD9D9;\'>"+texto+"</tr>";

                        parentTr.after(texto);
                        $(".tr-detalhes").fadeIn();
                      }else{
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                      }';

$scriptVerDetalhes = $this->Jquery->get('.ver-detalhes')->event('click',$scriptVerDetalhes);

echo $this->Html->scriptBlock($this->Jquery->domReady($scriptVerDetalhes));


$urlCancelarSuperPay = \Cake\Routing\Router::url([
    'controller' => false,
    'plugin' => 'FormaPagamentoSuperPayV3',
    'action' => 'cancelar'
    ]);

$urlCancelarFastConnect = \Cake\Routing\Router::url([
        'controller' => false,
        'plugin' => 'FormaPagamentoFastConnect',
        'action' => 'cancelar'
        ]);

$urlVenda = \Cake\Routing\Router::url([
        'controller' => false,
        'plugin' => 'Vendas',
        'action' => 'index'
        ]);
    

$statusTransacao = [
                    'aguardando_pagamento' => 'aguardando_pagamento',
                    'cancelada' => 'cancelada',
                    'erro' => 'erro',
                    'estorno' => 'estorno',
                    'negada' => 'negada',
                    'paga' => 'paga'
                ]; 

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
                <?= $this->Form->input('status_transacao',['class'=>'reset', 'label'=> __('Status da Transação'),'options' => $statusTransacao, 'empty' => __('Todos')]) ?>
            </div>
        </div>
        <hr>

         <div class="row">

            <div class="col-md-2 text-right">
                <strong><?= __('Busca Específica') ?></strong>
            </div>

            <div class="col-md-2">
                <?= $this->Form->input('id', [ 'label' => 'Código','class'=>'set' , 'title' => __('Informando esse campo os outros filtros serão ignorados!')]) ?>
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
        
        </div>

    </fieldset>
    <?= $this->Form->end() ?>

<?php if ($ecmTransacao->count() > 1): ?>

    <br><br>
    <h3><?= __('Resumo Transações') ?></h3>

    <table cellpadding="0" cellspacing="0" class="table table-condensed table-responsive">
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
            <td  width="9%" > 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="cartao" ></span>
                <b>Cartão</b> 
            </td>

            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['dia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['dia'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Paga </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['paga'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['paga'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Negada </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['negada'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['negada'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Aguardando </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['aguardando_pagamento'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['aguardando_pagamento'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Cancelada </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['cancelada'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['cancelada'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Falha </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['erro'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['erro'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Estorno </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['cartao']['estorno'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['estorno'] ?> </td>
        </tr>

        <tr>
            <td  width="9%" > 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="recorrencia" ></span>
                <b>Recorrêcias</b> 
            </td>

            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['dia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['dia'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Paga </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['paga'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['paga'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Negada </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['negada'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['negada'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Aguardando </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['aguardando_pagamento'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['aguardando_pagamento'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Cancelada </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['cancelada'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['cancelada'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Falha </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['erro'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['erro'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Estorno </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['recorrencia']['estorno'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['estorno'] ?> </td>
        </tr>
   
        </tbody>
    </table>

<?php endif; ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">  Código </th>
                <th width="10%"> Status </th>
                <th width="30%"> Nome Cliente </th>
                <th width="8%">  valor </th>
                <th width="4%"> Parcelas </th>
                <th width="10%"> Data </th>
                <th class="actions" width="5%" > Ação </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmTransacao as $ecmTransacao): ?>

            <?php
                $openDiv = '<div class="large-2" style="float: left;margin-top:5px;">';

                $detalhamento = $openDiv.'<strong>'.__('Venda:').'</strong> '.h($ecmTransacao->ecm_venda_id).'</br>';
                $detalhamento .= ($ecmTransacao->ecm_venda->pedido) ? '<strong>'.__('Pedido:').'</strong>'.h($ecmTransacao->ecm_venda->pedido).'</br>' : '';
                $detalhamento .= ($ecmTransacao->ecm_venda->proposta) ? '<strong>'.__('Proposta:').'</strong> '.h($ecmTransacao->ecm_venda->proposta).'</br>' : '' ;
                $detalhamento .= ($ecmTransacao->ecm_recorrencia_id) ? '<strong>'.__('Recorrencia:').'</strong> '.$ecmTransacao->ecm_recorrencia_id.'</div>' : '</div>' ;

                $detalhamento .= $openDiv.'<strong>'.__('Forma De Pagamento').'</strong> <br>'.$ecmTransacao->ecm_tipo_pagamento->ecm_forma_pagamento->nome.'</div>';
                $detalhamento .= $openDiv.'<strong>'.__('Operadora').'</strong> <br>'.$ecmTransacao->ecm_operadora_pagamento->nome.'</div>';

                $detalhamento .= $openDiv.'<strong>'.__('Data Retorno:').'</strong> '.(($ecmTransacao->data_retorno) ? $ecmTransacao->data_retorno->format('d/m/Y') : '') .'<br>';
                $detalhamento .= '<strong>'.__('Data Envio:').'</strong> '.(($ecmTransacao->data_envio) ? $ecmTransacao->data_envio->format('d/m/Y') : '').'<br>';
                $detalhamento .= '<strong>'.__('Data Cobrança:').'</strong> '.(($ecmTransacao->data_cobranca) ? $ecmTransacao->data_cobranca->format('d/m/Y') : '').'<br>';
                $detalhamento .= '<strong>'.__('Campainha:').'</strong> '.(($ecmTransacao->data_campainha) ? $ecmTransacao->data_campainha->format('d/m/Y') : '').'</div>';
                
                $detalhamento .= $openDiv. (($ecmTransacao->erro) ? '<strong>'.__('Mensagem:').'</strong> '.h($ecmTransacao->erro) .'<br>': '');
                $detalhamento .= '<strong> IP: </strong> '.h($ecmTransacao->ip). '</div>';

                $table = '<br> <table class="table table-striped">';
                $table .= '<thead><tr>';
                    $table .= '<th width="20%" > TID </th>';
                    $table .= '<th width="10%" > Autorização </th>';
                    $table .= '<th  width="40%" > PAN </th>';
                    $table .= '<th > NSU </th>';
                    $table .= '<th > Erro </th>';
                $table .= '</tr></thead> <tr>';
                
                $table .= '<td> '.$ecmTransacao->tid .'</td>';
                $table .= '<td> '.$ecmTransacao->arp .'</td>';
                $table .= '<td> '.$ecmTransacao->pan .'</td>';
                $table .= '<td> '.$ecmTransacao->nsu .'</td>';
                $table .= '<td> '.$ecmTransacao->lr .'</td> </tr> </table>';

                $detalhamento .= $table;
            ?>
            <tr>
                <td><?= $ecmTransacao->id.'<br>'.$ecmTransacao->id_integracao ?></td>
                <td><?= $ecmTransacao->status ?></td>
                <td><?= $ecmTransacao->mdl_user->firstname.' '.$ecmTransacao->mdl_user->lastname?></td>
                <td><?= $this->Number->format($ecmTransacao->valor, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) ?></td>
                <td><?= ($ecmTransacao->ecm_recorrencia_id) ?  $ecmTransacao->parcela.'/'.$ecmTransacao->ecm_venda->numero_parcelas : $ecmTransacao->ecm_venda->numero_parcelas; ?></td>
                <td><?= h($ecmTransacao->data_envio) ?></td>
                <td> 
                    <?= $this->Html->link('', '#',['title' => __('Ver Detalhes'), 'class' => 'glyphicon glyphicon-eye-open ver-detalhes' ]) ?>
                    <?= $this->Html->tag('div', $detalhamento, ['style' => 'display:none;', 'class' => 'large-12 ver-detalhes-texto']); ?>

                    <?= ($ecmTransacao->status == 'paga' && ($ecmTransacao->ecm_transacao_status_id == 1 || $ecmTransacao->ecm_transacao_status_id == 7)) ? $this->Html->link('', '#',['title' => __('Estornar'), 'class' => 'glyphicon glyphicon-remove cancelar', 'title' => 'Estornar', 'data-id' => $ecmTransacao->id, 'data-controller' => $ecmTransacao->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller, 'data-venda' => $ecmTransacao->ecm_venda_id ]) : ''; ?>
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

        $(".cartao").hide();
        $(".recorrencia").hide();

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

    $( ".cancelar" ).click(function() {

        var id = $(this).data('id');
        var venda = $(this).data('venda');
        var controller = $(this).data('controller');
        var params = { "id": id };
        
        var urlCancelarSuperPay = '<?= $urlCancelarSuperPay ?>';
        var urlCancelarFastConnect = '<?= $urlCancelarFastConnect ?>';
        var url = (controller == 'FastConnect') ? urlCancelarFastConnect : urlCancelarSuperPay;

        var urlVenda = '<?= $urlVenda ?>';
        var el = $(this);

        var vendaCallback = function(result) {
            var status = 3;
            if(result){
                $.ajax({
                    type: "POST",
                    url: urlVenda,
                    data: {id: venda, status: status},
                    dataType : 'json',
                    success:function(data) {
                        bootbox.alert('Venda Atualizada com SUCESSO!');
                    }
                });
            }
        };

        var callback = function(result){
            if(result){
                $.post(url, params, function( data ) {
                    console.log(data);
                    if(data && data.retorno && data.retorno.sucesso){
                        el.hide();
                        bootbox.confirm("SUCESSO! Deseja alterar status da venda para ESTORNADA? "+venda, vendaCallback);
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
