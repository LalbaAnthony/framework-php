<div class="pill"
    style="background-color: <?= $color ?? '#ccc' ?>; cursor: <?= (isset($href) && $href) ? 'pointer' : 'default' ?>;"
    <?= (isset($href) && $href) ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= icon($icon ?? '', 'currentColor', '16px') ?>
    <?= e($label ?? 'Send') ?>
</div>