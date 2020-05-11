<div class="related">
    <h4><?= $titulo ?></h4>

    <?php if(count($listaCupons) > 0):?>
        <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveCupom">
            <tr>
                <th><?= __('Nome') ?></th>
                <th><?= __('Descrição') ?></th>
                <th><?= __('Desconto') ?></th>
                <th><?= __('Inicio') ?></th>
                <th><?= __('Fim') ?></th>

                <?php if(isset($situacao) && $situacao):?>
                    <th><?= __('Habilitado') ?></th>
                    <th><?= __('Situação') ?></th>
                <?php endif;?>

                <?php if(isset($acao) && $acao):?>
                    <th><?= __('Ação') ?></th>
                <?php endif;?>
            </tr>

            <?php foreach ($listaCupons as $cupom): ?>
                <?php
                $desconto = is_null($cupom->descontovalor) ? $cupom->descontoporcentagem : $cupom->descontovalor;

                if(is_null($cupom->descontovalor)){
                    $desconto = number_format($cupom->descontoporcentagem, 2 , ',', '.').'%';
                }else{
                    $desconto = 'R$ '.number_format($cupom->descontovalor, 2 , ',', '.');
                }
                ?>
                <tr>
                    <td><?= h($cupom->nome) ?></td>
                    <td><?= h($cupom->descricao) ?></td>
                    <td><?= $desconto ?></td>
                    <td><?= \Cake\I18n\Time::parse($cupom->datainicio)->format('d/m/Y') ?></td>
                    <td><?= \Cake\I18n\Time::parse($cupom->datafim)->format('d/m/Y') ?></td>

                    <?php
                        if(isset($situacao) && $situacao){
                            echo '<td>' . ($cupom->habilitado == 'true' ? __('Sim') : __('Não')) . '</td>';

                            if($cupom->datafim >= new \Cake\I18n\FrozenDate()){
                                echo '<td style="color:#009900;">'.__('Ativo').'</td>';
                            }else{
                                echo '<td style="color:#993300;">'.__('Expirado').'</td>';
                            }
                        }
                    ?>
                    <?php if(isset($acao) && $acao):?>
                        <td class="actions">
                            <?= $this->Form->postLink(__('Vincular cupom ao usuário'), ['controller' => '', 'action' => 'lista-cupom', $cupom->id], ['confirm' => __('Tem certeza que deseja efetuar essa ação?')]) ?>
                        </td>
                    <?php endif;?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h5><?= __('Não há Cupom') ?>
    <?php endif; ?>
</div>