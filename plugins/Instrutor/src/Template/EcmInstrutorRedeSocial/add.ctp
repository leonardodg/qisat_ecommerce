<div class="ecmInstrutorRedeSocial col-md-12">
    <?= $this->Form->create($ecmInstrutorRedeSocial) ?>
    <fieldset>
        <legend><?= $titulo ?></legend>
        <?php
            echo $this->Form->input('ecm_rede_social_id', ['label' => __('Rede social'), 'options' => $ecmRedeSocial]);
            echo $this->Form->input('link');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
