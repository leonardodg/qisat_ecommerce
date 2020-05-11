<div id="divdependencia">
    <fieldset>
        <legend><?= __('Add Dependencia') ?></legend>
        <?php
            echo $this->Form->input('mdl_course_id[0]', ['options' => [],
                'label' => 'Curso a ser liberado', 'onchange' => 'alterarConclusion(this)']);
            echo $this->Form->input('mdl_course_conclusion_id[0]', ['options' => [],
                'disabled' => 'disabled', 'label' => 'Curso a ser concluido']);
        ?>
        <?= $this->Form->button(__('Remover Dependencia'), ['type' => 'button', 'onclick' => 'removerDependencia(this)']) ?>
    </fieldset>
</div>

<?php if (!empty($ecmProduto->mdl_fase)):
    $row = 0;
    foreach ($ecmProduto->mdl_fase->mdl_course_mdl_fase as $dependencia): ?>
        <div id="divdependencia<?= ++$row ?>">
            <fieldset>
                <legend><?= __('Edit Dependencia') ?></legend>
                <?php
                    echo $this->Form->hidden('mdl_fase_id', ['value' => $dependencia->mdl_fase_id]);
                    echo $this->Form->input('mdl_course_id['.$row.']', ['options' => [],
                        'onchange' => 'alterarConclusion(this)',
                        'label' => 'Curso a ser liberado']);
                    echo $this->Form->input('mdl_course_conclusion_id['.$row.']', ['options' => [],
                        'label' => 'Curso a ser concluido']);
                ?>
                <?= $this->Form->button(__('Remover Dependencia'), ['type' => 'button', 'onclick' => 'removerDependencia(this)']) ?>
            </fieldset>
        </div>
    <?php endforeach;
endif;?>
<?= $this->Form->button(__('Adicionar Dependência'), ['type' => 'button', 'onclick' => 'adicionarDependencia(this)']) ?>

<script>
    function alterarConclusion(botao){
        var course  = $(botao);
        var conclusionid = course.attr("id").replace("course-id", "course-conclusion-id");
        var conclusion = $("#"+conclusionid);
        if(course.val() == 0){
            conclusion.attr("disabled","disabled");
        }else{
            conclusion.children('option').show();
            conclusion.children('option[value="' + course.val() + '"]').hide();
            conclusion.removeAttr("disabled");
        }
    }
    var row = 1;
    $(function() {
        //row = $('div[id*="divdependencia"]').length;
        $('select[id*="mdl-course-id-"]').append($('<option>', {
            value: 0, text: "Selecione um curso"
        }));
        $('select[id*="mdl-course-conclusion-id-"]').append($('<option>', {
            value: 0, text: "Selecione um curso"
        }));
        <?php if (!empty($ecmProduto->mdl_course)):
            foreach ($ecmProduto->mdl_course as $mdl_course): ?>
                $('select[id*="mdl-course-id-"]').append($('<option>', {
                    value: <?= $mdl_course['id'] ?>,
                    text: "<?= $mdl_course['shortname'] ?>"
                }));
                $('select[id*="mdl-course-conclusion-id-"]').append($('<option>', {
                    value: <?= $mdl_course['id'] ?>,
                    text: "<?= $mdl_course['shortname'] ?>"
                }));
            <?php endforeach;
        endif;?>

        <?php if (!empty($ecmProduto->mdl_fase)):
            foreach ($ecmProduto->mdl_fase->mdl_course_mdl_fase as $dependencia): ?>
                $('#mdl-course-id-'+row+' option[value="<?= $dependencia['mdl_course_id'] ?>"]').attr('selected', 'selected');
                $('#mdl-course-conclusion-id-'+row+' option[value="<?= $dependencia['mdl_course_conclusion_id'] ?>"]').attr('selected', 'selected');
                $('#mdl-course-conclusion-id-'+row+' option[value="<?= $dependencia['mdl_course_id'] ?>"]').hide();
                row++;
            <?php endforeach;
        endif;?>
    });
    $('#divdependencia').hide();
    function adicionarDependencia(botao){
        var divdependencia = $('#divdependencia');
        if(divdependencia.is(':visible')){
            var divdependencia2 = divdependencia.clone();
            divdependencia2.attr('id', 'divdependencia'+row);
            divdependencia2.html(divdependencia2.html().replace(/0/g, row++));
            divdependencia2.removeAttr('id');
            $(botao).before(divdependencia2);
        }else{
            divdependencia.show();
        }
    }
    function removerDependencia(botao){
        var divdependencia = $(botao).parent().parent();
        var mdl_fase_id = divdependencia.find('input[name*="mdl_fase_id"]').val();
        if(mdl_fase_id != ""){
            var mdl_course_id = divdependencia.find('input[name*="mdl_course_id"]').val();
            var mdl_course_conclusion_id = divdependencia.find('input[name*="mdl_course_conclusion_id"]').val();
            $.post("<?= \Cake\Routing\Router::url(['produto/altoqi-lab/delete-dependencia']) ?>",
                {'mdl_fase_id' : mdl_fase_id, 'mdl_course_id' : mdl_course_id,
                    'mdl_course_conclusion_id' : mdl_course_conclusion_id},
                function(data){}, "json");
        }
        if(divdependencia.attr('id') == 'divdependencia'){
            divdependencia.find('input[name*="removido"]').val('1');
            divdependencia.hide();
        } else {
            divdependencia.remove();
        }
    }
</script>
