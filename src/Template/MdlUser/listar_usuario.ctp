<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<?= $this->Html->script('/webroot/js/accounting.js') ?>
<?= $this->Html->script('/webroot/js/jquery.mask.min.js') ?>
<div class="ecmUser form medium-12 large-12 columns content">
    <h4><?= __('Buscar Usuário') ?></h4>
    <?= $this->Form->create('') ?>
    <fieldset>
        <div class="medium-6 large-6 columns">
            <?php
                echo $this->Form->input('idnumber', ['label' => __('Chave')]);
            ?>
        </div>
        <div class="medium-6 large-6 columns">
            <?php
                echo $this->Form->input('cpf', ['label' => __('CPF / CNPJ')]);
            ?>
        </div>
        <div class="medium-6 large-6 columns">
            <?php
                echo $this->Form->input('nome', ['label' => __('Nome')]);
            ?>
        </div>
        <div class="medium-6 large-6 columns">
            <?php
                echo $this->Form->input('email', ['label' => __('Email')]);
            ?>
        </div>
        <div class="medium-6 large-6 columns">
            <?php
                echo $this->Form->checkbox('altoqi',['id'=>'altoqi']);
                echo $this->Html->tag('label', __('Buscar na Base Site AltoQi'), ['for' => 'altoqi']);
            ?>
        </div>
        <?= $this->Form->hidden('clients', ['value' =>"[]"]) ?>
    </fieldset>

    <?php if ($contaAzul): ?>
        <h4><?= __('Filtra Lista para Exportar - ContaAzul') ?></h4>
        <fieldset>
            <div class="medium-2 large-2 columns">
                <label for="filter"> Buscar por:</label>
                <?= $this->Form->select('filter',[ 'Email', 'CPF', 'Chave AltoQI'], [ 'empty' => '(selecione o tipo de lista)' ]); ?>
            </div>
            <div class="medium-8 large-8 columns">
                <label for="list"> (Lista deve ser separado por virgula "," - Limite 100) </label>
                <?= $this->Form->textarea('list'); ?>
                <span class='limitqtd'></span>
            </div>
        </fieldset>
    <?php endif; ?>

    <div class="row">
        <div class="medium-12 large-12 columns">
            <?php if ($contaAzul): ?>
                <?= $this->Form->button(__('Exportar ContaAzul'), ['class' => 'btnContaAzul', 'type' => 'button']) ?>
                <?= $this->Form->button(__('Select All'), ['class' => 'btnSelectAll', 'type' => 'button', 'title' => 'Selecionar Todos']) ?>
            <?php endif; ?>
            <?= $this->Form->button(__('Buscar')) ?>
        </div>    
    </div>

    <div class="row">
        <div class="medium-6 large-6 columns">
            <h4><?= __('Usuários da Plataforma') ?> </h4>
        </div>
        <div class="medium-2 large-2 columns">
            <?= $this->Form->select('limit',['20'=> 20, '50'=>50, '100'=> 100], ['label' => 'Numero de linhas', 'empty' => '(selecione o limite)', 'value' => $limit ]); ?>
        </div>
    </div>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>    
    </div>

    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList" >
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('id',__('ID')) ?></th>
            <th><?= $this->Paginator->sort('idnumber',__('Chave')) ?></th>
            <th><?= __('CPF') ?></th>
            <th><?= $this->Paginator->sort('firstname', __('Nome')) ?></th>
            <th><?= __('E-mail') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($mdlUserLista as $user): ?>
            <tr>
                <td> <?= h($user->id) ?></td>
                <td> &nbsp <?= h($user->idnumber) ?></td>
                <td> <?= h($user->numero) ?></td>
                <td> <?= h($user->firstname.' '.$user->lastname) ?></td>
                <td> <?= h($user->email) ?></td>
                <td class="actions">
                    <?= $this->Html->link('', ['plugin' => 'Carrinho', 'controller'=>'', 'action' => 'index', $user->id ],['title' => 'Comprar', 'class' => 'glyphicon glyphicon-shopping-cart']) ?>

                    <?= $this->Html->link('', ['controller'=>'MdlUser', 'action' => 'edit', $user->id],['title' => 'Editar', 'class' => 'glyphicon glyphicon-pencil']) ?>

                    <?= $this->Html->link('', $moodle.'/blocks/gerenciamento/Central/AlunosAcessos/consultageral.php?'. (($user->idnumber) ? 'chave='.$user->idnumber : 'email='.$user->email ) ,['title' => 'Cursos', 'class' => 'glyphicon glyphicon-education', 'target' => '_blank']) ?>

                    <?php if($user->email) echo $this->Html->link('', '#',['title' => 'Enviar Lembrete de Senha', 'class' => 'glyphicon glyphicon-envelope sendmail', 'data-email' => $user->email ]) ?>

                    <?php if ($contaAzul): ?>
                        <?= ($user->numero) ? $this->Html->link('', [ 'controller'=>'ContaAzul', 'action' => 'exportClients', $user->id],['title' => 'Exportar ContaAzul', 'class' => 'glyphicon glyphicon-share-alt']) : '' ?>
                        <?= ($user->numero) ? $this->Form->checkbox('export', ['hiddenField' => false, 'class' => 'select-export', 'data-id' => $user->id, 'value' => 0, 'title' => 'Exportar lista ContaAzul' ]) : '' ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>    
    </div>

    <?php if($userAltoQiLista):?>
        <?php if(count($userAltoQiLista) > 0):?>
            <h5><?= __('Usuários AltoQi') ?></h5>   
            <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveUserListAltoQi" >
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id',__('Chave')) ?></th>
                    <th><?= __('CPF') ?></th>
                    <th><?= $this->Paginator->sort('firstname', __('Nome')) ?></th>
                    <th><?= __('E-mail') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userAltoQiLista as $user): ?>
                    <tr>
                        <td> <?= $user[0].'-'.$user[1] ?></td>

                        <td> 
                            <?php if(!$user[2]):?>
                                <?= $this->Form->input('newcpf-'.$user[0], ['label' => false, 'style'=> 'display : none;', 'class' => 'newcpf' ]) ?>
                                <span class="glyphicon glyphicon-pencil show-input-cpf" aria-hidden="true" title="Editar" style="cursor: pointer;" data-chave="<?= $user[0] ?>" ></span> 
                            <?php else:?>
                                <?= $user[2] ?>
                            <?php endif; ?>
                        </td>
                        <td> <?= h($user[5]) ?></td>
                        <td> 
                            <?php if(!$user[3]):?>
                                <?= $this->Form->input('newemail-'.$user[0], ['label' => false, 'style'=> 'display : none;' ]) ?>
                                <span class="glyphicon glyphicon-pencil show-input-email" aria-hidden="true" title="Editar" style="cursor: pointer;" data-chave="<?= $user[0] ?>" ></span> 
                            <?php else:?>
                                <?= $user[3] ?>
                            <?php endif; ?>
                        </td>
                        <td class="actions">  &nbsp
                            <?php if(!$user[6]):?>
                                <span class="button importar" data-chave="<?= $user[0] ?>" title="Importar" > Importar </span>
                            <?php endif; ?>

                            <span class="glyphicon glyphicon-ok salvar" aria-hidden="true" title="Salvar" style="cursor: pointer;display: none" data-chave="<?= $user[0] ?>" ></span>
                        </td> 
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>

    $(document).ready(function() {

        $(".btnContaAzul:visible").hide();

        if($('.select-export').length ==0){
            $(".btnSelectAll:visible").hide();
        }else{
            $(".btnSelectAll:visible").data('view', 'show');
        }

        $(".btnContaAzul").click(function() {
            var data = $("input[name='clients']").val();
            
            $.ajax({
                type: "POST",
                url: '/conta-azul/export-clients',
                data: { ids: data },
                dataType : 'json',
                success:function(res) {
                    console.log(res);
                },
                complete:function(res) {
                    console.log(res);
                }
            });
        });
        
        $(".btnSelectAll").on('click',function() {

            var ids = '';

            if($(this).data('view') == 'show'){
                $('.select-export').prop('checked','checked');
                $(this).data('view', 'hide');
                ids = $('.select-export').map(function() {
                                                    return $(this).data('id');
                                                }).get().join();
                
                $(".btnContaAzul:hidden").show();                
            }else{
                $(this).data('view', 'show');
                $('.select-export').removeAttr('checked');
                $(".btnContaAzul:visible").hide();
            }
            
            ids = JSON.parse('['+ids+']');
            ids = JSON.stringify(ids);
            $("input[name='clients']").val(ids);   
        });

        $("select[name='limit']").change(function(){
                var val = $(this).val();
                document.location.search = (val) ? '&limit='+val : '&limit=20';
        });

        $("textarea[name='list']").on('input',function(){
                var val = $(this).val();
                if(val){
                    val = val.split(',',100);
                    $(".limitqtd").text("Quatidade de Dados: "+val.length);
                    $("select[name='filter']").prop('required', 'required');
                }else{
                    $("select[name='filter']").removeAttr('required');
                    $(".limitqtd").text("");
                }
        });

        $( ".select-export" ).change(function() {
                var id = $(this).data('id');
                var list = $("input[name='clients']").val();
                    list = JSON.parse(list);

                if($(this)[0].checked)
                    list.push(id);
                else
                    list.splice(list.indexOf(id),1);

                if(list.length){
                    $(".btnContaAzul:hidden").show();
                    $(".btnSelectAll:hidden").show();
                }else{
                    $(".btnContaAzul:visible").hide();
                    $(".btnSelectAll:visible").hide();
                }
                
                list = JSON.stringify(list);
                $("input[name='clients']").val(list);
        });

        $("input.newcpf").keyup(function(e){
            var code = e.keyCode || e.which;
            var tamanho = $("input.newcpf").val().replace(/[^\d]+/g,'').length;
            var elem = this;

            if (code != 9 && code != 13  && code != 32 && code != 86 && code != 17 && code != undefined ) {
                try {
                    $("input.newcpf").unmask();
                } catch (e) {}

                if(tamanho <= 11)
                    $("input.newcpf").mask("999.999.999-999");
                else if(tamanho > 11)
                    $("input.newcpf").mask("99.999.999/9999-99");

                setTimeout(function(){
                    elem.selectionStart = elem.selectionEnd = 10000;
                }, 0);
                var currentValue = $(this).val();
                $(this).val('');
                $(this).val(currentValue);
            }
        });


        function validaCPF(cpf){
                exp = /\.|\-/g;
                cpf = cpf.toString().replace( exp, "" );
                var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10)),
                    soma1=0, soma2=0, vlr =11, digitoGerado;

                for(i=0;i<9;i++){
                        soma1+=eval(cpf.charAt(i)*(vlr-1));
                        soma2+=eval(cpf.charAt(i)*vlr);
                        vlr--;
                }       
                soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
                soma2 = (((soma2+(2*soma1))*10)%11) == 10 ? 0 : (((soma2+(2*soma1))*10)%11);
                digitoGerado = (soma1*10)+soma2;

                return (digitoGerado!=digitoDigitado) ? false : true; 
        };

        function validarCNPJ(cnpj){
                var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
                var dig1= new Number;
                var dig2= new Number;

                exp = /\.|\-|\//g
                cnpj = cnpj.toString().replace( exp, "" ); 
                var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

                for(i = 0; i<valida.length; i++){
                        dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
                        dig2 += cnpj.charAt(i)*valida[i];       
                }
                dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
                dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

                return (((dig1*10)+dig2) != digito) ? false : true;
        }


        function validaEmail(email){      
           var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
           return emailPattern.test(email); 
         } 

        $( ".importar" ).click(function() {
                var params = { chave : $(this).data('chave') };

                $.get("importar-usuario", params, function( data ) {
                            if(data && data.sucesso){
                                bootbox.alert('Sucesso!', function(){ location.reload(); });
                            }else 
                                bootbox.alert('Falha ao tentar importar Usuário!');
                        }, "json");
        });

        $( ".salvar" ).click(function() { 
            var chave = $(this).data('chave');
            var newCPF = $("input[name='newcpf-"+chave+"']").val();
            var newEmail = $("input[name='newemail-"+chave+"']").val();
            var data = { chave : chave };

            if(newCPF && newCPF != '' && typeof newCPF != 'undefined') {

                var cpf_cnpj = newCPF.replace(/[^\d]+/g,'');


                if(cpf_cnpj.length>11){
                    if(validarCNPJ(newCPF))
                        data['cpf'] = newCPF;
                    else{
                        bootbox.alert('CNPJ Invalido!');
                        return;
                    }
                }else{ 
                    if(validaCPF(newCPF))
                        data['cpf'] = newCPF;
                    else{
                        bootbox.alert('CPF Invalido!');
                        return;
                    }
                }
            }

            if(newEmail && newEmail != '' && typeof newEmail != 'undefined') {
                if(validaEmail(newEmail))
                    data['email'] = newEmail;
                else{
                    bootbox.alert('E-Mail Invalido!');
                    return;
                }
            }

            $.post("edit-usuario-altoqi", data, function( res ) {
                if(res && res.sucesso)
                    bootbox.alert('Sucesso!', function(){ location.reload(); });
                else 
                    bootbox.alert('Falha ao salvar!');
            }, "json");

        });
        $( ".show-input-cpf" ).click(function() { 
                var chave = $(this).data('chave');
                $(this).hide(); 
                $("input[name='newcpf-"+chave+"']").show(); 
                $(".salvar[data-chave='"+chave+"']").show(); 
        });
        $( ".show-input-email" ).click(function() { 
                var chave = $(this).data('chave');
                $(this).hide(); 
                $("input[name='newemail-"+chave+"']").show(); 
                $(".salvar[data-chave='"+chave+"']").show(); 
        });

        $( ".sendmail" ).click(function() { 
                var data = { email : $(this).data('email') };
                    
                $.post("/wsc-user/remember-me", data, function( res ) {
                    if(res && res.retorno && res.retorno.sucesso)
                        bootbox.alert('Email enviado para: '+data.email);
                    else 
                        bootbox.alert('Falha!');
                }, "json");
        });
     });
</script>