<?php
echo $this->JqueryUI->getScript();

$atributos = array (
    'changeMonth' => true,
    'changeYear' => true,
    'numberOfMonths' => 2,
    'maxDate' => 0,
    'showButtonPanel' => true
);

$atributos ['onClose'] = 'function( selectedDate ) {
                                 $( "#data-fim" ).datepicker( "option", "minDate", selectedDate );
                             }';

$atributosDate ['#data-inicio'] = $atributos;

$atributos ['onClose'] = 'function( selectedDate ) {
                                $( "#data-inicio" ).datepicker( "option", "maxDate", selectedDate );
                             }';

$atributosDate ['#data-fim'] = $atributos;

$datePicker = $this->JqueryUI->datePicker ( array (
    '#data-inicio',
    '#data-fim'
), $atributosDate );

$scripts = $this->Jquery->domReady($datePicker);

echo $this->Html->scriptBlock($scripts);

?>

<div class="ecmCursoPresencialInteresse col-md-12">
    <?= $this->Form->create($interesse, ['type' =>'get',
                    'url' => ['controller' => 'EcmCursoPresencialInteresse', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('nome_pesquisa', ['label' => __('Nome')]);
            echo $this->Form->input('email_pesquisa', ['label' => __('E-mail')]);
            echo $this->Form->input('ecm_produto_id', ['label' => __('Curso Presencial'),
                'options' => $ecmProduto, 'empty' => __('Todos')]);
            echo $this->Form->input('data_inicio', ['label' => __('Data de Inicio'), 'type'=>'text']);
            echo $this->Form->input('data_fim', ['label' => __('Data de Fim'),'type'=>'text']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>
</div>

<div class="ecmCursoPresencialInteresse col-md-12">
    <h3><?= __('Lista de Interesse em Curso Presencial') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="large-1"><?= $this->Paginator->sort('nome') ?></th>
                <th class="large-2"><?= $this->Paginator->sort('email', __('E-mail')) ?></th>
                <th class="large-1"><?= $this->Paginator->sort('telefone') ?></th>
                <th class="large-1"><?= $this->Paginator->sort('ecm_produto_id', __('Produto')) ?></th>
                <th class="large-1"><?= $this->Paginator->sort('data', __('Data de Registro')) ?></th>
                <th class="actions large-1"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmCursoPresencialInteresse as $ecmCursoPresencialInteresse): ?>

                <?php
                    $nomeProduto = '';

                    if($ecmCursoPresencialInteresse->has('ecm_produto')){
                        $nomeProduto = $this->Html->link($ecmCursoPresencialInteresse->ecm_produto->nome, ['plugin' => 'produto', 'controller' => '', 'action' => 'view', $ecmCursoPresencialInteresse->ecm_produto->id]);
                    }else if($ecmCursoPresencialInteresse->has('ecm_curso_presencial_turma')){

                        $dataTurma = current($ecmCursoPresencialInteresse->ecm_curso_presencial_turma->ecm_curso_presencial_data);

                        $cidade = $dataTurma->ecm_curso_presencial_local->mdl_cidade->nome;

                        $cidade .= '/'.$dataTurma->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;

                        $nomeProduto = $this->Html->link($ecmCursoPresencialInteresse->ecm_curso_presencial_turma->ecm_produto->nome.' - '.$cidade,
                            ['controller' => 'turma', 'action' => 'view', $ecmCursoPresencialInteresse->ecm_curso_presencial_turma->id]);
                    }

                $nome = $ecmCursoPresencialInteresse->nome;
                $email = $ecmCursoPresencialInteresse->email;
                $telefone = $ecmCursoPresencialInteresse->telefone;

                if($ecmCursoPresencialInteresse->has('mdl_user')){
                    $nome = $ecmCursoPresencialInteresse->mdl_user->idnumber.' - ';
                    $nome .= $ecmCursoPresencialInteresse->mdl_user->firstname.' '.$ecmCursoPresencialInteresse->mdl_user->lastname;
                    $email = $ecmCursoPresencialInteresse->mdl_user->email;
                    $telefone = $ecmCursoPresencialInteresse->mdl_user->phone1;

                    if(strlen(trim($ecmCursoPresencialInteresse->mdl_user->phone2)) > 0) {
                        $telefone = strlen(trim($telefone)) > 0?$telefone.'/':$telefone;
                        $telefone .= $ecmCursoPresencialInteresse->mdl_user->phone2;
                    }
                }
                ?>
            <tr>
                <td><?= $nome ?></td>
                <td><?= $email ?></td>
                <td><?= $telefone ?></td>
                <td><?= $nomeProduto ?></td>
                <td><?= h($ecmCursoPresencialInteresse->data) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'lista-interesse','action' => 'view', $ecmCursoPresencialInteresse->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'lista-interesse','action' => 'edit', $ecmCursoPresencialInteresse->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'lista-interesse','action' => 'delete', $ecmCursoPresencialInteresse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ecmCursoPresencialInteresse->id)]) ?>
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
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
