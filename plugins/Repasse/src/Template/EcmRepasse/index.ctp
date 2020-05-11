<?= $this->MutipleSelect->getScript();?>

<?php
    $attrPermissao = array(
        'width' => '460',
        'filter' => 'true',
        'multiple' => 'true',
        'position' => '"top"',
        'multipleWidth' => '250');

    $scripts = $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#ecm-permissao-ids',$attrPermissao));
    $scripts .= $this->Jquery->domReady($this->MutipleSelect->multipleSelect('#mdl-user-ids',$attrPermissao));
    echo $this->Html->scriptBlock($scripts);
?>

<?php

$scriptVerDetalhes = 'var parentTr = $(this).parent().parent();

                      $(".tr-detalhes").fadeOut("fast",function(){
                        $(this).remove();
                      });
                      if(!parentTr.hasClass("tr-detalhes-show")){
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                        parentTr.addClass("tr-detalhes-show");
                        var texto = $(this).parent().find(".ver-detalhes-texto").html();
                        var colsTable = parentTr.find("td").length;

                        texto = "<td colspan=\'"+colsTable+"\'>"+texto+"</td>";
                        texto = "<tr class=\'tr-detalhes\' style=\'display:none;background: #DBD9D9;\'>"+texto+"</tr>";

                        parentTr.after(texto);
                        $(".tr-detalhes").fadeIn();
                      }else{
                        $(".tr-detalhes-show").removeClass("tr-detalhes-show");
                      }';

$scriptVerDetalhes = $this->Jquery->get('.ver-detalhes')->event('click',$scriptVerDetalhes);

$scriptAlterarStatus = 'var objData = $(this).data();
                             var dados = {id: objData.id, status: $(this).val()}
                             alterarRepasse("'.\Cake\Routing\Router::url(['action' => 'alterarStatus']).'", dados);';

$scriptAlterarStatus = $this->Jquery->get("select[name*='status_repasse']")->event('change',$scriptAlterarStatus);

$scriptAlterarEquipe = 'var objData = $(this).data();
                             var dados = {id: objData.id, equipe: $(this).val()}
                             alterarRepasse("'.\Cake\Routing\Router::url(['action' => 'alterarEquipe']).'", dados);';

$scriptAlterarEquipe = $this->Jquery->get("select[name*='equipe']")->event('change',$scriptAlterarEquipe);

$scriptAlterarResponsavel = 'var objData = $(this).data();
                             var dados = {id: objData.id, funcionario: $(this).val()}
                             alterarRepasse("'.\Cake\Routing\Router::url(['action' => 'alterarResponsavel']).'", dados);';

$scriptAlterarResponsavel = $this->Jquery->get("select[name*='funcionario']")->event('change',$scriptAlterarResponsavel);


$scriptPegarRepasse = 'var objData = $(this).data();
                           var dados = {id: objData.id, funcionario: "atribuir-logado"}
                           alterarRepasse("'.\Cake\Routing\Router::url(['action' => 'alterarResponsavel']).'", dados);';
$scriptPegarRepasse = $this->Jquery->get(".pegar-repasse")->event('click',$scriptPegarRepasse);

$scripts = $this->Jquery->domReady($scriptVerDetalhes.$scriptAlterarStatus.$scriptAlterarResponsavel.$scriptAlterarEquipe.$scriptPegarRepasse);

echo $this->Html->scriptBlock($scripts);

