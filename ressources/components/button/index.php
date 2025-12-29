<?php

use App\Helpers;

$id = isset($id) ? $id : Helpers::randomHex(8);

?>

<button
    id="<?= $id ?>"
    type="<?= $type ?? 'button' ?>"
    class="button <?= $color ?? 'dark' ?> <?= (isset($outline) && $outline) ? 'outline' : '' ?> <?= (isset($hover) && $hover) ? 'hover' : '' ?>"
    <?= (isset($href) && $href) ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= icon($icon ?? '', 'currentColor', '16px') ?>
    <?= e($label ?? 'Cliquez') ?>
</button>