<?= $this->Html->script('/webroot/js/tiny_mce4/tinymce.min') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>

<div class="ecmProdutoInfo col-md-12">
    <?= $this->Form->create($ecmProdutoInfo, ['enctype' => 'multipart/form-data']) ?>
    <div id="accordion-info">
        <fieldset>
            <legend>
                <h1 role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
                    <?= __('Editar Info do Produto') ?>
                </h1>
            </legend>
            <div id="collapse1" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading2">
                <?php
                    echo $this->Form->input('titulo', ['label' => __('Título')]);
                    echo $this->Form->input('chamada');
                    echo $this->Form->input('persona', ['class' => 'tinymce-editor']);
                    echo $this->Form->input('descricao', ['label' => __('Descrição'), 'class' => 'tinymce-editor']);
                    echo $this->Form->input('qtd_aulas');
                    echo $this->Form->input('tempo_acesso');
                    echo $this->Form->input('tempo_aula');
                    echo $this->Form->input('carga_horaria');
                    echo $this->Form->input('material');
                    echo $this->Form->input('certificado_digital');
                    echo $this->Form->input('certificado_impresso');
                    echo $this->Form->input('forum');
                    echo $this->Form->input('tira_duvidas');
                    echo $this->Form->input('mobile');
                    echo $this->Form->input('software_demo');
                    echo $this->Form->input('simulador');
                    echo $this->Form->input('disponibilidade', ['label' => __('Disponibilidade'), 'maxlength' => 20, 'type' => 'text']);
                    echo $this->Form->input('metodologia', ['label' => __('Metodologia'), 'maxlength' => 20, 'type' => 'text']);
                    echo $this->Form->input('metatag_titulo');
                    echo $this->Form->input('metatag_key');
                    echo $this->Form->input('metatag_descricao');
                    echo $this->Form->input('url');

                    $attr = [
                        'selector' => '.tinymce-editor',
                        'plugins' => 'advlist autolink lists link charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking save table contextmenu directionality emoticons template paste textcolor',
                        'toolbar1' => 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
                        'toolbar2' => 'print preview | forecolor backcolor emoticons | code'];
                    echo $this->TinyMCE->editor($attr);
                ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <h1 role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    <?= __('Add Arquivos na Info do Produto') ?>
                </h1>
            </legend>

            <div id="collapse2" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading2">
                <div id= "arquivosHide" class="hide">
                    <?php
                        echo $this->Form->input('EcmProdutoInfoArquivos.tipo[]', ['label' => 'Tipo', 'options' => $tiposArquivos]);
                        echo $this->Form->input('EcmProdutoInfoArquivos.nome[]', ['label' => 'Nome']);
                        echo $this->Form->input('EcmProdutoInfoArquivos.descricao[]', ['label' => 'Descrição']);
                        echo $this->Form->input('EcmProdutoInfoArquivos.link[]', ['label' => 'Link']);
                        echo $this->Form->label('Arquivo');
                        echo $this->Form->file('EcmProdutoInfoArquivos.path[]', ['onchange' => 'exibirImagem(this)']);
                        $atributosImagem = ['style' => 'max-height:52px;'];
                        echo $this->Html->tag('img', null, $atributosImagem);
                    ?>
                    <br/><br/>
                    <?= $this->Form->button(__('Remover Arquivo'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                <br/></div>
                <?php if(isset($ecmProdutoInfo->ecm_produto_info_arquivos)): ?>
                    <?php echo $this->Form->hidden('EcmProdutoInfoArquivos.id[]'); ?>
                    <?php foreach($ecmProdutoInfo->ecm_produto_info_arquivos as $ecm_produto_info_arquivos): ?>
                        <div data-name="Arquivos">
                            <?php
                                echo $this->Form->hidden('EcmProdutoInfoArquivos.id[]', ['value' => $ecm_produto_info_arquivos->id]);
                                echo $this->Form->input('EcmProdutoInfoArquivos.tipo[]', ['label' => 'Tipo',
                                    'options' => $tiposArquivos, 'value' => $ecm_produto_info_arquivos->ecm_produto_info_arquivos_tipos_id]);
                                echo $this->Form->input('EcmProdutoInfoArquivos.nome[]', ['label' => 'Nome',
                                    'value' => $ecm_produto_info_arquivos->nome]);
                                echo $this->Form->textarea('EcmProdutoInfoArquivos.descricao[]', ['label' => 'Descrição',
                                    'value' => $ecm_produto_info_arquivos->descricao]);
                                echo $this->Form->input('EcmProdutoInfoArquivos.link[]', ['label' => 'Link',
                                    'value' => $ecm_produto_info_arquivos->link]);
                                echo $this->Form->label('Arquivo');
                                echo $this->Form->file('EcmProdutoInfoArquivos.path[]', ['onchange' => 'exibirImagem(this)']);

                                if(!empty($ecm_produto_info_arquivos->path)){
                                    if(pathinfo($ecm_produto_info_arquivos->path, PATHINFO_EXTENSION) != 'mp4') {
                                        $atributosImagem = ['style' => 'max-height:52px;'];
                                        $atributosImagem['src'] = $this->request->webroot . 'upload/' . $ecm_produto_info_arquivos->path;
                                        echo $this->Html->tag('img', null, $atributosImagem);
                                    }else{
                                        echo $this->Html->image('movie_file.png').'<br / >';
                                        echo substr($ecm_produto_info_arquivos->path, strrpos($ecm_produto_info_arquivos->path, '/') + 1);
                                    }
                                }
                            ?>
                            <br/><br/>
                            <?= $this->Form->button(__('Remover Arquivo'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                        <br/></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?= $this->Form->button(__('Add Arquivo'), ['type' => 'button', 'onclick' => "adicionar(this,'arquivos');"]) ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <h1 role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                    <?= __('Add Conteúdo') ?>
                </h1>
            </legend>

            <div id="collapse3" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading3">
                <a href="javascript://" id="minimizar-tudo-conteudo" data-visible="1"><?= __('Minimizar Tudo')?></a>

                <li id="conteudoHide" class="ui-state-default item-conteudo hide">
                    <div class="conteudo-header">
                        <span class="titulo-conteudo-header">Novo</span>
                        <span class="ui-icon ui-icon-arrowthick-2-n-s" style="float:right;"></span>
                    </div>
                    <div data-name="Conteudo" class="div-editor">
                        <?php
                        echo $this->Form->input('EcmProdutoInfoConteudo.titulo[]', ['label' => 'Titulo']);
                        echo $this->Form->textarea('EcmProdutoInfoConteudo.descricao[]', ['label' => 'Descrição']);
                        ?>
                        <?= $this->Form->button(__('Remover Conteúdo'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                    </div>
                </li>

                <ul id="sortable">
                <?php if(isset($ecmProdutoInfo->ecm_produto_info_conteudo)): ?>
                    <?php echo $this->Form->hidden('EcmProdutoInfoConteudo.id[]'); ?>
                    <?php
                    $contEditor = 0;
                    foreach($ecmProdutoInfo->ecm_produto_info_conteudo as $ecm_produto_info_conteudo):
                    ?>
                        <li class="ui-state-default item-conteudo">

                            <div class="conteudo-header">
                                <span class="titulo-conteudo-header"><?= $ecm_produto_info_conteudo->titulo?></span>
                                <span class="ui-icon ui-icon-arrowthick-2-n-s" style="float:right;"></span>
                            </div>
                            <div data-name="Conteudo" class="div-editor">
                                <?php
                                echo $this->Form->hidden('EcmProdutoInfoConteudo.id[]', ['value' => $ecm_produto_info_conteudo->id]);
                                echo $this->Form->input('EcmProdutoInfoConteudo.titulo[]', ['label' => 'Titulo',
                                    'value' => $ecm_produto_info_conteudo->titulo]);
                                echo $this->Form->textarea('EcmProdutoInfoConteudo.descricao[]', ['label' => 'Descrição',
                                    'value' => $ecm_produto_info_conteudo->descricao,
                                    'class' => 'tinymce-editor',
                                    'id' => 'tinymce-editor-default-'.$contEditor++
                                ]);
                                ?>
                                <?= $this->Form->button(__('Remover Conteúdo'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                                <br/>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                </ul>

                <?= $this->Form->button(__('Add Conteúdo'), ['type' => 'button', 'onclick' => "adicionar(this,'conteudo');"]) ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <h1 role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                    <?= __('Add Faq') ?>
                </h1>
            </legend>

            <div id="collapse4" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading4">
                <div id= "faqHide" class="hide">
                    <?php
                        echo $this->Form->input('EcmProdutoInfoFaq.titulo[]', ['label' => 'Titulo']);
                        echo $this->Form->input('EcmProdutoInfoFaq.descricao[]', ['label' => 'Descrição']);
                    ?>
                    <?= $this->Form->button(__('Remover FAQ'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                <br/></div>
                <?php if(isset($ecmProdutoInfo->ecm_produto_info_faq)): ?>
                    <?php echo $this->Form->hidden('EcmProdutoInfoFaq.id[]'); ?>
                    <?php foreach($ecmProdutoInfo->ecm_produto_info_faq as $ecm_produto_info_faq): ?>
                        <div data-name="Faq">
                            <?php
                                echo $this->Form->hidden('EcmProdutoInfoFaq.id[]', ['value' => $ecm_produto_info_faq->id]);
                                echo $this->Form->input('EcmProdutoInfoFaq.titulo[]', ['label' => 'Titulo',
                                    'value' => $ecm_produto_info_faq->titulo]);
                                echo $this->Form->input('EcmProdutoInfoFaq.descricao[]', ['label' => 'Descrição',
                                    'value' => $ecm_produto_info_faq->descricao]);
                            ?>
                            <?= $this->Form->button(__('Remover FAQ'), ['type' => 'button', 'onclick' => 'remover(this);']) ?>
                        <br/></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?= $this->Form->button(__('Add FAQ'), ['type' => 'button', 'onclick' => "adicionar(this,'faq');"]) ?>
            </div>
        </fieldset>
    </div>

    <br/>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function adicionar(botao, tipo){
        var div = $("#"+tipo+"Hide").clone();
        div.removeAttr('id');
        div.removeClass('hide');

        if(tipo == 'conteudo') {
            var idEditor = '';
            var cont = $('#tinymce-editor-' + $('.div-editor').length).length;
            do {
                idEditor = 'tinymce-editor-' + cont;

                if($('#'+idEditor).length){
                    cont++;
                    idEditor = '';
                }

            }while(idEditor == '');

            div.addClass('div-editor');
            div.find('textarea').attr('id', idEditor);

            $(botao).parent().find('#sortable').append(div);
            tinymce.EditorManager.execCommand('mceAddEditor', true, idEditor);
            $( "#sortable" ).sortable( "refresh" );

        }else{
            $(botao).before(div);
        }
    }
    function remover(botao){
        bootbox.confirm({
            message: "<?= __('Deseja realmente remover esse conteúdo?')?>",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result) {
                    var div = $(botao).parent();

                    if(div.attr('class') == 'div-editor') {
                        var idEditor = div.find('textarea').attr('id');
                        tinymce.EditorManager.execCommand('mceRemoveEditor', true, idEditor);
                    }

                    var id = div.find("input[type='hidden']").val();
                    var tipo = div.attr('data-name');
                    if (tipo != undefined) {
                        $.post('../delete' + tipo, {id: id});
                    }
                    div.fadeOut(200, function () {
                        div.remove();
                    });
                }
            }
        });
    }
    function exibirImagem(input) {
        var file = input.files[0];
        var reader = new FileReader();
        var imagem = $(input).parent().find("img");
        reader.onloadend = function () {
            imagem.attr('src', reader.result);
        };
        if (file) {
            reader.readAsDataURL(file);
            imagem.show();
        } else {
            imagem.attr("src", "");
            imagem.hide();
        }
    }

    $( function() {
        $( "#sortable" ).sortable({
            placeholder: "portlet-placeholder ui-corner-all",
            beforeStop: function( event, ui ) {
                tinymce.EditorManager.execCommand('mceAddEditor', true, ui.item.find("textarea").attr('id'));
                $('#move-editor').remove();
            },
            activate: function( event, ui ) {
                tinymce.remove(tinymce.get(ui.item.find("textarea").attr('id')));
                ui.item.append('<div id="move-editor"> </div>');
            }
        });
        $( "#sortable" ).disableSelection();

        $( ".item-conteudo" )
            .addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
            .find( ".conteudo-header" )
            .addClass( "ui-widget-header ui-corner-all" )
            .prepend( "<span class='ui-icon ui-icon-minusthick conteudo-minus' style='float: right;'></span>");

        $( "#sortable" ).on( "click", '.conteudo-minus', function() {
            var icon = $( this );
            icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
            $(this).closest('li').find(".div-editor").toggle();
        });

        $('#minimizar-tudo-conteudo').click(function(){
            var visible = $(this).data().visible;

            if(visible == 1) {
                $( "#sortable li" ).find( ".div-editor:visible" ).toggle();
                $(this).data().visible = 0;
                $(this).text('<?= __('Maximizar Tudo')?>');
                $( "#sortable .ui-icon-minusthick" ).toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
            }else {
                $( "#sortable li" ).find( ".div-editor:hidden" ).toggle();
                $(this).data().visible = 1;
                $(this).text('<?= __('Minimizar Tudo')?>');
                $( "#sortable .ui-icon-plusthick" ).toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
            }

        });

        $('#sortable').on("keyup", '#ecmprodutoinfoconteudo-titulo', function(){
            $(this).parent().parent().parent().find('.conteudo-header .titulo-conteudo-header').text($(this).val());
        });
    } );
</script>
<style>
    #move-editor{
        position: absolute;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, 0.8) url('/img/move-32-32.png') 50% 50% no-repeat;
    }
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
    #sortable li {padding: 5px; margin-bottom: 10px;cursor: move;}
    .ui-state-highlight { height: 1.5em; line-height: 1.2em; }

    #sortable .ui-icon-minusthick, #sortable .ui-icon-plusthick{
        cursor:pointer;
    }

    #accordion-info>fieldset{
        border: 1px solid #ddd;
    }

    #accordion-info>fieldset h1:after {
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }

    #accordion-info>fieldset h1[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    #accordion-info>fieldset h1[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }
</style>