<?php
if(isset($params['escape'])){
    $message = $params['escape']? h($message) : $message;
}

$onclick = 'onclick="this.classList.add(\'hidden\');"';
if(isset($params['hiddenClick'])){
    $onclick = $params['hiddenClick'] == true? $onclick : '';
}
?>

<div class="message error" <?= $onclick?>><?= $message ?></div>
