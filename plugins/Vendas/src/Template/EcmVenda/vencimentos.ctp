<?php 

$ndias = (cal_days_in_month(CAL_GREGORIAN, $datanow->format('n'), $datanow->format('Y')) -1);
$i=0;

?>

<div class="ecmVenda col-md-12">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>

            <div class="row">

                <div class="col-md-2 col-md-offset-2">
                    <?= $this->Form->input('mes',['label'=> __('Mês'), 'class'=>'reset', 'options' => $list_mes, 'empty' => __('Selecione o Mês'),'value' => $datanow->format('m/Y')]) ?>
                </div>

                <div class="col-md-4">
                    <?= $this->Form->button('Buscar', ['type' => 'submit', 'class' => 'right']) ?>
                </div> 
            </div>

    </fieldset>

    <?= $this->Form->end() ?>
</div>

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
            <td  width="9%"> Pago </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['cartao']['ids']['pago'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['cartao']['ids']['pago'][$i].'" data-action="cartao" ><a href="#">'.$tabelas['cartao']['pago'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['cartao']['pago'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['pago'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Em Aberto </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['cartao']['ids']['aberto'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['cartao']['ids']['aberto'][$i].'" data-action="cartao" ><a href="#">'.$tabelas['cartao']['aberto'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['cartao']['aberto'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['cartao']['total']['aberto'] ?> </td>
        </tr>

        <tr class="cartao active">
            <td  width="9%"> Estorno </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['cartao']['ids']['estorno'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['cartao']['ids']['estorno'][$i].'" data-action="cartao" ><a href="#">'.$tabelas['cartao']['estorno'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['cartao']['estorno'][$i].'</td>';
                }
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
            <td  width="9%"> Pago </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['recorrencia']['ids']['pago'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['recorrencia']['ids']['pago'][$i].'" data-action="recorrencia"><a href="#">'.$tabelas['recorrencia']['pago'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['recorrencia']['pago'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['pago'] ?> </td>
        </tr>
        <tr class="recorrencia active">
            <td  width="9%"> Não Pago </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['recorrencia']['ids']['cancelado'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['recorrencia']['ids']['cancelado'][$i].'" data-action="recorrencia"><a href="#">'.$tabelas['recorrencia']['cancelado'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['recorrencia']['cancelado'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['cancelado'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Em Aberto </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['recorrencia']['ids']['aberto'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['recorrencia']['ids']['aberto'][$i].'" data-action="recorrencia"><a href="#">'.$tabelas['recorrencia']['aberto'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['recorrencia']['aberto'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['aberto'] ?> </td>
        </tr>

        <tr class="recorrencia active">
            <td  width="9%"> Estorno </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++){
                    if(!empty($tabelas['recorrencia']['ids']['estorno'][$i]))
                        echo '<td class="views" data-ids="'.$tabelas['recorrencia']['ids']['estorno'][$i].'" data-action="recorrencia"><a href="#">'.$tabelas['recorrencia']['estorno'][$i].' <a/> </td>';
                    else
                        echo '<td> '.$tabelas['recorrencia']['estorno'][$i].'</td>';
                }
            ?>
            <td width="5%"> <?= $tabelas['recorrencia']['total']['estorno'] ?> </td>
        </tr>

        <tr>
            <td  width="9%" > 
                <span class="glyphicon glyphicon-eye-open action" aria-hidden="true" data-action="show" data-visible="boleto" ></span>
                <b>Boleto</b> 
            </td>

            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['boleto']['dia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['boleto']['total']['dia'] ?> </td>
        </tr>

        <tr class="boleto active">
            <td  width="9%"> Pago </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['boleto']['pago'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['boleto']['total']['pago'] ?> </td>
        </tr>
        <tr class="boleto active">
            <td  width="9%"> Não Pago </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['boleto']['cancelado'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['boleto']['total']['cancelado'] ?> </td>
        </tr>

        <tr class="boleto active">
            <td  width="9%"> Em Aberto </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['boleto']['aberto'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['boleto']['total']['aberto'] ?> </td>
        </tr>

        <tr class="boleto active">
            <td  width="9%"> Estorno </td>
            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['boleto']['estorno'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['boleto']['total']['estorno'] ?> </td>
        </tr>
        
        <tr>
            <td  width="9%" > 
                <b>TOTAL</b> 
            </td>

            <?php 
                $i=0;
                for (; $i <= $ndias; $i++) 
                    echo '<td> '.$tabelas['pagamentos']['dia'][$i].'</td>';
            ?>
            <td width="5%"> <?= $tabelas['pagamentos']['total'] ?> </td>
        </tr>
        </tbody>
    </table>


            <h4><?= __('Vencimentos') ?> </h4>

            <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%"> Venda </th>
                    <th width="5%"> Pedido </th>
                    <th width="5%"> Proposta </th>
                    <th width="5%"> Data Compra </th>
                    <th width="5%"> Data Vencimento </th>
                    <th width="5%"> Data Recibimento </th>
                    <th width="7%"> Valor </th>
                    <th width="5%"> Parcelas </th>
                    <th width="15%"> Detalhamento do Pagamento </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendas as $venda): ?>
                <tr>
                    <td> <?= $venda->id ?> <br> </td>
                    <td> <?= $venda->pedido ?> </td>
                    <td> <?= $venda->proposta ?> </td>
                    <td> <?= $venda->data->format('Y-m-d') ?> </td>
                    <td> <?= $venda->vencimento['data']->format('Y-m-d') ?> </td>
                    <td> <?= $venda->vencimento['data_pagamento']->format('Y-m-d') ?> </td>
                    <td> <?= $this->Number->format($venda->valor_parcelas, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) .'<br>'.$this->Number->format($venda->get('ecm_carrinho')->calcularTotal(), ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ '])  ?> </td>
                    <td> <?= $venda->vencimento['parcela'].' / '. $venda->numero_parcelas ?> </td>
                    <td> 
                            Forma de Pagamento: 
                            <?php 
                                if($venda->ecm_tipo_pagamento->ecm_forma_pagamento->tipo == 'cartao_recorrencia')
                                    $forma = 'crediLD'; 
                            ?>
                            Tipo: <strong> <?= ucfirst($venda->ecm_tipo_pagamento->ecm_forma_pagamento->tipo) ?> </strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>


<script>
        $(".cartao").hide();
        $(".recorrencia").hide();
        $(".boleto").hide();

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

        $(".views").click(function() {
            var data = $(this).data('ids');
                data = data.substring(0,data.length-1);
            var send = $(this).data('action');

            var url = (send == 'cartao') ? '/carrinho/transacao/relatorio-transacoes' : '/carrinho/recorrencia/relatorio-recorrencia'
            
            $('<form>', {
                "id": "viewRecorrencias",
                "html": '<input type="hidden" id="id" name="id" value="' + data + '" />',
                "action": url,
                "method" : 'POST'
            }).appendTo(document.body).submit();
        
        });
</script>