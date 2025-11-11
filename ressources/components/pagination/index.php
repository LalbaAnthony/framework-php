<?php

use App\Icon;

if ($lastPage <= 1) return;

?>

<div class="pagination">
    <!-- First page -->
    <?php if ($page > 2): ?>
        <span data-page="1" class="number">1</span>
    <?php endif; ?>
    <?php if ($page > 3): ?>
        <span class="dots">...</span>
    <?php endif; ?>

    <!-- Previous page -->
    <?php if ($page > 1): ?>
        <span data-page="<?= ($page - 1); ?>" class="number"><?= ($page - 1); ?></span>
    <?php endif; ?>

    <!-- Current page -->
    <span class="number active"><?= $page; ?></span>

    <!-- Next page -->
    <?php if ($page < $lastPage): ?>
        <span data-page="<?= ($page + 1); ?>" class="number"><?= ($page + 1); ?></span>
    <?php endif; ?>

    <!-- Last page -->
    <?php if ($page < $lastPage - 2): ?>
        <span class="dots">...</span>
    <?php endif; ?>
    <?php if ($page < $lastPage - 1): ?>
        <span data-page="<?= $lastPage; ?>" class="number"><?= $lastPage; ?></span>
    <?php endif; ?>
</div>