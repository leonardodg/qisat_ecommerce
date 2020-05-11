<script>
    function bloquearPar(id){
        var conjuntos = [
            [40, 22],
            [24, 23],
            [11, 12, 13, 14, 15],
            [33, 41],
            [49, 50]
        ];
        var bloquear = [];
        for (i = 0; i < conjuntos.length; i++) {
            for (j = 0; j < conjuntos[i].length; j++) {
                if(conjuntos[i][j] == id){
                    bloquear = conjuntos[i];
                    bloquear.splice(j, 1);
                }
            }
        }
        var ativo = $("input[data-ref='" + id + "']");
        var inativo;
        if (ativo.prop("checked")) {
            for (i = 0; i < bloquear.length; i++) {
                inativo = $("input[data-ref='" + bloquear[i] + "']");
                inativo.removeAttr("checked", false);
                inativo.attr("disabled", "disabled");
            }
        } else {
            ativo.removeAttr("disabled");
            for (i = 0; i < bloquear.length; i++) {
                inativo = $("input[data-ref='" + bloquear[i] + "']");
                inativo.removeAttr("disabled");
            }
        }
    }
    function blockCheck(id){
        var selectstipos = $("#selectstipos").find("input[name*='selectTipo_45_']");
        var elem45 = $("#45");

        var enabled = false;
        selectstipos.each(function () {
            if($(this).prop("checked"))
                enabled = true;
        });
        if(enabled) {
            elem45.attr("disabled", "disabled");
            elem45.prop("checked", true);
        } else {
            elem45.removeAttr("disabled");
            elem45.removeAttr("checked");
        }

        bloquearPar(id);
    }
</script>

<div class="ecmProduto col-md-12">
    <?= $this->Form->create($ecmProduto) ?>
    <fieldset>
        <legend><?= __(ucfirst($this->template).' Produto') ?></legend>
        <?php
            echo $this->Form->input('nome');
            echo $this->Form->input('sigla');
            echo $this->Form->input('referencia', [
                'label' => [
                    'text' => __('Referência').' <b>('.__('Valor para facilitar identificação do produto').')</b>',
                    'escape' => false
                ]
            ]);
            echo $this->Form->input('moeda', ['options' => $moeda, 'label' => 'O Valor do Produto dele ser exibido em']);
            
            echo $this->Form->input('preco', ['label' => 'Valor do Produto', 'disabled' => ($tipoProduto == 17 || $tipoProduto == 58)]);
            if($tipoProduto == 17 || $tipoProduto == 58){
                echo $this->Form->button(__("Valores dos Produtos"), ["name" => "preco", "class" => "btn btn-success", "type" => "button", 'style' => 'margin-bottom:12px;']);
?>
<div id="precoModal" class="modal fade" role="dialog">  
    <div class="modal-dialog">  
        <div class="modal-content">  
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal">&times;</button>  
                <h4 class="modal-title">Preço</h4>  
            </div>  
            <div class="modal-body">  
                <table style="box-shadow: none !important;"> 
                    <tr style="display: none">
                        <td style="width:70%">
                            <span id="descricao">Descrição</span>
                        </td>
                        <td>
                            R$ <input id="" name="" data-id="" type="text" value="0" size="6" style="width: 100px; display: inline-block;">
                        </td>
                    </tr>
                </table>
                <button type="button" name="preco" class="btn btn-warning">Salvar</button>  
            </div>  
        </div>  
    </div>  
</div>  
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<script>  
    $(document).ready(function(){ 
        $.fn.modal.Constructor.prototype.setScrollbar = function () { }; 
        $("select[multiple='multiple']").on('change', function(){
            $('input[name="preco"]').val('');
        });
        <?php if(isset($ecmProduto->ecm_produto_mdl_course) && ($tipoProduto == 17 || $tipoProduto == 58)): ?>
            <?php foreach ($ecmProduto->ecm_produto_mdl_course as $course): ?>
                var tr = $(".modal-body table tr:first").clone();
                tr.find("#descricao").text("<?= $course->mdl_course['shortname'] ?>");
                tr.find("input").val(accounting.formatNumber("<?= $course->preco ?>", 2, ".", ","));
                tr.find("input").attr('id',   'valores[<?= $course->mdl_course_id ?>]');
                tr.find("input").attr('name', 'valores[<?= $course->mdl_course_id ?>]');
                tr.find("input").attr('data-id', "<?= $course->mdl_course_id ?>");
                tr.show();
                $(".modal-body table").append(tr);
            <?php endforeach; ?>
        <?php endif; ?>
    }); 
    $("form").submit(function(event) {
        $('#preco').removeAttr('disabled');
    });
    $('button[name="preco"]').click(function(){  
        if($('.modal-body').is(':visible')){
            var total = 0;
            $.each($(".modal-body table tr"), function( index, value ) {
                if($(value).is(':visible')){
                    var preco = parseFloat(accounting.unformat($(value).find('input').val(), ","));
                    total += preco;
                }
            });
            $('input[name="preco"]').val(total);
            $('#precoModal').modal('hide');
        }else{
            var cursos = $("select[multiple='multiple']").val();
            if(cursos != undefined) {  
                $.ajax({  
                    url:"/produto/pacote/get-courses",  
                    method:"GET",  
                    data: {cursos:cursos},  
                    success:function(data)  {  
                        var modal = $('.modal-body');
                        $.each(modal.find("table tr"), function( index, value ) {
                            if(index != 0 && cursos.indexOf($(value).find('input').attr('data-id')) == -1){
                                $(value).remove();
                            }
                        });
                        for (let i in data) {
                            if(!modal.find("table tr input[id='valores["+data[i].id+"]']").length){
                                var tr = modal.find("table tr:first").clone();
                                tr.find("#descricao").text(data[i].shortname);
                                tr.find("input").val(accounting.formatNumber(data[i].preco, 2, ".", ","));
                                tr.find("input").attr('id',   'valores['+data[i].id+']');
                                tr.find("input").attr('name', 'valores['+data[i].id+']');
                                tr.find("input").attr('data-id', data[i].id);
                                tr.show();
                                modal.find("table").append(tr);
                            }
                        } 
                        $('#precoModal').modal('show');
                    } 
                });
            } else { 
                alert('Favor, selecione pelo menos um curso abaixo.');
            } 
        }
    }); 
</script>
<?php
            }

            echo $this->Form->input('habilitado', ['options' => $habilitado]);
            echo $this->Form->input('visivel', ['options' => $visivel]);
            echo $this->Form->input('parcela', ['options' => $parcela, 'label' => 'Limite de parcelas para este Produto']);
            echo $this->Form->input('idtop', ['label' => 'Código do Produto no TopMkt AltoQi']);
        ?>

