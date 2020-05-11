<br>

<div class="jumbotron">
  <p>
  token_access: <?= $token_access ?> <br>
  token_refresh: <?= $token_refresh ?> <br>
  token_data:  <?= $token_data ?>
  </p>

  <?= $this->Html->link('Login', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'index', 'login' => true ]),  [ 'class' => 'button' ]) ?>
  <?= $this->Html->link('Refresh', \Cake\Routing\Router::url([ 'plugin' => 'ContaAzul','controller' => false, 'action' => 'index', '?' => ['refresh' => true ]]),  [ 'class' => 'button' ]) ?>
</div>

