<div class="ecmIndicacaoSegmento col-md-12">
    <?= $this->Form->create($ecmIndicacaoSegmento) ?>
    <fieldset>
        <legend><?= __('Edit Ecm Indicacao Segmento') ?></legend>
        <?php
            echo $this->Form->input('segmento');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
