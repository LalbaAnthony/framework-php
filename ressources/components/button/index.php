<?php

use App\Icon;

?>

<button
    class="button <?= $color ?? 'light' ?> <?= (isset($outline) && $outline) ? 'outline' : ' ' ?>"
    type="<?= $type ?? 'button' ?>"
    <?= (isset($href) && $href) ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= Icon::display($icon ?? '', 'currentColor', '16px') ?>
    <?= e($label ?? 'Cliquez') ?>
</button>