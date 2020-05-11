<div class="ecmCursoPresencialInteresse col-md-12">
    <?php
    $nomeProduto = '';
    $nomeProdutoLink = '';

    if($ecmCursoPresencialInteresse->has('ecm_produto')){
        $nomeProduto = $ecmCursoPresencialInteresse->ecm_produto->nome;
        $nomeProdutoLink = $this->Html->link($ecmCursoPresencialInteresse->ecm_produto->nome, ['plugin' => 'produto', 'controller' => '', 'action' => 'view', $ecmCursoPresencialInteresse->ecm_produto->id]);
    }else if($ecmCursoPresencialInteresse->has('ecm_curso_presencial_turma')){


        $dataTurma = current($ecmCursoPresencialInteresse->ecm_curso_presencial_turma->ecm_curso_presencial_data);

        $cidade = $dataTurma->ecm_curso_presencial_local->mdl_cidade->nome;

        $cidade .= '/'.$dataTurma->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf;

        $nomeProduto = $ecmCursoPresencialInteresse->ecm_curso_presencial_turma->ecm_produto->nome.' - '.$cidade;
        $nomeProdutoLink = $this->Html->link($ecmCursoPresencialInteresse->ecm_curso_presencial_turma->ecm_produto->nome.' - '.$cidade,
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
    <h3><?= $nomeProduto ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Nome') ?></th>
            <td><?= $nome ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= $email ?></td>
        </tr>
        <tr>
            <th><?= __('Telefone') ?></th>
            <td><?= $telefone ?></td>
        </tr>
        <tr>
            <th><?= __('Produto') ?></th>
            <td><?= $nomeProdutoLink?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ecmCursoPresencialInteresse->id) ?></td>
        </tr>
    </table>
</div>
