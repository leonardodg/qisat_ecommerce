<?php
if(!empty($params['escape'])){
    $message = $params['escape']? h($message) : $message;
}
$onclick = 'onclick="this.classList.add(\'hidden\');"';
if(!empty($params['hiddenClick'])){
    $onclick = !$params['hiddenClick']? '' : $onclick;
}
?>

<div class="message success" <?= $onclick?>><?= $message ?></div>
