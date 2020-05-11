<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?php
    $options = ['label' => 'Selecione os Cursos', 'options' => $mdlCourse];
    if($ecmProduto->refcurso == "true"){
        $options['type'] = 'select';
        $options['label'] = "Selecione um curso";
    }
    echo $this->Form->input('refcurso', ['type' => 'checkbox', 'label' => 'Produto Referência para um Curso',
        'required' => false, 'disabled' => 'disabled']);
    echo $this->Form->input('mdl_course._ids', $options);
?>
<?php if($ecmProduto->refcurso != "true"): ?>
    <?= $this->Html->script('/webroot/js/tiny_mce4/tinymce.min') ?>
    <?= $this->Html->css('/webroot/css/multi-select.css') ?>
    <?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
    <?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>
<script>
    $("select[multiple='multiple']").multiSelect({
        selectableHeader: "<input type='text' class='search-input' autocomplete='off'>",
        selectionHeader: "<input type='text' class='search-input' autocomplete='off'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(id){
            this.qs1.cache();
            this.qs2.cache();
            <?php if($this->request->params['controller'] == "AltoqiLab"): ?>
                $.post("add-course-ordem", {"id": id[0]}, function( data ) {
                    if(data.ecm_produto.mdl_course.length){
                        var course = data.ecm_produto.mdl_course[0];
                        var src = "img/default-img.png";
                        if (data.ecm_produto.ecm_imagem.length)
                            src = "upload/"+data.ecm_produto.ecm_imagem[0].src;
                        var img = $("<img />", {src: '../../../webroot/'+src});
                        var div = $("<div />", {class: 'divSortable'});
                        div.append(img);
                        div.append($("<br/>"));
                        div.append(course.fullname);
                        var li = $("<li />", {
                            class: 'ui-state-default ui-sortable-handle',
                            title: course.fullname,
                            sigla: course.shortname
                        });
                        li.attr("data-id", course.id);
                        li.append(div);
                        var sortable = $("#sortable");
                        sortable.append(li);
                        $("label[for='sortable']").show();
                        var height = li.height() * Math.floor((3 + $("#sortable li").size()) / 4);
                        sortable.height(height);

                        var name = $("#mdl-course-ids option[value='"+id[0]+"']").text();
                        $('select[id*="mdl-course-id-"]').append($('<option>', {
                            value: id[0], text: name
                        }));
                        $('select[id*="mdl-course-conclusion-id-"]').append($('<option>', {
                            value: id[0], text: name
                        }));

                        if(!data.modules){
                            var msg = (course.fullname.indexOf("Curso") != -1 ? "O " : "A ") +
                                course.fullname + " não contem módulos cadastrados.";
                            bootbox.alert(msg);
                        }
                    }
                }, "json");
            <?php endif; ?>
        },
        afterDeselect: function(id){
            this.qs1.cache();
            this.qs2.cache();
            <?php if($this->request->params['controller'] == "AltoqiLab"): ?>
                $("li[data-id='"+id[0]+"']").remove();
                var sortable = $("#sortable");
                var height = $("#sortable li:last").height() * Math.floor((3 + $("#sortable li").size()) / 4);
                sortable.height(height);
                if(sortable.is(':empty'))
                    $("label[for='sortable']").hide();

                $('select[id*="mdl-course-id-"] option[value="'+id[0]+'"]').remove();
                $('select[id*="mdl-course-conclusion-id-"] option[value="'+id[0]+'"]').remove();
            <?php endif; ?>
        }
    });
</script>
<?php endif; ?>
