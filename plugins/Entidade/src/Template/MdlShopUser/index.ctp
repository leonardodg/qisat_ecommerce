<?= $this->JqueryMask->getScript(); ?>
<?php
$script = $this->Jquery->domReady($this->JqueryMask->mask('#cpf', ['999.999.999-99']));
echo $this->Html->scriptBlock($script);
?>
<div class="mdlShopUser col-md-12">
    <h3><?= __('Lista de usuários da entidades') ?></h3>

    <?= $this->Form->create(null, ['type'=>'get','url' => ['controller' => 'MdlShopUser', 'action' => 'index']]) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
        $options = [1 => __('Sim'), 0 => __('Não')];

        echo $this->Form->input('nome');
        echo $this->Form->input('cpf', ['label' => __('CPF')]);
        echo $this->Form->input('numero', ['label' => __('Número do CREA')]);
        echo $this->Form->input('adimplente', ['options' => $options, 'empty' => __('Todos')]);
        echo $this->Form->input('confirmado', ['options' => $options, 'empty' => __('Todos')]);
        echo $this->Form->input('registrado', ['label' => __('Usuários Registrados'), 'options' => $options, 'empty' => __('Todos')]);
        echo $this->Form->input('entidade', ['options' => $optionsEntidade, 'empty' => __('Todos')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Buscar')) ?>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('nome') ?></th>
                <th><?= __('Nº CREA') ?></th>
                <th><?= __('Chave AltoQi') ?></th>
                <th><?= $this->Paginator->sort('shortname', __('Entidade')) ?></th>
                <th><?= $this->Paginator->sort('adimplente') ?></th>
                <th><?= $this->Paginator->sort('confirmado') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaUsuarios as $usuario): ?>
                <?php
                $cpf = $usuario->get('cpf');
                if(is_numeric($usuario->get('cpf'))) {
                    $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
                }
                ?>
            <tr>
                <td><?= $usuario->get('nome').'<br /><b>'.$cpf.'</b>' ?></td>
                <td><?= $usuario->get('numero') ?></td>
                <td><?= $usuario->get('chave_altoqi') ?></td>
                <td><?= $usuario->get('ecm_alternative_host')->get('shortname');?></td>
                <td>
                    <?php
                    $labelAlterar = __('Sim');
                    $entidade = null;

                    $entidade = $usuario->get('ecm_alternative_host')->get('id');

                    if($usuario->get('adimplente') == 1) {
                        echo '<span style="color:green;">' . __('Sim') . '</span>';
                        $labelAlterar = __('Não');
                    }else{
                        echo '<span style="color:red;">' . __('Não') . '</span>';
                    }

                    echo '<br />'.$this->Form->postLink(
                            __('Alterar para '.$labelAlterar),
                            ['action' => 'alterarStatus', $usuario->id, $entidade, 'adimplente'],
                            ['confirm' => __('Deseja realmente alterar esse registro?')]);
                    ?>
                </td>
                <td>
                    <?php
                    $labelAlterar = __('Sim');

                    if($usuario->get('confirmado') == 1){
                        echo '<span style="color:green;">'.__('Sim').'</span>';
                        $labelAlterar = __('Não');
                    }else{
                        echo '<span style="color:red;">' . __('Não') . '</span>';
                    }

                    echo '<br />'.$this->Form->postLink(
                            __('Alterar para '.$labelAlterar),
                            ['action' => 'alterarStatus', $usuario->id, $entidade, 'confirmado'],
                            ['confirm' => __('Deseja realmente alterar esse registro?')]);

                    ?>
                </td>
                <td class="actions">
                    <?php
                    if(empty($usuario->get('chave_altoqi'))){
                        echo $this->Html->link(__('Edit'), ['action' => 'edit', $usuario->id]);
                    }
                    ?>
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
