<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>
<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->script('/webroot/js/bootbox.min.js') ?>
<div class="ecmCarrinho medium-12 large-12 columns content">
<?= $this->Form->button(__("Voltar"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='index/".$usuario->id."'"]) ?>
    <?= $this->Form->button(__("Retorna á Lista Usuários"), ["class" => "small-button", "type" => "button", "onclick" => "location.href='../mdl-user/listar-usuario'"]) ?>
    <?= $this->Html->link('Montar Carrinho', '/carrinho/montarcarrinho', ['class' => 'button right']) ?>
    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>
    <h3><?= __('Lista de Produtos') ?></h3>
    <?= $this->Html->link('Cursos Online', '/carrinho/listaprodutos', ['class' => 'button']) ?>
        <?= $this->Html->link('Cursos Presenciais', '/carrinho/listaprodutos/presencial', ['class' => 'button active']) ?>
        <?= $this->Html->link('Produto AltoQi', '/carrinho/produtos_altoqi/', ['class' => 'button']) ?>
    <legend><?= __('Filtro') ?></legend>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->input('produto', ['label' => 'Produto']) ?>
        <?= $this->Form->button('Buscar', ['type' => 'submit']) ?>
    </fieldset>
    <?= $this->Form->end() ?>
        <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="10%"><?= $this->Paginator->sort('sigla', null, [
                        'url' => ['controller' => false, 'presencial']]) ?></th>
                <th width="40%"><?= $this->Paginator->sort('nome', null, [
                        'url' => ['controller' => false, 'presencial']]) ?></th>
                <th width="30%" ><?= h('Turma') ?></th>
                <th width="10%" ><?= $this->Paginator->sort('preco', null, [
                        'url' => ['controller' => false, 'presencial']]) ?></th>
                <th width="10%" ><?= h('Ação') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecmProduto as $produto): ?>
                <?php foreach ($produto->ecm_curso_presencial_turma as $turma): ?>

                    <tr>
                        <td> <?= $produto->sigla ?> </td>
                        <td> <?= $produto->nome ?> </td>
                        <td> 
                            <div><b><?= $turma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome ?> -
                                    <?= $turma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf ?>
                                </b></div>
                            <?php foreach ($turma->ecm_curso_presencial_data as $data): ?>
                                <div><?= $data->datainicio->format('d/m/Y') ?></div>
                            <?php endforeach; ?>
                        </td>
                        <td> 
                            <?php if(isset($produto->datafim) && isset($produto->valorTotal)): ?>
                            <div>até <?= $produto->datafim ?></div>
                            <?php if($turma->valor_produto == 'true'): ?>
                                <span style="text-decoration: line-through;">R$ <?= $this->Number->precision($produto->preco, 2) ?></span>
                            <?php else: ?>
                                <span style="text-decoration: line-through;">R$ <?= $this->Number->precision($turma->valor, 2) ?></span>
                            <?php endif; ?>
                            <span> | R$ <?= $this->Number->precision($produto->valorTotal, 2) ?></span>
                            <?php else: ?>
                                <?php if($turma->valor_produto == 'true'): ?>
                                    <span>R$ <?= $this->Number->precision($produto->preco, 2) ?></span>
                                <?php else: ?>
                                    <span>R$ <?= $this->Number->precision($turma->valor, 2) ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td> 
                            <input type="button" data-produto="<?= $produto->id ?>" data-turma="<?= $turma->id ?>" value="Adicionar">
                        </td>
                    </tr>
                 <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<script>
    $("input[type='button']").click(function() {
        var produto = $(this).attr("data-produto");
        var turma = $(this).attr("data-turma");
        $.post("../add", {"produto": produto, "presencial": turma}, function(data) {
            if(!data.sucesso){
                bootbox.alert(data.mensagem);
            }
        }, "json");
    });
</script>
<script>
    $(document).ready(function(){
        $( document ).ajaxStart(function() {
            bootbox.dialog({
                title: "Carregando...",
                message: '<div style="text-align: center"><img src="../../webroot/img/preload/preload.gif"></div>',
                closeButton: false
            });
        });
        $( document ).ajaxStop(function() {
            bootbox.hideAll();
        });
    });
</script>