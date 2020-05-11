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

                    'hiddenField' => false
                ]);
            }
        ?>
            </div>
<script>
    window.onload = function(){
        $("#1").prop("checked", true).attr("disabled", "disabled");
        $("#42").prop("checked", true).attr("disabled", "disabled");
        $("#48").prop("checked", true).attr("disabled", "disabled");
        <?php if(isset($tipoProduto)): ?>
            $("#<?= $tipoProduto?>").prop("checked", true).attr("disabled", "disabled");
        <?php endif; ?>
    }
</script>