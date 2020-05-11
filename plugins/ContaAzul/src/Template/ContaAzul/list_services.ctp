
<br>
<br>

<div class="row">
    <div class="col-md-12">
        <h3><?= __('Lista de Serviços - ContaAzul') ?></h3>

        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Filtro') ?></legend>


            <div class="row">
                <div class="col-md-8">
                    <?= $this->Form->input('nome', ['label' => "Buscar pelo Nome ou Sigla","onkeyup" => "filter()"]) ?>
                </div>

                  <div class="col-md-2">
                    <div class="input-group"> 
                    <span class="input-group-btn">
                        <?php if ($set): ?>
                            <?= $this->Html->link('Criar Serviço', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'service']),  [ 'class' => 'button' ]) ?>
                        <?php endif; ?>
                    </span>
                    </div><!-- /input-group -->
                   </div>
            </div>
        </fieldset>
        <?= $this->Form->end() ?>

        <table id="services" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th width="10%" > Identificação  </th>
                <th width="40%" > Nome </th>
                <th width="5%" > Curso </th>
                <th width="5%" > Valor </th>
                <th width="5%" > Custo </th>
                <th width="5%">  Ação </th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $curso): ?>
                <tr>
                    <td><?= $curso['id'] ?></td>
                    <td><?= h($curso['name']) ?></td>
                    <td><?= $curso['produto'] ?></td>
                    <td><?= $this->Number->currency($curso['value'], 'BRL') ?></td>
                    <td><?=  $this->Number->currency($curso['cost'], 'BRL') ?></td>
                    <td class="actions">
                        <?php if ($set): ?>
                            <?= $this->Html->link('', [ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'service', $curso['id']], ['title' => 'Editar', 'class' => 'glyphicon glyphicon-pencil'] ) ?>
                        <?php endif; ?>
                        <?php if ($del): ?>
                            <?= $this->Form->postLink('', ['plugin' => 'ContaAzul','controller' => false, 'action' => 'delService',  $curso['id'] ], ['confirm' => __('Confirma exclusão do Serviço? # {0}?', $curso['id']), 'title' => 'Deletar', 'class' => 'glyphicon glyphicon-remove' ]) ?>
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
    table = document.getElementById("services");
    tr = table.getElementsByTagName("tr");
    var i = tr.length;

        while (i--) {
            td = tr[i].getElementsByTagName("td")[1];
            td2 = tr[i].getElementsByTagName("td")[2];

            if (td || td2) {
                if ( (td.innerHTML.toUpperCase().indexOf(filter) > -1) || (td2.innerHTML.toUpperCase().indexOf(filter) > -1) ) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            } 

        }
    }
</script>