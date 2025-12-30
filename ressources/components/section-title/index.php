<?php

use App\Util\Helpers;

?>

<div class="section-title-wrap">
    <?php if (isset($goback) && $goback): ?>
        <div id="goback" class="section-title-goback">
            <span><?php icon('arrow-left', 'var(--dark)', '30px') ?></span>
        </div>
    <?php endif; ?>
    <h4 id="<?= Helpers::slugify($title) ?>" class="section-title-text">
        <?= $title ?? '' ?>
    </h4>
    <a href="#<?= Helpers::slugify($title) ?>" class="section-title-hastage" style="color: <?= $color ?? 'var(--dark)' ?>;">#</a>
</div>