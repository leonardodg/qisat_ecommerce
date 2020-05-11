<div class="ecmNewsletter col-md-12">
    <?= $this->Form->create($ecmNewsletter) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('email',['label' => __('E-mail')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
