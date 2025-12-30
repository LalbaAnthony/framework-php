<?php

use App\Util\Helpers;

$type = isset($type) ? $type : 'info';
$message = isset($message) ? $message : '';

?>

<div class="alert-wrapper">
    <div class="alert-message <?= e($type) ?>">
        <span><?= e(Helpers::stringLimit($message)) ?></span>
    </div>
</div>