if(!$this->request->is('ajax')):
    echo $this->JqueryUI->getScript();

    $atributos = array (
        'changeMonth' => true,
        'changeYear' => true,
        'numberOfMonths' => 2,
        'maxDate' => 0,
        'showButtonPanel' => true
    );

    $atributos ['onClose'] = 'function( selectedDate ) {
                                 $( "#data-fim" ).datepicker( "option", "minDate", selectedDate );
                             }';

    $atributosDate ['#data-inicio'] = $atributos;

    $atributos ['onClose'] = 'function( selectedDate ) {
                                $( "#data-inicio" ).datepicker( "option", "maxDate", selectedDate );
                             }';

    $atributosDate ['#data-fim'] = $atributos;

    $datePicker = $this->JqueryUI->datePicker ( array (
        '#data-inicio',
        '#data-fim'
    ), $atributosDate );

    $scriptRefresh = 'var contTempo = 0;
                      setInterval(function(){
                          if($("#atualizar-automaticamente").is(":checked")){
                              contTempo++;

                              var tempoRestante = (60 - contTempo);
                              if(tempoRestante > 0)
                                  $("#tempoRefresh").html(tempoRestante);

                              if(contTempo >= 60 && !$("#repasse-container").is(":hover")){
                                  $("#elementoMsg").remove();

                                  contTempo = 0;
                                  atulizacaoTemporaria();
                              }else if($("#repasse-container").is(":hover") && contTempo == 60){
                                  var elementoMsg = $("#tempoRefresh").html(tempoRestante).parent();

                                  if($("#elementoMsg").length == 0)
                                    elementoMsg.after("<span id=\'elementoMsg\'>'.__("ATENÇÃO: A atualização ocorrerá quando o cursor do mouse não estiver sobre a tabela").'</span>");
                              }
                          }
                      }, 1000);

                      function atulizacaoTemporaria(){
                        var formSerialize = $("#FormConsultaRepasse").serialize();
                        var link = "'.\Cake\Routing\Router::url().'?page="+$(".pagination .active a").text();
                        $.post(link, formSerialize, function(data) {
                            $("#repasse-container").html(data);
                        });
                      }';

    $scripts = $this->Jquery->domReady($datePicker.$scriptRefresh);

    echo $this->Html->scriptBlock($scripts);

$optionsStatus = [
                1 =>__('Não Finalizado'), 'Não Atendido' =>__('Não Atendido'), 'Em Atendimento' =>__('Em Atendimento'),
                'Finalizado' =>__('Finalizado'), 0 =>__('Todos')
            ];

?>

