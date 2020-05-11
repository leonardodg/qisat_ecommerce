<div class="ecmCupom col-md-12">
    <h3><?= __('Lista de Cupons') ?></h3>

    <button type="button"
            onclick="window.location.href='<?= \Cake\Routing\Router::url(['controller' => '', 'action' => 'validar-cupom'])?>'">
        <?= __('Prosseguir para a compra') ?>
    </button>

    <?= $this->element('comprando_para',['usuario'=>$usuario]);?>

    <?= $this->element('lista_cupom',['listaCupons'=>$listaCupons, 'acao' => true,'titulo' => __('Cupons disponíveis que o usuário não possui')]);?>

    <?= $this->element('lista_cupom',['listaCupons'=>$listaCuponsAltoQi,
        'titulo' => __('Cupons AltoQi que o usuário possui'),'situacao' => true
    ]);?>

    <?= $this->element('lista_cupom',['listaCupons'=>$listaCuponsQiSat,
        'titulo' => __('Demais Cupons que o usuário possui'),'situacao' => true
    ]);?>

</div>