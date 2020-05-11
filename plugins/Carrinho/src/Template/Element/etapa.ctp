<?php
$styleEtapa = 'background-color: #EEEEEE;border: 1px solid #CCCCCC;height: 50px;';
$styleEtapaInativa = 'background-color: #2a6496;border: 1px solid #CCCCCC;color: #FFF;height: 50px;';
?>
<div class="col-lg-12">
    <div class="col-lg-3" style="<?= $etapa==1? $styleEtapaInativa:$styleEtapa?>">
        <?= __('Montar Carrinho')?>
    </div>
    <div class="col-lg-3" style="<?= $etapa==2? $styleEtapaInativa:$styleEtapa?>">
        <?= __('Confirmação de Dados')?>
    </div>
    <div class="col-lg-3" style="<?= $etapa==3? $styleEtapaInativa:$styleEtapa?>">
        <?= __('Confirmação da Compra')?>
    </div>
    <div class="col-lg-3" style="<?= $etapa==4? $styleEtapaInativa:$styleEtapa?>">
        <?= __('Agendar Cursos')?>
    </div>
</div>