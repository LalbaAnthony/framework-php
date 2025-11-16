<?php

use App\Helpers;

$types = ['text', 'password', 'email', 'number', 'url', 'tel', 'search', 'date', 'datetime-local', 'month', 'week', 'time', 'color', 'textarea'];
$autocompletes = ['text' => 'on', 'password' => 'current-password', 'email' => 'email', 'tel' => 'tel', 'url' => 'url', 'search' => 'search', 'name' => 'name'];

$id = isset($id) ? $id : Helpers::randomHex(8);

?>

<button
    id="<?= $id ?>"
    type="<?= $type ?? 'button' ?>"
    class="button <?= $color ?? 'light' ?> <?= (isset($outline) && $outline) ? 'outline' : '' ?>"
    <?= (isset($href) && $href) ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= icon($icon ?? '', 'currentColor', '16px') ?>
    <?= e($label ?? 'Cliquez') ?>
</button>