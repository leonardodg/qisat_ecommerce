<div class="ecmDuvidasFrequentes col-md-12">
    <?= $this->Form->create($ecmDuvidasFrequente) ?>
    <fieldset>
        <legend><?= __('Edit Ecm Duvidas Frequente') ?></legend>
        <?php
            echo $this->Form->input('titulo');
            echo $this->Form->input('url');
            echo $this->Form->input('ordem', ['min' => 1, 'max' => $count, 'value' => $count]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
