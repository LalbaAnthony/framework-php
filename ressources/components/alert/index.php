<?php

use App\Helpers;

$type = isset($type) ? $type : 'info';
$message = isset($message) ? $message : '';

?>

<div class="alert alert-<?= e($type) ?>">
    <?= e(Helpers::stringLimit($message)) ?>
</div>
