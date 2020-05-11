<div class="ecmRepasseOrigem col-md-12">
    <?= $this->Form->create($ecmRepasseOrigem) ?>
    <fieldset>
        <legend><?= __('Edit Ecm Repasse Origem') ?></legend>
        <?php
            echo $this->Form->input('origem');
            echo $this->Form->input('visivel', ['type' => 'checkbox']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
