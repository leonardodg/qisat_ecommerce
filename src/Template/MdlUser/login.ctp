<?php $this->assign('title', __('Acesso ao Sistema'));?>

<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend>
            <?= __('Por favor informe seu usuário e senha') ?>
        </legend>
        <?= $this->Form->input('username', ['label' => __('Usuário')]) ?>
        <?= $this->Form->input('password', ['label' => __('Senha')]) ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>