            <br/>
            <div id="selectstipos" class="selectstipos">
                <label for="sigla">Selecione o Tipo do Produto</label>
        <?php
            $pos = array();
            foreach ($ecmTipoproduto as $value){
                if($value['ecm_tipo_produto_id']==0){
                    $pos[$value['id']] = ['pos'=>0, 'referencia'=>$value['id']];
                } else {
                    $pos[$value['id']] = ['pos'=>$pos[$value['ecm_tipo_produto_id']]['pos']+1,
                        'referencia'=>$pos[$value['ecm_tipo_produto_id']]['referencia'].'_'.$value['id']];
                }
                echo $this->Form->input($value['nome'], [
                    'type' => 'checkbox',
                    'name' => 'selectTipo_'.$pos[$value['id']]['referencia'],
                    'id' => $value['id'],
                    'onclick' => 'blockCheck(this.id)',
                    'label' => [
                        'style' => 'margin-left:'.(25*$pos[$value['id']]['pos']).'px;'
                    ],
                    'data-ref' => $value['id'],
                    'checked' => $value['EcmProdutoTipoproduto']['id']?'checked':'',
                    'hiddenField' => false
                ]);
            }
        ?>
            </div>

<script>
    window.onload = function(){
        $("#1").attr("disabled", "disabled");
        $("#42").attr("disabled", "disabled");
        $("#48").attr("disabled", "disabled");
        $("#<?= $tipoProduto?>").prop("checked", true).attr("disabled", "disabled");
        var checkbox;
        var excecoes = ['45', '3', '4', '6', '5', '7', '8', '9',
            '34', '35', '36', '37', '38', '29', '39'];
        $("#selectstipos").find("input").each(function () {
            if($(this).prop("checked") && $.inArray($(this).attr("data-ref"), excecoes) == -1) {
                var profundidade = $(this).attr("name").split("_");
                if(checkbox == undefined || profundidade.length > checkbox.attr("name").split("_").length){
                    blockCheck($(this).attr("id"));
                }
            }
        });
    }
</script>