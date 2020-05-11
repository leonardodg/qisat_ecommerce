<div class="ecmRepasseOrigem col-md-12">
    <?= $this->Form->create($ecmRepasseOrigem) ?>
    <fieldset>
        <legend><?= __('Add Ecm Repasse Origem') ?></legend>
        <?php
            echo $this->Form->input('origem');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
