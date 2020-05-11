<div class="ecmRepasseCategorias col-md-12">
    <?= $this->Form->create($ecmRepasseCategoria) ?>
    <fieldset>
        <legend><?= __('Add Categoria para os Repasses') ?></legend>
        <?php
            echo $this->Form->input('categoria');

            echo $this->Form->input('visivel', ['type' => 'checkbox']);

            echo $this->Form->input('email', ['type' => 'checkbox']);
            echo $this->Form->input('recaptcha', ['type' => 'checkbox']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