<div class="ecmPromocao col-md-12">
    <?= $this->Form->create($repasse, ['id'=>'FormConsultaRepasse', 'url' => ['controller' => 'EcmRepasse', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('status_pesquisa',['options'=>$optionsStatus]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('responsavel',['label'=> __('Responsável'), 'options' => $listaFuncionarios, 'empty' => __('Todos')]) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('data_inicio', ['label' => __('Data de Inicio'), 'type'=>'text']) ?>
            </div>
            <div class="col-xs-12 col-md-3">
                <?= $this->Form->input('data_fim', ['label' => __('Data de Fim'),'type'=>'text']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-4">
                <?= $this->Form->input('ecm_repasse_origem_id', ['options'=>$ecmRepasseOrigem, 'label'=>'Origem']) ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <?= $this->Form->input('ecm_repasse_categorias_id', ['options'=>$ecmRepasseCategorias, 'label'=>'Categoria']) ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <?= $this->Form->input('ecm_alternative_host_id', ['options'=>$ecmAlternativeHost, 'label'=>'Empresa']) ?>
            </div>
        </div>

        <div class="row text-right">
            <div class="col-xs-12 col-md-12">
                <?= $this->Form->button(__('Buscar')); ?>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </fieldset>


    <h3><?= __('Lista de Repasses') ?></h3>

    <?= $this->Form->input('atualizar-automaticamente', ['label' => __('Atualizar consulta de repasses automaticamente a cada 1 minuto?'),
        'type' => 'checkbox', 'checked' => 'checked']) ?>
    <div><?= __('Atualização em ')?><span id="tempoRefresh">60</span> <?= __('segundos')?></div>

    <div id="repasse-container">
<?php endif; ?>

    <table cellpadding="0" cellspacing="0" class="table">
        <thead>
            <tr>
                <th class="col-lg-1"><?= __('id') ?></th>
                <th class="col-lg-3"><?= __('Atendente') ?></th>
                <th class="col-lg-1 ordenacao"><?= $this->Paginator->sort('data_registro', __('Data')) ?></th>
                <th class="col-lg-1 ordenacao"><?= $this->Paginator->sort('status') ?></th>
                <th class="col-lg-1"><?= __('Categoria') ?></th>
                <th class="col-lg-1"><?= __('Cliente') ?></th>
                <th class="col-lg-1"><?= __('Equipe') ?></th>
                <th class="col-lg-1"><?= __('Empresa') ?></th>
                <th class="col-xs-1" style="width: 3%"><?= __('Ação') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmRepasse as $ecmRepasse):?>

            <?php

                $text = '<strong>Status: </strong>';

                if($ecmRepasse->status == \Repasse\Model\Entity\EcmRepasse::STATUS_EM_ATENDIMENTO){
                    $text .= __('Repasse em atendimento desde '). h($ecmRepasse->data_modificacao);
                }elseif($ecmRepasse->status == \Repasse\Model\Entity\EcmRepasse::STATUS_FINALIZADO){
                    $text .= __('Repasse atendido em '). h($ecmRepasse->data_modificacao);
                }else{
                    $text .= __('Repasse ainda não atendido');
                }

                $iconeAtender = $this->Html->image('addgreen.png',
                    ['class' => 'pegar-repasse', 'style' => 'cursor:pointer;', 'data-id' => $ecmRepasse->id,'title' => __('Atender Repasse') ]);

                if($repasseEmAtendimento){
                    $iconeAtender = $this->Html->image('addgray.png', ['title' => __('Finalize o suporte')]);
                }

                $corTr = '';
                if($idUsuario == $ecmRepasse->mdl_user_id){
                    $corTr = 'class="active"';
                    $iconeAtender = $this->Html->image('tick_green_big.png');
                }

                $cliente = '';
                if(!empty($ecmRepasse->MdlUserCliente)){
                    $text .= '<br/><br/><h5>Perfil do cliente</h5>';
                    $text .= '<b>Nome:</b> ' . $ecmRepasse->MdlUserCliente->firstname . ' ' . $ecmRepasse->MdlUserCliente->lastname;
                    $text .= '<br/><b>Chave:</b> ' . (isset($ecmRepasse->MdlUserCliente->idnumber) ? $ecmRepasse->MdlUserCliente->idnumber : $ecmRepasse->MdlUserCliente->username);

                     $cliente = '<b>Chave:</b> <br />' . (isset($ecmRepasse->MdlUserCliente->idnumber) ? $ecmRepasse->MdlUserCliente->idnumber : $ecmRepasse->MdlUserCliente->username);


                    $text .= '<br/><b>E-mail:</b> ' . $ecmRepasse->MdlUserCliente->email;
                    $text .= '<br/><b>Telefone:</b> ' . $ecmRepasse->MdlUserCliente->phone1;

                    if(!empty($ecmRepasse->MdlUserCliente->phone2))
                        $text .= '<br/><b>Telefone 2:</b> ' . $ecmRepasse->MdlUserCliente->phone2;
                }

                if(isset($ecmRepasse->ecm_repasse_origem))
                    $text .= '<br/><br/><b>Origem:</b> ' . $ecmRepasse->ecm_repasse_origem->origem;
                if(isset($ecmRepasse->ecm_repasse_categoria))
                    $text .= '<br/><b>Categoria:</b> ' . $ecmRepasse->ecm_repasse_categoria->categoria;

                if(!empty($ecmRepasse->observacao))
                    $text .= '<br/><br/><b>Observação:</b> ' . $ecmRepasse->observacao;

                if(!empty($ecmRepasse->corpo_email))
                    $text .= '<br/><br/><b>Informações:</b> ' . $ecmRepasse->corpo_email;

                $optionFuncionario = '';
                if(!is_null($ecmRepasse->mdl_user)) {
                    $optionFuncionario = $ecmRepasse->mdl_user->firstname . ' ' . $ecmRepasse->mdl_user->lastname;
                    if($idUsuario != $ecmRepasse->mdl_user_id){
                        $iconeAtender = $this->Html->image('tick_gray_big.png');
                    }
                }

                $iconeAtender .= $this->Html->image('detalhar.png', ['class' => 'ver-detalhes', 'title' => __('Ver Detalhes'), 'width'=>'16px']);
                $iconeAtender .= $this->Html->tag('div', $text, ['style' => 'display:none;', 'class' => 'ver-detalhes-texto']);

                if($permissaoAlterarResponsavel) {
                    $optionFuncionario = $this->Form->input('funcionario', ['label' => '',
                        'options' => $listaFuncionarios,
                        'value' => $ecmRepasse->mdl_user_id,
                        'data-id' => $ecmRepasse->id,
                        'empty' => __('Sem Responsável')]);
                }

                $selectStatus = $ecmRepasse->status;
                if($permissaoAlterarStatus) {
                    $selectStatus = $this->Form->input('status_repasse', ['label' => false,
                        'options' => ['Em Atendimento' => 'Em Atendimento',
                            'Finalizado' => 'Finalizado',
                            'Não Atendido' => 'Não Atendido'
                        ],
                        'data-id' => $ecmRepasse->id,
                        'value' => $ecmRepasse->status]);
                }

                $selectEquipe = $this->Form->input('equipe', ['label' => false,
                        'options' => [ 'QiSat' => 'QiSat', 'Pré 1' => 'Pré 1', 'Pré 2' => 'Pré 2', 'Pré 3' => 'Pré 3'],
                        'data-id' => $ecmRepasse->id,
                        'value' => $ecmRepasse->equipe]);

    
                $iconeAtender .= $this->Html->image('edit.gif', ['class' => 'edit', 'title' => __('Editar'), 'data-id' => $ecmRepasse->id, 'data-visible' => 'show' ]);                        

            ?>
            <tr <?=$corTr?>>
                 <td><?= $ecmRepasse->id ?></td>
                <td><?= $optionFuncionario ?></td>
                <td>

                     <?= $this->Form->hidden('obs-'.$ecmRepasse->id, ['value' => $ecmRepasse->observacao, 'id' => 'obs-'.$ecmRepasse->id, 'disabled' => true ]); ?>

                     <?= $this->Form->hidden('userid-'.$ecmRepasse->id, ['value' => $ecmRepasse->mdl_user_cliente_id, 'id' => 'userid-'.$ecmRepasse->id, 'disabled' => true ]); ?>

                    <strong>Recebido: </strong>
                    <?= h($ecmRepasse->data_registro) ?>

                    <?php if($ecmRepasse->data_modificacao):?>
                        <strong>Modificado:</strong>
                        <?= h($ecmRepasse->data_modificacao) ?>
                    <?php endif;?>
                    
                </td>
                <td><?= $selectStatus ?></td>
                <td>
                    <?php if($ecmRepasse->ecm_repasse_categorias_id):?>
                        <strong>Categoria: </strong>
                        <br />
                        <?= $ecmRepasseCategorias[$ecmRepasse->ecm_repasse_categorias_id] ?>
                    <?php endif;?>
                    <br />
                    <?php if($ecmRepasse->ecm_repasse_origem_id):?>
                        <strong>Origem: </strong>
                        <br />
                        <?= $ecmRepasseOrigem[$ecmRepasse->ecm_repasse_origem_id] ?>
                    <?php endif;?>
                </td>
                <td> <?= $cliente ?></td>
                <td> <?= $selectEquipe ?> </td>
                <td> <?= $ecmAlternativeHost[$ecmRepasse->ecm_alternative_host_id] ?> </td>
                <td><?= $iconeAtender ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>

    <script>
        <?= $this->Jquery->domReady($this->Paginator->ajaxPagination(
                '.pagination a',
                '#repasse-container',
                '#FormConsultaRepasse',
            \App\View\Helper\PaginatorHelper::METHOD_POST
            ));
        ?>

        <?= $this->Jquery->domReady($this->Paginator->ajaxPagination(
            '.ordenacao a',
            '#repasse-container',
            '#FormConsultaRepasse',
            \App\View\Helper\PaginatorHelper::METHOD_POST
        ));?>

    </script>
<?php if(!$this->request->is('ajax')):?>
    </div>
</div>
<?php endif;?>

<script>

    var update = $("#atualizar-automaticamente").prop( "checked");

    $(document).on('click', 'button[name*="salve-"]' ,function() {
        var id = $(this).data('id');
        var obs = $('#observacao-'+id).val();
        var userid = $('#select-usuario-'+id).val();

        if(obs || userid){
            $.post( "/repasse/ecm-repasse/alterar-dados", { id : id, userid : userid, obs: obs}, function(data){
                mensagem = data.retorno.mensagem;
                bootbox.alert(mensagem);
                if(data.retorno.sucesso){
                    $(".edit-"+id).hide();
                    document.location.reload(true);
                }
            }, "json");
        }
    });

    $(document).on('keyup', 'input[name*="buscar_usuario"]' ,function() {
    
                var delay = (function(){
                  var timer = 0;
                  return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                  };
                })();
                var val = $(this).val(), el = $(this), id = $(this).data('id'), notId = new Array(), cont = 0;
                var elRetorno = $("#select-usuario-"+id);

                if(val && val.length >= 3){
                    delay(function(){

                        $.post("/usuario/lista-usuario-json", {nome : val }, function( data ) {

                                    var options = '<option value=""> Selecione </option>';
                                    data = JSON.parse(data);

                                    data.forEach(function(entry) {
                                        options += '<option value="'+entry.id+'">'+entry.firstname+' '+entry.lastname+' - '+entry.idnumber+'</option>';
                                    });
                                    elRetorno.html(options);
                                });

                    }, 1500 );
                }
    });

    $('.edit').click(function(){
        var id = $(this).data('id'), visible = $(this).data('visible');
        var obs = $("#obs-"+id).val();
        var userid = $("#userid-"+id).val();

        $('.EditDados:visible').hide();

        userid = (userid == '') ? '<div class="row"><div class="col-xs-5 col-md-5"><label for="buscar-usuario-'+id+'">Buscar Usuário</label><input name="buscar_usuario" id="buscar-usuario-'+id+'" type="text" data-id="'+id+'" title="Busca pode ser por Chave Sem o Digito, CPF ou CNPJ, Email ou Nome" ></div><div class="col-xs-5 col-md-5"><label for="select-usuario-'+id+'">Definir o Cliente</label><select name="select_usuario" id="select-usuario-'+id+'" data-id="'+id+'" ><option value="">Selecione</option></select></div></div>' : '';


        var l = $(this).parents('tr').find('td').length;

        $("#atualizar-automaticamente:checked").prop( "checked", false );

        if($(".edit-"+id).length){
            if(visible == 'show'){
                $(".edit-"+id).show();
                $(this).data('visible', 'hide');
            }else{
                $(".edit-"+id).hide();
                $(this).data('visible', 'show');
                if(update)
                    $("#atualizar-automaticamente:checked").prop( "checked", true );
            }
        }else{
            var html = '<tr class="edit-'+id+' EditDados" style="background: #DBD9D9;" ><td colspan="'+l+'" ><div class="row"><div class="col-xs-10 col-md-10"><div class="input textarea"><label for="observacao">Observação</label><textarea name="observacao" rows="5" maxlength="250" id="observacao-'+id+'" data-id="'+id+'">'+obs+'</textarea></div></div><div class="col-xs-12 col-md-1"><button name="salve-'+id+'" data-id="'+id+'" >Salvar</button></div></div>'+userid+'</td></tr>'; 
            $(this).parents('tr').after(html);
        }
    });

    function alterarRepasse(link, dados){
        var mensagem = "";
        $.ajaxSetup({async: false});
        $.post( link, dados, function(data){
                mensagem = data.retorno.mensagem;

                var formSerialize = $("#FormConsultaRepasse").serialize();
                var link = "<?=\Cake\Routing\Router::url()?>?page="+$(".pagination .active a").text();
                $.post(link, formSerialize, function(data) {
                    $("#repasse-container").html(data);
                });
            }, "json");
        $.ajaxSetup({async: true});
        setTimeout(function(){ bootbox.alert(mensagem); }, 400);
    }
</script>