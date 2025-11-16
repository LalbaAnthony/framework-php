<?php

use App\Icon;

?>

<button
    id="<?= $id ?? '' ?>"
    type="<?= $type ?? 'button' ?>"
    class="button <?= $color ?? 'light' ?> <?= (isset($outline) && $outline) ? 'outline' : '' ?>"
    <?= (isset($href) && $href) ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= icon($icon ?? '', 'currentColor', '16px') ?>
    <?= e($label ?? 'Cliquez') ?>
</button>