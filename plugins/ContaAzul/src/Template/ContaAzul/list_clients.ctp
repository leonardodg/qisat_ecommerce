
<br>
<br>

<div class="row">
    <div class="col-md-12">
        <h3><?= __('Lista de Clientes - ContaAzul') ?></h3>

        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Filtro') ?></legend>

            <div class="row">
                <div class="col-md-8">
                    <?= $this->Form->input('nome', ['label' => "Buscar pelo Nome ou Chave","onkeyup" => "filter()"]) ?>
                </div>

                  <div class="col-md-2">
                    <div class="input-group"> 
                    <span class="input-group-btn">
                        <?= $this->Html->link('Exportar Cliente', \Cake\Routing\Router::url([ 'plugin' => false,'controller' => 'MdlUser', 'action' => 'listar-usuario']),  [ 'class' => 'button' ]) ?>
                    </span>
                    </div><!-- /input-group -->
                   </div>
            </div>
        </fieldset>
        <?= $this->Form->end() ?>

        <table id="products" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th width="15%" > Identificação  </th>
                <th width="25%" > Nome </th>
                <th width="12%" >  CPF/CNPJ </th>
                <th width="35%" >  Dados </th>
                <th width="8%">  Ação </th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $user): ?>
                <tr>
                    <td><?= (($user['uid']) ? ('<b> id: </b>'. $user['uid'] ).'<br>' : '').$user['id'] ?></td>
                    <td><?= h($user['chave'] .' - '. $user['name']) ?></td>
                    <td><?= $user['document'] ?></td>
                    <td> 
                        <?php 
                            echo '<b> E-mail: </b>'. $user['email']. '<br>';
                            echo ($user['business_phone']) ? '<b> Telefone: </b>'. $user['business_phone']. '<br>' : '';
                            echo '<b> Pessoa: </b>'. (($user['person_type'] == 'NATURAL') ? 'Fisíca' : 'Jurídica'). '<br>';

                            if($user['person_type'] == 'LEGAL'){
                                echo ($user['state_registration_number']) ? ('<b> Inscrição Estadual: </b>'. $user['state_registration_number']. '<br>') : '';
                                echo ($user['state_registration_type']) ? ('<b> Tipo Inscrição Estadual: </b>'. $user['state_registration_type']. '<br>') : '';
                                echo ($user['city_registration_number']) ? ('<b> Inscrição Municipal: </b>'. $user['city_registration_number']. '<br>') : '';
                            }

                        ?>
                    </td>
                    <td class="actions">
                        <?php if ($set): ?>
                            <?= ($user['uid']) ? $this->Html->link('', ['plugin' => false, 'controller'=>'MdlUser', 'action' => 'edit', $user['uid'] ], ['title' => 'Editar', 'class' => 'glyphicon glyphicon-pencil']) : '' ?>
                        <?php endif; ?>

                        <?php if ($del): ?>
                            <?= $this->Form->postLink('', ['plugin' => 'ContaAzul','controller' => false, 'action' => 'delClient',  $user['id'] ], ['confirm' => __('Confirma exclusão do cliente no ContaAzul? # {0}?', $user['id']), 'title' => 'Deletar', 'class' => 'glyphicon glyphicon-remove']) ?>
                        <?php endif; ?>

                        <?php if ($set): ?>
                            <?= ($user['vincular']) ? $this->Html->link('', '#', ['title' => 'Vincular', 'class' => 'glyphicon glyphicon-share-alt vincular', 'data-uid' => $user['id'], 'data-id' => $user['vincular'] ]) : '' ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function filter() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("nome");
    filter = input.value.toUpperCase();
    table = document.getElementById("products");
    tr = table.getElementsByTagName("tr");
    var i = tr.length;

        while (i--) {
            td = tr[i].getElementsByTagName("td")[1];

            if (td || td2) {
                if ( (td.innerHTML.toUpperCase().indexOf(filter) > -1)) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            } 

        }
    }

    $( ".vincular" ).click(function() { 
            var id = $(this).data('id');
            var uid = $(this).data('uid');
            var data = { id : id , mdl_user_dado : { conta_azul : uid }};

            $.post("/mdl-user/edit/"+id, data, function( res ) {
                document.location.reload(true);
            }, "json");
    });

</script>