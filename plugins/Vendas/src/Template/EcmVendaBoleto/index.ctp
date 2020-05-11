<?= $this->Dialog->getScript()?>
<?= $this->MutipleSelect->getScript();?>

<?php
    $attr = array(
        'width' => '460',
        'filter' => 'true',
        'multiple' => 'true',
        'position' => '"top"',
        'multipleWidth' => '250');

    $multiselect = $this->MutipleSelect->multipleSelect('#ecm-tipo-produto',$attr);
    $scripts = $this->Jquery->domReady($multiselect);

    echo $this->Html->scriptBlock($scripts);
?>
<div class="ecmVendaBoleto col-md-12">
    <h3><?= __('Vendas por Boleto') ?></h3>
    <?= $this->Form->create('', ['type' => 'GET']) ?>
    <fieldset>
        <legend><?= __('Filtro') ?></legend>
        <?php
            echo $this->Form->input('idnumber', ['label' => 'Chave AltoQi']);
            echo $this->Form->input('pedido', ['label' => 'Número do Pedido']);
            echo $this->Form->input('parcela', ['label' => 'Número da Parcela']);
            echo $this->Form->input('status', ['options' => $status]);
            echo $this->Form->input('ecm_tipo_produto', ['label'=>__('Tipo de produto'), 'multiple' => 'multiple', 'options' => $optionsTipoProduto]);
            echo $this->Form->button('Buscar', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('Chave AltoQi') ?></th>
                <th><?= $this->Paginator->sort('Pedido') ?></th>
                <th><?= $this->Paginator->sort('Usuário') ?></th>
                <th><?= $this->Paginator->sort('Parcelas') ?></th>
                <th width="20%"><?= $this->Paginator->sort('Status') ?></th>
                <th><?= $this->Paginator->sort('Imprimir') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmVendaBoleto as $ecmVendaBoleto): ?>
            <tr>
                <td><?= $this->Number->format($ecmVendaBoleto->ecm_venda->mdl_user->idnumber) ?></td>
                <td><?= h($ecmVendaBoleto->ecm_venda->pedido) ?></td>
                <td><?= h($ecmVendaBoleto->ecm_venda->mdl_user->firstname." ".$ecmVendaBoleto->ecm_venda->mdl_user->lastname) ?></td>
                <td><?= $this->Number->format($ecmVendaBoleto->parcela) ?></td>

                <?php if($ecmVendaBoleto->status == "Em aberto"):?>
                    <td>
                        <?= $this->Form->hidden("id", ['value' => $ecmVendaBoleto->id]); ?>
                        <div name="statusD<?= $ecmVendaBoleto->id ?>" onclick="abrirCombobox(this);" style="cursor:pointer;">
                            <span>
                                <?= h($ecmVendaBoleto->status=="nPago"?"Não Pago":$ecmVendaBoleto->status) ?>
                            </span>
                            <?= $this->Html->image("edit.gif") ?>
                        </div>
                        <?= $this->Form->input('statusC'.$ecmVendaBoleto->id,
                                [
                                    'options' => $status,
                                    'label' => false,
                                    'value' => $ecmVendaBoleto->status,
                                    'style' => 'min-width:100px;',
                                    'data-isfase' => $ecmVendaBoleto->is_fase
                                ]); ?>
                        <div name="delete" style="cursor:pointer;display:none;margin-left:105px;" onclick="fecharCombobox(this);">
                            <?= $this->Html->image("delete.gif") ?>
                        </div>
                    </td>
                <?php else:?>
                    <td>
                        <?= h($ecmVendaBoleto->status=="nPago"?"Não Pago":$ecmVendaBoleto->status) ?>
                    </td>
                <?php endif;?>
                <td>
                    <?php
                        echo $this->Form->create(null, ['url' => ['plugin' => 'FormaPagamento'.$formaPagamento->controller,
                            'controller' => '', 'action' => 'boleto'], 'target' => '_blank']);
                        echo $this->Form->hidden("id", ['value' => $ecmVendaBoleto->id]);
                        echo $this->Form->button("Boleto");
                        echo $this->Form->end();
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
<script>
    $(function(){
        var statusC = $('select[name*="statusC"]');
        statusC.find('option[value="Todos"]').remove();
        statusC.parent().css('float', 'left');
        statusC.change(function() {
            var statusC = $(this);
            var div = statusC.parent().parent().find('div[name*="statusD"]');
            var id = statusC.parent().parent().find('input[name="id"]').val();
            var status = statusC.val();

            if(status != 'Em aberto') {
                var mensagem = '';

                if ($(this).data().isfase == 1) {
                    mensagem = 'Esse boleto é referente a uma trilha, se seu status for alterado para ';

                    if (status == 'Pago') {
                        mensagem += '"Pago" ocorrerá a liberação de acesso aos cursos vinculados a esse pagamento.<br/>';
                    } else if (status == 'nPago') {
                        mensagem += '"Não Pago" ocorrerá a exclusão das matrículas da trilha que estão aguardando a confirmação desse pagamento.<br/>';
                    }
                }

                mensagem += 'Essa alteração não poderá ser desfeita.';
                mensagem += '<br />Deseja continuar?';

                bootbox.confirm({
                    title: "Atenção",
                    message: mensagem,
                    callback: function (result) {
                        if (result) {
                            $.ajax({
                                type: "POST",
                                url: '',
                                data: {id: id, status: status},
                                dataType: 'json',
                                success: function (data) {
                                    if(data.sucesso) {
                                        div.html(statusC.find('option:selected').text());
                                        div.removeAttr('onclick');
                                        div.removeAttr('style');
                                    }else{
                                        setTimeout(function(){ bootbox.alert(data.mensagem); }, 500);
                                    }
                                },
                                complete: function () {
                                    div.show();
                                    statusC.parent().parent().find('div[name="delete"]').hide();
                                    statusC.hide();
                                }
                            });
                        }else{
                            statusC.val('Em aberto');
                        }
                    }
                });
            }
        });
        statusC.hide();
    });
    function abrirCombobox(div){
        $('div[name*="statusD"]').show();
        $(div).hide();
        $('select[name*="statusC"]').hide();
        $('div[name="delete"]').hide();
        $(div).parent().find('select').show();
        $(div).parent().find('div[name="delete"]').show();
    }
    function fecharCombobox(div){
        $(div).parent().find('div').show();
        $(div).parent().find('select').hide();
        $(div).hide();
    }
</script>