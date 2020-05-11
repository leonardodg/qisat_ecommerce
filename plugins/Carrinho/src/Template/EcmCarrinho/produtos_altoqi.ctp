<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/clipboard.min.js') ?>
<?= $this->Html->script('/webroot/js/jquery-validation/jquery.validate.min.js') ?>

<?= $this->Form->create('NULL', ['name' => 'formAltoQi']) ?>

<div class="row"> 
  <div class="col-md-12"> 
      <?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='/carrinho/listaprodutos'" ]) ?>
  </div>
</div>

<div class="row"> 
  <div class="ecmCarrinho col-md-12"> 
  	
  	    <h3><?= __('Venda Produto AltoQi') ?></h3>
        <?= $this->Form->hidden("itens_json", [ 'value' => "[]" ]) ?>
        <?= $this->Form->hidden("linhas_json", [ 'value' => "[]" ]) ?>
        <?= $this->Form->hidden("modulos_json", [ 'value' => "[]" ]) ?>
        <?= $this->Form->hidden("calcular", [ 'value' => 1 ]) ?>

        <fieldset>
            <legend><?= __('Filtro do Produto') ?></legend>

            <div class="row">
              <div class="col-md-3">
                <label for="tabela">Tabela de Preço</label>
                <?= $this->Form->select('tabela', ['CLI', 'CORP', 'EDU', 'PRE', 'STD'], ['empty' => 'Selecione valor de Tabela', 'default' => 3, 'disabled' => true]); ?>
              </div>

                <div class="col-md-3"></div>

                <div class="col-md-3">
                    <label for="tabela">Código Tw</label>
                    <?= $this->Form->input('decript_tw', ['label' => false]); ?>
                </div>
            </div>

            <div class="row">
              <div class="col-md-10">
                <label for="app">Observação</label>
                <?= $this->Form->textarea('obs', ['rows' => '5', 'cols' => '5']); ?>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-3">
                <label for="produto">Tecnologia</label>
                <?=  $this->Form->select('produto', ['ebv' => 'EBERICK', 'qib' =>'QIBUILDER', 'mod' =>'MÓDULO', 'ebvqib' =>'EBERICK + QIBUILDER'], ['empty' => 'Selecione o Produto' , 'required' => true ]); ?>
              </div>
                <div class="col-md-2">
                    <label for="edicao">Edição</label>
                    <?=  $this->Form->select('edicao', ['18' => '2018', '19' =>'2019', '20' =>'2020'], ['empty' => 'Selecione o ano' , 'disabled' => true ]); ?>
                </div>
              <div class="col-md-2">
                <label for="app">Aplicação</label>
                <?=  $this->Form->select('app', ['LITE','FLEX','BASIC','PRO','PLENA','MODULO'], ['empty' => 'Selecione a Aplicação', 'disabled' => true ]); ?>
              </div>
              <div class="col-md-2">
                <label for="licenca">Licença</label>
                <?=  $this->Form->select('licenca', ['LTEMP' => 'LTEMP','VITALÍCIA' => 'VITALÍCIA', 'LANUAL' => 'LANUAL'], ['empty' => 'Selecione a Licença',  'disabled' => true ]); ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <label for="conexao">Conexão</label>
                <?= $this->Form->select('conexao', [ 0 => 'MONO', 1 => 'REDE'], ['empty' => 'Selecione a Conexão', 'default' => 0 ]); ?>
              </div>
              <div class="col-md-1"> 
                <div id="rede-qtd" style="display:none" >
                  <label for="rede">Quantidade</label>
                  <?= $this->Form->input('rede', ['label' => false]); ?>
                </div>
              </div>

              <div class="col-md-3">
                <label for="especiais">Tipo de Aquisição</label>
                <?=  $this->Form->select('especiais', [ 'ativa' => 'ATIVAÇÃO', 'renova' => 'RENOVAÇÃO', 'update' => 'UPGRADE'], ['empty' => 'Selecione o TIPO' ]); ?>
              </div>

              <div class="col-md-3">
                <label for="ativacao">Ativação do Protetor </label>
                <?=  $this->Form->select('ativacao', [ 'usb' => 'NOVO USB', 'remota' => 'REMOTA (Hardlock Existente)', 'online' => 'ONLINE'], ['empty' => 'Selecione o Protetor' ]); ?>
              </div>

              <div class="col-md-1">
                <div id="select-tempo-renova" style="display:none">
                    <label for="tempo-renova" title="Tempo que possui o produto"> Tempo </label>
                    <?=  $this->Form->number('tempo-renova', [ 'min'=> 1, 'max'=> 4, 'disabled' => true, 'placeholder' => "Anos" ]); ?>
                </div>
              </div>
          </div>

            <div class="form-group row">
              <div class="col-md-3">
                <div id="select-modulos" style="display:none">
                  <label for="modulos">Módulos</label>

                    <div id="select_modulos_ltemp" style="display:none">
                      <?=  $this->Form->select('modulos_ltemp', [ 'Light' => 'LIGHT', 'Essencial' => 'ESSENCIAL', 'Top' => 'TOP'], ['empty' => 'Selecione o Módulos', 'class' => 'modulos' ]); ?>
                    </div>

                    <div id="select_modulos_vital" style="display:none">
                         <?= $this->Form->input('Tipo I', [ 'name' => 'tipo', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'select_modulos_vital modulos', 'value' => 'TIPO-I']); ?>

                         <?= $this->Form->input('Tipo II', [ 'name' => 'tipo', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'select_modulos_vital modulos', 'value' => 'TIPO-II']); ?>

                         <?= $this->Form->input('Tipo III', [ 'name' => 'tipo', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'select_modulos_vital modulos', 'value' => 'TIPO-III']); ?>

                         <?= $this->Form->input('Tipo IV', [ 'name' => 'tipo', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'select_modulos_vital modulos', 'value' => 'TIPO-IV']); ?>
                  </div>
                </div> 

                <div id="select-linha" style="display:none">
                   <h5><?= __('Linhas QiBuilder LTEMP') ?></h5>

                   <?= $this->Form->input('Linha Hidráulica', [ 'name' => 'linha', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'checkbox-linha', 'value' => 'hidraulica']); ?>

                   <?= $this->Form->input('Linha Elétrica', [ 'name' => 'linha', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'checkbox-linha', 'value' => 'eletrica' ]); ?>

                   <?= $this->Form->input('Sistemas Preventivos', [ 'name' => 'linha', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'checkbox-linha', 'value' => 'preventivos' ]); ?>

                   <?= $this->Form->input('Infra-estrutura predial', [ 'name' => 'linha', 'hiddenField' => false, 'type' => 'checkbox', 'class' => 'checkbox-linha', 'value' => 'predial' ]); ?>
                </div> 
              </div>

                <div class="col-md-3">
                    <div id="select-familia" style="display:none">
                        <label for="familia">Familia</label>

                        <div id="select_familia">
                            <?=  $this->Form->select('familia', [ '15 - CPH' => 'HIDRÁULICA', '16 - CPE' => 'ELÉTRICA', '17 - CPEH' => 'ELÉTRICA + HIDRÁULICA'], ['empty' => 'Selecione a Familia' ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="up-more" style="display:none">
                <div class="row">
                    <div class="col-md-3 select-up-vs">
                        <label for="up-vs">Mudança de Versão</label>
                        <?=  $this->Form->select('up-vs', [ 'Manter Versão','DE 2017 PARA 2018', 'DE Linha V4 PARA 2018', 'DE V9 PARA 2018','De V10 para 2018' ], ['empty' => 'Selecione a VERSÃO','disabled' => true ]); ?>
                    </div>

                    <div class="col-md-3 select-up-app">
                        <label for="up-app">Mudança de Aplicação</label>
                        <?=  $this->Form->select('up-app', [ 'Manter Aplicação' ,'FLEX para BASIC', 'FLEX para PRO', 'FLEX para PLENA', 'LITE para BASIC', 'LITE para PRO', 'LITE para PLENA', 'BASIC para PRO','BASIC para PLENA','PRO para PLENA'], ['empty' => 'Selecione a APLICAÇÂO', 'disabled' => true ]); ?>
                    </div>

                    <div class="col-md-3 select-up-mod">
                        <label for="up-mod">Mudança de Módulos</label>
                        <?=  $this->Form->select('up-mod', [ 'Manter Módulos', 'LIGHT para ESSENTIAL','LIGHT para TOP','ESSENTIAL para TOP'], ['empty' => 'Selecione o MÓDULOS','disabled' => true ]); ?>
                    </div>

                    <div class="col-md-1">
                        <div id="select-tempo-up" style="display:none">
                            <label for="tempo-up" title="Tempo ativo desta licença"> Tempo </label>
                            <?=  $this->Form->number('tempo-up', [ 'min'=> 1, 'max'=> 12, 'disabled' => true, 'placeholder' => "Mêses" ]); ?>
                        </div>
                    </div>
                </div>
            </div>

          <div id="checkbox-produtos" class="form-group row" style="display:none" >
              <div class="col-md-12">
                <h5><?= __('Produtos AltoQi') ?></h5>

                <?php foreach ($produtos as $produto): ?>
                        <?php echo $this->Form->input($produto->nome, [ 'name' => 'ITEM-'.$produto->codigo, 'value' => $produto->codigo, 'hiddenField' => false, 'data-grupo' => $produto->grupo, 'type' => 'checkbox', 'class' => 'checkbox-produtos', 'label' => [ 'class' =>'checkbox-inline'], 'templates' => [ 'inputContainer' => '<div class="input {{type}} div-checkbox-produtos" data-grupo="'.$produto->grupo.'">{{content}}</div>']]); ?>
                <?php endforeach; ?>
              </div>
          </div>

           <div id="checkbox-modulos" class="row" style="display:none" >
              <div class="col-md-4">
                <h5><?= __('Módulos Eberick') ?></h5>
                <?php for ($i = 0; $i <= 12; $i++): ?>
                        <?php echo $this->Form->input($modulos[$i]->nome, [ 'name' => 'MOD-'.$modulos[$i]->codigo, 'value' => $modulos[$i]->codigo, 'hiddenField' => false, 'data-mod' => $modulos[$i]->grupo, 'data-tipo' => $modulos[$i]->tipo, 'type' => 'checkbox', 'class' => 'checkbox-modulos' ]); ?>
                <?php endfor; ?>
              </div>

               <div class="col-md-4">
                <?php for ($i = 13; $i <= 26; $i++): ?>
                        <?php echo $this->Form->input($modulos[$i]->nome, [ 'name' => 'MOD-'.$modulos[$i]->codigo, 'value' => $modulos[$i]->codigo, 'hiddenField' => false, 'data-mod' => $modulos[$i]->grupo, 'data-tipo' => $modulos[$i]->tipo, 'type' => 'checkbox', 'class' => 'checkbox-modulos' ]); ?>
                <?php endfor; ?>
              </div>

               <div class="col-md-4">
                <?php for ($i = 27; $i <= count($modulos)-1; $i++): ?>
                      <?php echo $this->Form->input($modulos[$i]->nome, [ 'name' => 'MOD-'.$modulos[$i]->codigo, 'value' => $modulos[$i]->codigo, 'hiddenField' => false, 'data-mod' => $modulos[$i]->grupo, 'data-tipo' => $modulos[$i]->tipo, 'type' => 'checkbox', 'class' => 'checkbox-modulos' ]); ?>
                <?php endfor; ?>
              </div>
          </div>

           <div class="row right">
              <div class="col-md-12">
                <label for="tabela">&nbsp</label>
                  <?= $this->Form->input('Incluir Valor do Frete', [ 'name' => 'frete', 'hiddenField' => false, 'type' => 'checkbox', 'value' => 1]); ?>
              </div>

              <div class="col-md-12">
                <?= $this->Form->button(__("Calcular"), ['name' => 'calc', "type" => "button"]) ?>
                <?= $this->Form->button(__("Adicionar"), ['name' => 'adicionar', "type" => "button" ]) ?>
               </div> 
          </div>        
      </fieldset>
  </div>	
</div>  

<div class="row"> 
  <div class="col-md-12"> 
    <div id="tabelaDescricao" class="related" style="display:none" >
      <h3><?= __('Descrição dos Produtos') ?></h3>
      <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveCompraAltoQi">
        <thead>
          <tr>
              <th><?= __('Código TW') ?></th>
              <th><?= __('Descrição') ?></th>
              <th><?= __('Valores') ?></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->Form->end(); ?>
<script>

   $( "input[name='decript_tw']" ).focusout(function () {
        var selected = $(this).val();
        $.post("", { decript_tw: selected }, function( res ) {
            if(res){
                if(res.aplicacao == 'MODULO'){
                    $( "select[name='produto']" ).val('mod');
                }else{
                    $( "select[name='produto']" ).val(res.codigo.substr(0, 3).toLowerCase());
                }
                $( "select[name='produto']" ).change();
                if(res.edicao){
                    $( "select[name='edicao']" ).val(res.edicao-2000);
                }else{
                    $( "select[name='edicao']" ).val(18);
                }
                $( "select[name='edicao']" ).change();
                $( "select[name='app'] option").each(function() {
                    if($(this).text() == res.aplicacao)
                        $(this).prop("selected", true);
                });
                $( "select[name='app']" ).change();
                $( "select[name='licenca']" ).val(res.licenca == "INDET" ? "VITALÍCIA" : res.licenca);
                $( "select[name='licenca']" ).change();
                $( "select[name='conexao']" ).val(res.conexao == "REDE" ? 1 : 0);
                $( "select[name='conexao']" ).change();
                $( "select[name='ativacao']" ).val(res.ativacao.toLowerCase());
                $( "select[name='ativacao']" ).change();
                $( "select[name='especiais'] option").each(function() {
                    if($(this).text() == res.especiais)
                        $(this).prop("selected", true);
                });
                $( "select[name='especiais']" ).change();
                //modulos_ltemp (Módulos)
                var modulos_linha = parseInt(res.modulos_linha.substr(0,2));
                if(modulos_linha >= 2 && modulos_linha <= 4){
                    $( "select[name='modulos_ltemp'] option").each(function() {
                        if($(this).text() == res.modulos_linha.substr(5))
                            $(this).prop("selected", true);
                    });
                } else if(modulos_linha >= 8 && modulos_linha <= 14){
                    $( "select[name='modulos_ltemp'] option:eq(1)").prop("selected", true);
                } else if(modulos_linha >= 15 && modulos_linha <= 18){
                    $( "select[name='modulos_ltemp'] option:eq(2)").prop("selected", true);
                } else if(modulos_linha == 19){
                    $( "select[name='modulos_ltemp'] option:eq(3)").prop("selected", true);
                }
                $( "select[name='modulos_ltemp']" ).change();

                if(modulos_linha >= 15 && modulos_linha <= 18){
                    $( "select[name='familia']" ).val(res.modulos_linha);
                    $( "select[name='familia']" ).change();
                }

                if(res.especiais == 'UPGRADE'){
                    var mudanca_de_aplicacao;
                    if(res.licenca == 'LTEMP'){
                        if(res.mudanca_de_aplicacao.length == 4){
                            mudanca_de_aplicacao = res.mudanca_de_aplicacao.match(/([A-Z]{1})/g);
                        }else{
                            mudanca_de_aplicacao = res.mudanca_de_aplicacao.match(/(PL|[A-Z]{1})/g);
                        }
                        var upmod = [ '', 'LE', 'LT', 'ET' ];
                        //up-mod
                        var mod = upmod.indexOf(mudanca_de_aplicacao[1] + res.modulos_linha.substr(5,1));
                        $( "select[name='up-mod']" ).val(mod == -1 ? 0 : mod);
                        $( "select[name='up-mod']" ).change();
                    }else{
                        mudanca_de_aplicacao = res.mudanca_de_aplicacao.match(/([0-9]+|[B-U]+)/g);
                        var upvs  = [ '', '17', '04', '09', '10', '18' ];
                        //up-vs
                        var up = upvs.indexOf(mudanca_de_aplicacao[1]);
                        $( "select[name='up-vs']" ).val(up == -1 ? 0 : up);
                        $( "select[name='up-vs']" ).change();
                    }

                    var upapp = [ '', 'LB', 'LP', 'LPL', 'BP', 'BPL', 'PPL' ];
                    //up-app
                    var app = upapp.indexOf(mudanca_de_aplicacao[0] + (res.aplicacao=='PLENA' ? 'PL' : res.aplicacao.substr(0, 1)));
                    $( "select[name='up-app']" ).val(app == -1 ? 0 : app);
                    $( "select[name='up-app']" ).change();
                }

                $( "input[name='frete']" ).prop('checked', res.frete != 0);
            }

        }, "json");
    });

  var modulos = <?= $modulos_json ?>;
  var itens = <?= $produtos_json ?>;
  var result;

  function addJson(elem, value){
      var stringData, arrayData, el = $('input[name="'+elem+'"]');
      stringData = el.val();
      arrayData = JSON.parse(stringData);
      if(arrayData.indexOf(value) < 0){
        arrayData.push(value);
        stringData = JSON.stringify(arrayData);
        el.val(stringData);
      }
  };

  function delJson(elem, value){
      var stringData, arrayData, el = $('input[name="'+elem+'"]'), aux;
      stringData = el.val();
      arrayData = JSON.parse(stringData);
      if(arrayData.indexOf(value)>=0){
        aux = arrayData.splice(arrayData.indexOf(value), 1);
        stringData = JSON.stringify(arrayData);
        el.val(stringData);
      }
  };

  function reset(nivel){

      if(!nivel){
          $("select[name='produto']").val("");
          $("select[name='produto'] option:selected").prop("selected", false);
          $("select[name='app'] option:selected").prop("selected", false);
          $("select[name='app']").val("");
          $("select[name='licenca']:enabled").prop("disabled", true);
          $("select[name='licenca'] option:disabled").prop("disabled", false);
          $("select[name='licenca'] option:selected").prop("selected", false);
          $("select[name='licenca']").val("");
      }else if(nivel==1){
          $("select[name='app'] option:selected").prop("selected", false);
          $("select[name='app']").val("");
          $("select[name='licenca']:enabled").prop("disabled", true);
          $("select[name='licenca'] option:disabled").prop("disabled", false);
          $("select[name='licenca'] option:selected").prop("selected", false);
          $("select[name='licenca']").val("");
      }else if(nivel==2){
          $("select[name='licenca']:enabled").prop("disabled", true);
          $("select[name='licenca'] option:disabled").prop("disabled", false);
          $("select[name='licenca'] option:selected").prop("selected", false);
          $("select[name='licenca']").val("");
      }

      $("input.checkbox-produtos[data-grupo!='all'][data-grupo!='alvenaria']").on('change', null);

      $("input[name='tempo-up']").prop('max', 12);
      $("input[name='tempo-up']").prop('placeholder', 'Mêses');

      $("#select-familia").hide();
      $("#select-linha:visible").hide();

      $("input.checkbox-linha:checked").prop("checked", false );
      $("select[name='especiais']").val("");
      $("select[name='especiais'] option:selected").prop("selected", false);
      $("select[name='especiais'] option:disabled").prop("disabled", false);
      $("select[name='up-vs']").val("");
      $("select[name='up-app']").val("");
      $("select[name='up-mod']").val("");
      $("select[name='up-vs'] option:selected").prop("selected", false);
      $("select[name='up-app'] option:selected").prop("selected", false);
      $("select[name='up-mod'] option:selected").prop("selected", false);
      $("select[name='up-vs'] option:disabled").prop("disabled", false);
      $("select[name='up-app'] option:disabled").prop("disabled", false);
      $("select[name='up-mod'] option:disabled").prop("disabled", false);
      $("select[name='up-vs']:enabled").prop('disabled', true);
      $("select[name='up-app']:enabled").prop('disabled', true);
      $("select[name='up-mod']:enabled").prop('disabled', true);

      $("select[name='modulos_ltemp']").val("");
      $("select[name='modulos_ltemp'] option:selected").prop("selected", false);
      $("select[name='modulos_ltemp'] option:disabled").prop("disabled", false);

      $("select[name='ativacao']").val("");
      $("select[name='ativacao'] option:selected").prop("selected", false);
      $("select.modulos").val("");
      $("#select-modulos:visible").hide();
      $("select.modulos option:selected").prop("selected", false);
      $("select.modulos option:disabled").prop("disabled", false);
      $("input.checkbox-modulos:checked").prop("checked", false );
      $("input.checkbox-modulos:disabled").prop('disabled', false );

      $("#select_modulos_vital").hide();
      $("input.select_modulos_vital:checked").prop('checked', false );
      $("input.checkbox-produtos:checked").prop('checked', false );
      $("input.checkbox-produtos:disabled").prop('disabled', false );
      $("select[name='conexao']").val("");
      $("#rede-qtd").hide();
      $("#rede").val("");
      $("#up-more:visible").hide();
      $("#select_modulos_ltemp:visible").hide();

      $('input[name="itens_json"]').val("[]");
      $('input[name="modulos_json"]').val("[]");
      $('input[name="linhas_json"]').val("[]");

      $("input[name^='tempo-']").val("");
      $("#select-tempo-renova:visible").hide();
      $("#select-tempo-up:visible").hide();
      $("input[name^='tempo-']:enabled").prop('disabled', true);
  };

  $("form").validate({
     rules: {
        tabela: "required",
        produto: "required",
        app: "required",
        licenca: "required",
        conexao: "required",
        licenca: "required",
        ativacao: "required",
        especiais: "required",
        modulos_ltemp: "required",
        linha: "required",
         familia: "required"
     },
     messages : {
        tabela: "Selecione a Tabela de Desconto",
        produto: "Especifique o Produto!",
        app: "Selecione a Aplicação",
        licenca: "Especifique o tipo de Licença!",
        conexao: "Selecione a Conexão",
        licenca: "Informe a Linceça",
        ativacao: "Informe a Ativação",
        especiais: "Selecione o Tipo!",
        modulos_ltemp: "Selecione o Modulos LTEMP",
        linha: "Selecione a Linha",
         familia: "Selecione a Familia"
     }
  });

  $("button[name='adicionar']").click(function () {
    var data = result;
    if($("form").valid()){
        if($('input[name="calcular"]').val() == 0){

          data.produto = $('select[name="produto"]').val();
          data.modulos_ltemp = $('select[name="modulos_ltemp"]').val();

          data.edicao = $('select[name="edicao"]').val() ? $('select[name="edicao"]').val() : $('select[name="edicao"] option').last().val();
          data.frete = ($('input[name="frete"]').prop('checked')) ? 1 : 0;
          data.obs = $('textarea[name="obs"]').val();
          data.rede = ($('input[name="rede"]').val()) ? $('input[name="rede"]').val() : 1;

          data['tempo-renova'] = ($('input[name="tempo-renova"]').val()) ? $('input[name="tempo-renova"]').val() : "";
          data['tempo-up'] = ($('input[name="tempo-up"]').val()) ? $('input[name="tempo-up"]').val() : "";
          data['up-vs'] = ($("select[name='up-vs']").val()) ? $("select[name='up-vs']").val() : "";
          data['up-app'] = ($("select[name='up-app']").val()) ? $("select[name='up-app']").val() : "";
          data['up-mod'] = ($("select[name='up-mod']").val()) ? $("select[name='up-mod']").val() : "";

          data.especiais = ($('select[name="especiais"]').val()) ? $('select[name="especiais"]').val() : "";
          data.ativacao = ($('select[name="ativacao"]').val()) ? $('select[name="ativacao"]').val() : "";
          data.licenca = ($('select[name="licenca"]').val()) ? $('select[name="licenca"]').val() : "";

          $.post("", data, function( res ) {
              if(res.sucesso){
                bootbox.alert('Produto Adicionado com Sucesso!');
                location.href='/carrinho/montarcarrinho';
              }else{
                if(res.mensagem)
                  bootbox.alert(res.mensagem);
                else
                  bootbox.alert('Falha ao Adicionar Produto!');
              }
          }, "json");

        }else
          bootbox.alert('Necessário calcular para Adicionar!');
    }else{
        bootbox.alert('Verifique os campos Obrigatórios!');
    }   
  });

  /* Obriga a calcular todas alteração no form */
  $( "form" ).change(function(){
      if($('input[name="calcular"]').val() == 0){
        $('input[name="calcular"]').val(1);
        $('input[name="decript_tw"]').val('');
        $("#tabelaDescricao").hide();
        $("#tabelaDescricao tbody").empty();
      }
  });

  $("button[name='calc']").click(function () {
      var total = 0.00;
      var data = {};
      var rede = ($("#rede").val()) ? $("#rede").val() + 'x ' : '';
      var dataArray = $("form").serializeArray();

      function validup(){
        var vs = $("select[name='up-vs']").val(), 
            app = $("select[name='up-app']").val(), 
            mod = $("select[name='up-mod']").val(),
            result = true;

        $("select[name^='up-']").removeClass('error');
        $(".label-error").remove();

        if($("#up-more").is(":visible")){

          if((( $(".select-up-vs").is(":visible") && vs=='0') && ( $(".select-up-app").is(":visible") && app == '0') && ( $(".select-up-mod").is(":visible") && mod == '0')) || (( $(".select-up-vs").is(":visible") && vs=='0') && ( $(".select-up-app").is(":visible") && app == '0')) || (( $(".select-up-vs").is(":visible") && vs=='0') && ( $(".select-up-mod").is(":visible") && mod == '0')) || (( $(".select-up-app").is(":visible") && app == '0') && ( $(".select-up-app").is(":visible") && mod == '0'))){
              bootbox.alert('Não é possivel manter Versão, Aplicação e/ou Módulos ao selecionar UPGRADE!');
              return false;
          }

          if( $(".select-up-vs").is(":visible") && (vs == '' || !vs)){
              $("select[name='up-vs']").addClass('error');
              $(".select-up-vs").append('<label id="up-vs-error" class="error label-error" for="up-vs">Informe a Mudança de Versão</label>');
              result = false;
          }

          if($(".select-up-app").is(":visible") && (app == '' || !app)){
              $("select[name='up-app']").addClass('error');
              $(".select-up-app").append('<label id="up-app-error" class="error label-error" for="up-app">Informe a Mudança de Aplicação</label>');
              result = false;
          }
          if($(".select-up-mod").is(":visible") && (mod == ''|| !mod)){
              $("select[name='up-mod']").addClass('error');
              $(".select-up-mod").append('<label id="up-mod-error" class="error label-error" for="up-mod">Informe a Mudança de Módulos</label>');
              result = false;
          }
        }

        if(!result) 
          bootbox.alert('Verifique os campos Obrigatórios!');
        return result;
      }

      if($("form").valid()){
        if(validup()){
            dataArray.map(function(field){
                data[field.name] = field.value;
            });

            $("form").find("[disabled='disabled']").each(function( i ) {
                if($( this ).val() != '')
                    data[$( this ).prop('name')] = $( this ).val();
            });

            $('input[name="calcular"]').val(0);
            $("#tabelaDescricao tbody").empty();

            $.post("", data, function( res ) {
                result = res;
                var total = parseFloat(res.valor_total), i = 1, res_mod = res.modulos,
                    frete = (res.frete) ? ' frete: R$ '+accounting.formatNumber(parseFloat(res.frete), 2, ".", ",") : '';

                $("#tabelaDescricao tbody").append('<tr> <td id="codigo_tw-'+i+'" > '+res.codigo_tw+' </td> <td id="descricao-'+i+'" >'+rede+res.descricao+'</td> <td id="valor-'+i+'"> R$ '+accounting.formatNumber(parseFloat(res.valor_unitario), 2, ".", ",")+'</td></tr>');
                $('input[name="decript_tw"]').val(res.codigo_tw);

                if(res_mod && res_mod.length){
                res_mod.map(function(el, i){
                    $("#tabelaDescricao tbody").append('<tr> <td id="codigo_tw-'+i+'" > '+el.codigo_tw+' </td> <td id="descricao-'+i+'" >'+rede+el.descricao+'</td> <td id="valor-'+i+'" style="display: '+(el.valor_unitario===undefined?"none":"block")+';"> R$ '+accounting.formatNumber(parseFloat(el.valor_unitario), 2, ".", ",")+'</td></tr>');
                    });
                }

                $("#tabelaDescricao tbody").append('<tr ><td colspan="3"> <p class="text-right"><strong>TOTAL R$ '+accounting.formatNumber(total, 2, ".", ",")+ frete +' Parcelado: '+res.qtd_parcelas+' x R$'+accounting.formatNumber(res.valor_parcelado, 2, ".", ",")+'</strong></p></td></tr>');

                $("#tabelaDescricao").show();
                $('input[name="calcular"]').val(0); 
            }, "json");
        }
      }else{
        bootbox.alert('Verifique os campos Obrigatórios!');
      }
  });

 /**
  * Selecionar Tecnologia 
  */
  $( "select[name='produto']" ).change(function () {
      var selected = $(this).val();

      reset(1);
      $("#checkbox-modulos").hide();
      $("#select-modulos").hide();
      $("#checkbox-produtos").hide();
      $("select[name='app']").prop("disabled", true);
      $("select[name='licenca']:enabled").prop("disabled", true);

      $("select[name='edicao'] option:selected").prop("selected", false);
      $("select[name='edicao'] option:eq(0)").prop("selected", true);

      $("#select_modulos_ltemp").show();

      $("select[name='app'] option").removeProp("disabled");
      $("select[name='ativacao'] option").removeProp("disabled");
      $("select[name='especiais'] option").removeProp("disabled");

      $("select[name='app'] option:eq(2)").prop("disabled", true);

      if( selected.indexOf('mod') !== -1){
          $("select[name='edicao']:disabled").prop("disabled", false);

          $("#select_modulos_ltemp").hide();

          $("select[name='app']").prop("disabled", true);
          $("select[name='app'] option:eq(6)").prop("selected", true);
          $("select[name='licenca'] option:eq(2)").prop("selected", true);
          $("#checkbox-produtos").hide();
          $("#checkbox-modulos").show();
          $("#select-modulos").show();
          $("#select_modulos_vital").show();

          $("select[name='edicao'] option:eq(1)").prop("selected", true);
          $("select[name='edicao']").attr("disabled", "disabled");
          $("select[name='ativacao'] option:eq(1)").prop("disabled", true);
          $("select[name='ativacao'] option:eq(2)").prop("selected", true);
          $("select[name='especiais'] option:eq(1)").prop("selected", true);
          $("select[name='especiais'] option:eq(2)").prop("disabled", true);
          $("select[name='especiais'] option:eq(3)").prop("disabled", true);
      } else if( selected.indexOf('ebvqib') !== -1){
          //$("select[name='edicao'] option:eq(2)").prop("selected", true);
          //$("select[name='edicao']:enabled").prop("disabled", 'disabled');
          $("select[name='edicao'] option:eq(1)").prop("disabled", "disabled");
          $("select[name='edicao']:disabled").prop("disabled", false);

          $("select[name='app'] option:disabled").prop("disabled", false);
          $("select[name='app'] option:eq(1)").prop("disabled", true);
          $("select[name='app'] option:eq(2)").prop("disabled", false);
          $("select[name='app'] option:eq(6)").prop("disabled", true);
          $("select[name='app']").removeProp("disabled");

          $("select[name='licenca'] option:eq(2)").prop("disabled", true);
          $('select[name="up-vs"] option').each(function(){
              $(this).text($(this).text().replace('2018', '2019'));
          });
          $('select[name="up-vs"]').append($('<option>', {value:5, text:'DE 2018 PARA 2019'}));
      }else if(selected == ''){
        $("select[name='edicao']:enabled").prop("disabled", true);
      }else{
        $("select[name='edicao']:disabled").prop("disabled", false);
      }

      $("select[name='ativacao'] option:eq(3)").prop("disabled", false);
  });

  $( "select[name='edicao']" ).change(function () {
      var selected = $("select[name='produto'] option:selected").val(), edicao = $(this).val();

      if( selected.indexOf('ebv') !== -1){
        $("select[name='app']").prop("disabled", false);
        reset(1);
        $("select[name='app'] option:eq(6)").prop("disabled", true);
        $("#checkbox-modulos").show();
        $(".div-checkbox-produtos[data-grupo!='all']").hide();
        $("select[name='app'] option:eq(1)").prop("disabled", parseInt(edicao) > 18);
      }else if(selected.indexOf('qib') !== -1){
        $("select[name='app']").prop("disabled", false);
        reset(1);

        if(edicao == 18){
            $("#checkbox-produtos").show();
        }else{
            $("#radio-produtos").show();
        }

        $(".div-checkbox-produtos[data-grupo!='all']").show();
        $("select[name='app'] option:eq(1)").prop("disabled", true);
        $("select[name='app'] option:eq(4)").prop("disabled", (edicao.indexOf('18') !== -1));
        $("select[name='app'] option:eq(6)").prop("disabled", true);

        $("#checkbox-modulos").hide();
        $("#select-modulos").hide();

      }else if(selected.indexOf('mod') >= 0){
        $("select[name='app'] option:eq(6)").prop("selected", true);
        $("select[name='licenca'] option:eq(2)").prop("selected", true);
      }else {
        $("#checkbox-modulos").hide();
        $("#select-modulos").hide();
        $("#checkbox-produtos").hide();
        $("select[name='app']").prop("disabled", true);
        $("select[name='licenca']:enabled").prop("disabled", true);
      }

      $('select[name="up-vs"] option:eq(6)').remove();
      if(edicao == 18){
            $("select[name='licenca'] option:eq(3)").prop("disabled", true);
            $('select[name="up-vs"] option').each(function(){
                $(this).text($(this).text().replace('2019', '2018'));
            });
      }else{
        $("select[name='licenca'] option:eq(3)").prop("disabled", false);
        $('select[name="up-vs"] option').each(function(){
            $(this).text($(this).text().replace('2018', '2019'));
        });
        $('select[name="up-vs"]').append($('<option>', {value:5, text:'DE 2018 PARA 2019'}));
      }
      
      $("select[name='ativacao'] option:eq(3)").prop("disabled", edicao == 18);
  });


  $( "select[name='app']" ).change(function () {
        var selected = $(this).val(), produto = $( "select[name='produto']" ).val(),
            edicao = $( "select[name='edicao']" ).val();
        
        $("select[name='licenca']:disabled").prop("disabled", false);
        $("select[name='licenca'] option:selected").prop("selected", false);
        $("select[name='licenca'] option:disabled").prop("disabled", false);
        $("select[name='ativacao'] option:disabled").prop("disabled", false);

      $("select[name='modulos_ltemp'] option:eq(1)").prop("disabled", false);
      if (selected == '0') { //LITE
          $("select[name='licenca'] option:eq(1)").prop("disabled", true);
          $("select[name='licenca'] option:eq(3)").prop("disabled", true);
          $("select[name='licenca'] option:eq(2)").prop("selected", true);
          $("select[name='licenca']:enabled").prop("disabled", true);
          $("select[name='especiais'] option:eq(2)").prop("disabled", true);

          $("#select-modulos").show();
          $("#select_modulos_vital").show();
          $("#select_modulos_ltemp").hide();
      } else if (selected == '1') { // FLEX
      
          $("select[name='ativacao'] option:eq(1)").prop("disabled", true);
          $("select[name='ativacao'] option:eq(2)").prop("disabled", true);
          $("select[name='conexao'] option:eq(2)").prop("disabled", true);
          $("select[name='especiais'] option:eq(1)").prop("disabled", false);
          $("select[name='especiais'] option:eq(3)").prop("disabled", true);
          //addJson('itens_json', 'QIBEDIT');

      } else if (selected == '2') { // BASIC
        $("select[name='licenca']:disabled").prop("disabled", false);
          if (produto.indexOf('qib') >= 0) 
              $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true);

          if(produto == 'qib')
              $("select[name='especiais'] option:eq(3)").prop("disabled", true);
          
      } else if (selected == '') {
          $("select[name='licenca']:enabled").prop("disabled", true);
      }

      if(produto == 'qib'){
          $("#checkbox-produtos:hidden").show();
          if(edicao != '18'){
              $("input[type='checkbox'][name='ITEM-QIBALV']").prop('disabled', true );
              $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true );
          }
      }

      if(produto == 'ebvqib'){
        $("select[name='licenca'] option:gt(1)").prop("disabled", true);
        $("select[name='conexao'] option:eq(2)").prop("disabled", true);
        $("select[name='especiais'] option:eq(2)").prop("disabled", true);
        if (selected == '1'){
          $("select[name='licenca'] option:eq(2)").prop("disabled", false);
          $("select[name='ativacao'] option:eq(1)").prop("disabled", true);
          $("select[name='ativacao'] option:eq(2)").prop("disabled", true);
        }
      }

      if(edicao == '18')
        $("select[name='licenca'] option:eq(3):enabled").prop("disabled", true);

      if(produto == 'ebv')
          $("#checkbox-modulos:hidden").show();
      
  });

  /**
   * Bloquear Modulos Eberick (checkbox's) na licença LANUAL
   * .checkbox-modulos
   */
  $( "select[name='licenca']" ).change(function () {
      var selected = $(this).val();
      var produto  = $("select[name='produto'] option:selected").val();
      var edicao  = $("select[name='edicao'] option:selected").val();
      var app = $( "select[name='app']" ).val();
      var mods_select = modulos.filter(function(mod){ return mod.grupo == 'Essencial' || mod.grupo == 'Light' || mod.grupo == 'Top' });

      reset(3);

      if(produto == 'EBV' && app == '4') // 'PLENA'
          $("select[name='modulos_ltemp'] option:eq(1)").prop("disabled", true);

      if(selected == 'LTEMP'){
          $("select[name='ativacao']").prop("disabled", false);
          $("select[name='especiais']").prop("disabled", false);

          mods_select.map(function(el){ $("input[type='checkbox'][name='MOD-"+el.codigo+"']").prop('disabled', true ) });

         if(produto.indexOf('ebv') !== -1){
             $("input.checkbox-produtos").prop('disabled', true );

            $("input[type='checkbox'][name='MOD-MNEXT']").prop('checked', true );
            $("input[type='checkbox'][name='MOD-MNEXT']").prop('disabled', true );

            addJson('modulos_json', 'MNEXT');

            $("#select-modulos:hidden").show();
            $("#checkbox-modulos:hidden").show();
            //$("#checkbox-produtos:hidden").show();
            $("#select_modulos_ltemp:hidden").show();
            $("#select_modulos_vital:visible").hide();

        }

        if (produto.indexOf('qib') !== -1){
             $("input.checkbox-produtos").prop('disabled', true );

            if(edicao == '18'){
                $("#select-linha:hidden").show();
                $("#select-modulos:visible").hide();
            }

            $("input[type='checkbox'][name='ITEM-QIB']").prop('checked', true );
            $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('checked', true );
            $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', false );
            $("input[type='checkbox'][name='ITEM-QIBALV']").prop('disabled', false );

            addJson('itens_json', 'QIB');
            addJson('itens_json', 'QBNEXT');

            $("#checkbox-produtos:hidden").show();
            $(".div-checkbox-produtos:hidden").show();
            $("#select_modulos_ltemp:visible").hide();
            $("#select_modulos_vital:visible").hide();

              if(app == '2'){ // 'BASIC'
                $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true );
              }

             if(edicao != '18'){
                 $("#select-modulos").show();
                 $("#select_modulos_ltemp").show();

                 $("input[type='checkbox'][name='ITEM-QIB']").prop('disabled', true );
                 $("input[type='checkbox'][name='ITEM-QIBALV']").prop('disabled', true );
                 $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true );
                 $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('disabled', true );
             }
        }
      }else/* if(selected == 'VITALÍCIA')*/{

          if(selected == 'LANUAL')
            $("select[name='especiais'] option:eq(3)").prop("disabled", true);
          else 
            $("select[name='especiais'] option:eq(2)").prop("disabled", true);
            
          if(selected == 'VITALÍCIA' && edicao != 18)
              $("select[name='especiais'] option:eq(3)").prop("disabled", true);

          if(produto == 'ebv'){
              if(edicao == 18){
                  $("#select_modulos_vital").show();
                  $("#select_modulos_ltemp").hide();
                  $(".checkbox-modulos").prop('disabled', false );
              } else {
                  $("#select_modulos_vital").hide();
                  $("#select_modulos_ltemp").show();
                  $(".checkbox-modulos").prop('disabled', true );
                  $("input[type='checkbox'][name='MOD-EB041']").prop('disabled', false );
                  $("input[type='checkbox'][name='MOD-EB033']").prop('disabled', false );
              }
              $("#checkbox-modulos:hidden").show();
              $("#select-modulos:hidden").show();
          }else{
              if(produto == 'ebvqib'){
                  $("select[name='up-vs'] option:eq(6)").prop("selected", true);

                  $("#select-modulos").show();
                  $("#up-more").show();
                  $(".select-up-app").hide();
                  $(".select-up-mod").hide();

                  $("#checkbox-produtos").show();
                  $("#checkbox-modulos").hide();
                  if(selected == 'LANUAL')
                      $(".checkbox-modulos").prop('disabled', true );
              }
              $("input[type='checkbox'][name='ITEM-QIBALV']").prop('disabled', true );
              $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true );
              $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('disabled', true );

              $("#select_modulos_vital:visible").hide();
                if (produto.indexOf('qib') !== -1) {
                    addJson('itens_json', 'QIB');
                    addJson('itens_json', 'QBNEXT');
                    $("input[type='checkbox'][name='ITEM-QIB']").prop('checked', true);
                    $("input[type='checkbox'][name='ITEM-QIB']").prop('disabled', true);
                    $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('checked', true );

                    if (app == '2') // 'BASIC'
                        $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('disabled', true);

                    if (parseInt(edicao) > 18) {
                        $("#select_modulos_ltemp").show();
                        $("#select-modulos:hidden").show();
                    }
                }
              }
      }

      if(produto == 'ebvqib'){
          $("select[name='especiais'] option:gt(1)").prop("disabled", true);

          if(app == '1'){
              $("select[name='modulos_ltemp'] option:gt(1)").prop("disabled", true);
              $("select[name='familia'] option:eq(1)").prop("disabled", true);
              $("select[name='familia'] option:eq(2)").prop("disabled", true);
              $(".select-up-vs").hide();
              $("input[type='checkbox'][name^='ITEM-']").prop('disabled', true);
              $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('checked', true);
              addJson('itens_json', 'QIBEDIT');
          }
      }
      
  });

  $( ".modulos" ).change(function () {
      var selected = $(this).val(), checked = true, mods_select, licenca = $( "select[name='licenca']" ).val(),
          produto  = $("select[name='produto'] option:selected").val(),
          edicao   = $("select[name='edicao'] option:selected").val(),
          app      = $("select[name='app'] option:selected").val(),
          especiais = $("select[name='especiais'] option:selected").val(),
          checkbox_produtos = $("input.checkbox-produtos[data-grupo='hidraulica']")
                           .add("input.checkbox-produtos[data-grupo='preventivos']")
                           .add("input.checkbox-produtos[data-grupo='predial']")
                           .add("input.checkbox-produtos[data-grupo='eletrica']");

      if(produto != 'ebvqib')
          $("select[name='up-mod'] option").prop('disabled', false);

      if(selected == 'Top' || selected == 'Essencial' || selected == 'Light'){
          if(produto.indexOf('ebv') !== -1) {
              var checked_alv = $("input[type='checkbox'][name='MOD-EB041']").prop('checked');
              var checked_pre = $("input[type='checkbox'][name='MOD-EB033']").prop('checked');

              $("input.checkbox-modulos:checked").prop('checked', false);
              $("input[type='checkbox'][name='MOD-MNEXT']").prop('checked', true);

              delJson('modulos_json', 'Top');
              delJson('modulos_json', 'Essencial');
              delJson('modulos_json', 'Light');
              addJson('modulos_json', selected);

              if (checked_alv) {
                  addJson('modulos_json', 'EB041');
                  $("input[type='checkbox'][name='MOD-EB041']").prop('checked', true);
              } else {
                  delJson('modulos_json', 'EB041');
              }

              if (checked_pre) {
                  addJson('modulos_json', 'EB033');
                  $("input[type='checkbox'][name='MOD-EB033']").prop('checked', true);
              } else {
                  delJson('modulos_json', 'EB033');
              }

              if (produto != 'ebvqib' && (selected == 'Light' || selected == 'Top'))
                  $("select[name='up-mod'] option:eq(2)").prop('disabled', true);

              if (produto != 'ebvqib' && (selected == 'Light' || selected == 'Essencial')) {
                  $("select[name='up-mod'] option:eq(3)").prop('disabled', true);
                  $("select[name='up-mod'] option:eq(4)").prop('disabled', true);
              }

              if(produto == 'ebvqib'){
                  //$("#select-familia option:eq(1)").prop('disabled', true );
                  //$("#select-familia option:eq(2)").prop('disabled', true );
                  if(app == '1' || especiais == 'update'){
                      $("input[type='checkbox'][name='ITEM-QIBEDIT']").prop('checked', true );
                      addJson('itens_json', 'QIBEDIT');
                      $("input[type='checkbox'][name='MOD-EB041']").prop('disabled', true);
                      $("input[type='checkbox'][name='MOD-EB033']").prop('disabled', true);
                  }
              }

              if (selected == 'Top')
                  mods_select = modulos.filter(function(mod){ return mod.grupo == 'Essencial' || mod.grupo == 'Light' || mod.grupo == 'Top' });
              /*else if (selected == 'Essencial' && app == '1'){
                  mods_select = modulos.filter(function(mod){ return mod.grupo == 'Light' });
                  addJson('itens_json', 'QIBEDIT');
              }*/
              else if (selected == 'Essencial')
                  mods_select = modulos.filter(function(mod){ return mod.grupo == 'Essencial' || mod.grupo == 'Light' });
              else
                  mods_select = modulos.filter(function(mod){ return mod.grupo == selected });

              mods_select.map(function(el){ $("input[type='checkbox'][name='MOD-"+el.codigo+"']").prop('checked', true ) });
          }

          if(produto.indexOf('qib') !== -1){
              $("#select-familia").hide();
              var checkbox = $("input.checkbox-produtos[data-grupo!='all'][data-grupo!='alvenaria']");
              checkbox.prop('disabled', true );
              checkbox.prop('checked',  false );
              if(selected == 'Top'){
                  checkbox.prop('checked', true );
                  checkbox_produtos.each(function(index) {
                      addJson('itens_json', $(this).val());
                  });
              }else{
                  checkbox_produtos.each(function(index) {
                      delJson('itens_json', $(this).val());
                  });
                  if(selected == 'Essencial'){
                    if(parseInt(edicao) > 19){
                      checkbox.prop('disabled', false );
                      checkbox.on('change', function(evt) {
                        if($("input.checkbox-produtos[data-grupo!='all'][data-grupo!='alvenaria']:checked").length >= 4) { // Limit 3 
                            this.checked = false;
                        }
                      });
                    }else{
                      $("#select-familia option:eq(0)").prop('selected', true );
                      //if(licenca != 'LANUAL')
                          $("#select-familia").show();
                    }
                  }else if(produto == 'ebvqib' && (app == '1' || especiais == 'update')){
                      addJson('itens_json', 'QIBHID');
                      $("input[type='checkbox'][name='ITEM-QIBHID']").prop('checked', true);
                      addJson('itens_json', 'QIBELT');
                      $("input[type='checkbox'][name='ITEM-QIBELT']").prop('checked', true);
                  }else{
                      checkbox.prop('disabled', false );
                  }
              }
          }
      }else{
          if(produto.indexOf('ebv') !== -1 || produto.indexOf('mod') !== -1){
              checked = $(this).prop('checked');
              mods_select = modulos.filter(function(mod){ return mod.tipo == selected });

              if(mods_select.length > 0){
                  mods_select.map(function(el){
                      $("input[type='checkbox'][name='MOD-"+el.codigo+"']").prop('checked', checked );

                      if(checked)
                          addJson('modulos_json', el.codigo);
                      else
                          delJson('modulos_json', el.codigo);
                  });
              }else{
                  $("input.checkbox-modulos:checked").prop('checked', false );
                  $('input[name="modulos_json"]').val("[]");
                  if(licenca == 'LTEMP'){
                      $("input[type='checkbox'][name='MOD-MNEXT']").prop('checked', true );
                      addJson('modulos_json', 'MNEXT');
                  }
              }
          }else{
              $("#select-familia").hide();

              var checkbox = $("input.checkbox-produtos[data-grupo!='all'][data-grupo!='alvenaria']");
              checkbox.prop('disabled', true );
              checkbox.prop('checked',  false );

               checkbox_produtos.prop('checked', false );
               checkbox_produtos.each(function(index) {
                    delJson('itens_json', $(this).val());
               });
          }
      }
  });

  $( "select[name='familia']" ).change(function () {
      var selected = $(this).val();
      $("input.checkbox-produtos[data-grupo='hidraulica']")
   .add("input.checkbox-produtos[data-grupo='preventivos']")
   .add("input.checkbox-produtos[data-grupo='predial']")
   .add("input.checkbox-produtos[data-grupo='eletrica']")
          .each(function(index) {
              delJson('itens_json', $(this).val());
      });
      $("input.checkbox-produtos[data-grupo!='all'][data-grupo!='alvenaria']").prop('checked', false );
      switch(selected){
          case '15 - CPH':
              $("input.checkbox-produtos[value='QIBHID']").prop('checked', true );
              $("input.checkbox-produtos[value='QIBINC']").prop('checked', true );
              $("input.checkbox-produtos[value='QIBGAS']").prop('checked', true );
              addJson('itens_json', 'QIBHID');
              addJson('itens_json', 'QIBINC');
              addJson('itens_json', 'QIBGAS');
              break;
          case '16 - CPE':
              $("input.checkbox-produtos[value='QIBELT']").prop('checked', true );
              $("input.checkbox-produtos[value='QIBCAB']").prop('checked', true );
              $("input.checkbox-produtos[value='QIBSPD']").prop('checked', true );
              addJson('itens_json', 'QIBELT');
              addJson('itens_json', 'QIBCAB');
              addJson('itens_json', 'QIBSPD');
              break;
          case '17 - CPEH':
              $("input.checkbox-produtos[value='QIBHID']").prop('checked', true );
              $("input.checkbox-produtos[value='QIBELT']").prop('checked', true );
              addJson('itens_json', 'QIBHID');
              addJson('itens_json', 'QIBELT');
              break;
      }
  });

  $( "input.checkbox-linha" ).change(function () {
      var selected = $(this).val(), checked = $(this).prop('checked'), itens_select;
          itens_select = itens.filter(function(mod){ return mod.grupo == selected });

      if(checked){
           addJson('linhas_json', selected);

           if( checked && ($("input.checkbox-produtos:checked").length == 1)) {
              $("input[type='checkbox'][name='ITEM-QIB']").prop('checked', true );
              $("input[type='checkbox'][name='ITEM-QIB']").prop('disabled', true );
              $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('checked', true );
              $("input[type='checkbox'][name='ITEM-QBNEXT']").prop('disabled', true );

              addJson('itens_json', 'QIB');
              addJson('itens_json', 'QBNEXT');
            }

            itens_select.map(function(el){ $("input[type='checkbox'][name='ITEM-"+el.codigo+"']").prop('checked', checked )});
      }else{
        delJson('linhas_json', selected);
        if($("input.checkbox-produtos:checked").length == 0){
          $("input.checkbox-produtos:checked").prop('checked', false );
          $("input.checkbox-produtos:disabled").prop('disabled', false );
          delJson('itens_json', 'QIB');
          delJson('itens_json', 'QBNEXT');
        }

        itens_select.map(function(el){ $("input[type='checkbox'][name='ITEM-"+el.codigo+"']").removeProp('checked')});
      }
  });

   $( "input.checkbox-produtos" ).change(function () { 
      var selected = $(this).val(), checked = $(this).prop('checked'),
          modulos_ltemp = $("select[name='modulos_ltemp'] option:selected").val();
      if(checked)
        addJson('itens_json', selected);
      else
        delJson('itens_json', selected);

        if($("select[name='modulos_ltemp']").is(":visible") && modulos_ltemp == "Light"){
            var checkbox_produtos = $("input.checkbox-produtos[data-grupo='hidraulica']")
                                 .add("input.checkbox-produtos[data-grupo='preventivos']")
                                 .add("input.checkbox-produtos[data-grupo='predial']")
                                 .add("input.checkbox-produtos[data-grupo='eletrica']");
            checkbox_produtos.prop('checked', false );
            $(this).prop('checked', checked );

            checkbox_produtos.each(function(index) {
                if($(this).val() != selected)
                    delJson('itens_json', $(this).val());
            });
        }
   });

   $( "input.checkbox-modulos" ).change(function () { 
        var selected = $(this).val(), checked = $(this).prop('checked');

        /*if(selected == 'EB041') {
            $("input[name='ITEM-QIBALV']").prop("checked", checked);
            if(checked){
                addJson('itens_json', 'QIBALV');
            }else{
                delJson('itens_json', 'QIBALV');
            }
        }else if(selected == 'EB033') {
            $("input[name='ITEM-QIBEDIT']").prop("checked", checked);
            if(checked){
                addJson('itens_json', 'QIBEDIT');
            }else{
                delJson('itens_json', 'QIBEDIT');
            }
        }*/

        if(checked){
            addJson('modulos_json', selected);
        }else{
            delJson('modulos_json', selected);
        }
   });

  $( "select[name='especiais']" ).change(function () {
      var selected = $(this).val();
      var produto = $( "select[name='produto']" ).val();
      var edicao  = $("select[name='edicao'] option:selected").val();
      var app = $( "select[name='app']" ).val();
      var licenca = $( "select[name='licenca']" ).val();

      $("select[name='modulos_ltemp']").val("");
      $("select[name='modulos_ltemp'] option:selected").prop("selected", false);
      $("select[name='modulos_ltemp'] option:disabled").prop("disabled", false);
      $("input.checkbox-modulos:checked").prop("checked", false );

      $("#up-more:visible").hide();
      $("select[name^='up-']").val("");
      $("select[name^='up-'] option:selected").prop("selected", false);
      $("select[name^='up-'] option:disabled").prop("disabled", false);
      $("select[name^='up-']:enabled").prop("disabled", true);

      $("input[name^='tempo-']").val("");
      $("#select-tempo-renova:visible").hide();
      $("#select-tempo-up:visible").hide();
      $("input[name^='tempo-']:enabled").prop('disabled', true);

      if(produto.indexOf('ebv') !== -1)
          $("#checkbox-modulos").show();

      if(produto == 'ebv' && parseInt(edicao) > 18 && app == '4') // 'Eberick' && 'PLENA'
          $("select[name='modulos_ltemp'] option:eq(1)").prop("disabled", true);

      if(selected == 'update'){

          if(produto && app && licenca){
            $("#up-more:hidden").show();
            $(".select-up-vs:hidden").show();
            $(".select-up-app:hidden").show();
            $(".select-up-mod:hidden").show();
            $("select[name='modulos_ltemp']").show();
            //$("#select-modulos").show();

            $("select[name^='up-']:disabled").prop("disabled", false);
            //$("#select-modulos:visible").hide();
            $("#select-tempo-up").hide();
            $("#select_modulos_vital:visible").hide();
            $("#select-linha:visible").hide();
              $("#checkbox-modulos").hide();
            $("select[name='modulos_ltemp'] option:eq(3)").removeProp("disabled");

            if(app == '0'){ // 'LITE'
              $(".select-up-app:visible").hide();
            
            //}else if(app == '1'){ // 'FLEX'
              
            }else if((app == '2')&&(licenca == 'VITALÍCIA')){ // 'BASIC'
              $("select[name='up-app'] option:not(:eq(1),:eq(5))").prop("disabled", true);
            }else if((app == '2')&&(licenca == 'LTEMP')){ // 'BASIC'
              $("select[name='up-app'] option:eq(1)").prop("selected", true);
              $("select[name='up-app'] option:not(:eq(1))").prop("disabled", true);
            }else if(app == '3'){ // 'PRO' + LTEMP
              if(produto == 'ebv'){
                if(licenca == 'VITALÍCIA'){
                  $("select[name='up-app'] option:not(:eq(1),:eq(6),:eq(8))").prop("disabled", true);
                }else if(licenca == 'LTEMP'){
                  $("select[name='up-app'] option:not(:eq(1),:eq(8))").prop("disabled", true);
                }
              //}else if (produto == 'qib'){
                // Atualmente QiBuilder não tem PRO
              }
            }else if((app == '4')&&(licenca == 'VITALÍCIA')){ // 'PLENA'
              if(produto == 'ebv')
                $("select[name='up-app'] option:not(:eq(1),:eq(7),:eq(9),:eq(10))").prop("disabled", true);
              else if (produto == 'qib'){
                $("select[name='up-app'] option:not(:eq(1), :eq(9))").prop("disabled", true);
              }
            }else if((app == '4')&&(licenca == 'LTEMP')){ // 'PLENA'
              if (produto == 'qib'){
                $("select[name='up-app'] option:eq(9)").prop("selected", true);
                $("select[name='up-app'] option:not(:eq(9))").prop("disabled", true);
              }else if(produto == 'ebv'){
                $("select[name='up-app'] option:not(:eq(1),:eq(9),:eq(10))").prop("disabled", true);
              }
            }

            if(licenca == 'VITALÍCIA'){
              $(".select-up-vs:hidden").show();
              $(".select-up-mod:visible").hide();
              $("#select-tempo-up:visible").hide();
              $("input[name='tempo-up']:enabled").prop('disabled', true);

             if(produto == 'ebv'){
                //$("#checkbox-produtos:visible").hide();
                $("select[name='up-vs'] option:eq(2)").prop("disabled", true); // 2017
                $("select[name='up-vs'] option:eq(3)").prop("disabled", true); // Linha V4
                $("select[name='up-vs'] option:eq(4)").prop("disabled", true); // V9
              }else if (produto == 'qib'){
                $("select[name='up-vs'] option:eq(1)").prop("disabled", true); // Manter Versão
                $("select[name='up-vs'] option:eq(3)").prop("disabled", true); // Linha V4
                $("select[name='up-vs'] option:eq(4)").prop("disabled", true); // V9
                $("select[name='up-vs'] option:eq(5)").prop("disabled", true); // V10
                 $("#select-modulos").hide();
                 $(".select-up-app").hide();
                 $("select[name='up-vs'] option:eq(2)").prop("selected", true);
                 //$("select[name='up-vs']").prop("disabled", true);
              }

            }else if(licenca == 'LTEMP'){
              $(".select-up-vs:visible").hide();
              if(produto.indexOf('ebv') !== -1)
                $(".select-up-mod:hidden").show();
              else if(produto.indexOf('qib') !== -1)
                $(".select-up-mod:visible").hide();

              $("#select-tempo-up:hidden").show();
              $("input[name='tempo-up']:disabled").prop('disabled', false);
            } else { // LANUAL
                $(".select-up-app").hide();
                $(".select-up-mod").hide();
                $("select[name='up-vs'] option:eq(6)").prop("selected", true);
                $("select[name='up-vs']").prop("disabled", true);
                $("#select-modulos").show();
                $("#select_modulos_ltemp").show();
            }

            if(produto == 'ebvqib'){ 
                $("select[name='modulos_ltemp'] option:gt(1)").prop("disabled", true);
                $("select[name='familia'] option:eq(1)").prop("disabled", true);
                $("select[name='familia'] option:eq(2)").prop("disabled", true);
                $("select[name='up-mod'] option:gt(1)").prop("disabled", true);

                $("select[name='up-app'] option:gt(0)").prop("disabled", true);
                $("select[name='up-app'] option:eq("+app+")").prop("disabled", false);
                
                $("input[name='tempo-up']").prop('max', 3);
                $("input[name='tempo-up']").prop('placeholder', 'Anos');
            }

            return;
          }else if(!produto)
             bootbox.alert('Selecione a Tecnologia!');
          else if(!app)
             bootbox.alert('Selecione a Aplicação!');
          else if(!licenca)
             bootbox.alert('Selecione a Licença!');

          $(this).val("");        
      }else if(selected == 'renova'){
        $("#select-tempo-renova:hidden").show();
        $("input[name='tempo-renova']").prop('disabled', false);
        if(produto == 'ebv')
            $("#checkbox-produtos:visible").hide();
        //$("#checkbox-modulos:visible").hide();
        //$("#select-modulos:visible").hide();
        $("#select-linha:visible").hide();
      }else{
          if(produto == 'qib')
              $("#checkbox-produtos:hidden").show();

          if(licenca == 'VITALÍCIA'){
              if(produto.indexOf('qib') === -1){
                 $("#select-modulos").show();
                 //$("#select_modulos_vital").show();
                 $("#checkbox-modulos").show();
              }
          }else if(licenca == 'LTEMP'){
              if(produto.indexOf('ebv') !== -1){
                 $("#select-modulos:hidden").show();
                 $("#checkbox-modulos:hidden").show();
              }else if (produto.indexOf('qib') !== -1 && edicao == '18'){
                 $("#select-linha:hidden").show();
              }
          }
      }

      if(produto == 'ebvqib' && app == 1){ // 'FLEX'
          $("select[name='modulos_ltemp'] option:gt(1)").prop("disabled", true);
          //
          if(licenca == 'LTEMP'){
              $("input[name='tempo-up']").prop('disabled', false);
              $("input[name='tempo-up']").prop('max', 3);
              $("input[name='tempo-up']").prop('placeholder', 'Anos');
              $("#select-tempo-up").show();
              $(".select-up-vs").hide();
              $(".select-up-app").hide();
              $(".select-up-mod").hide();
              $("#up-more").show();
          } else {
              $("input[type='checkbox'][name^='MOD-']").prop('disabled', true);
          }
      }

  });

  $( "select[name='conexao']" ).change(function () {
      var selected = $(this).val();

      if(selected == 1)
        $("#rede-qtd").show();
      else{
        $("#rede").val("");
        $("#rede-qtd").hide();
      }
  });

</script>