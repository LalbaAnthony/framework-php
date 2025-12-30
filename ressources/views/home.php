<?php

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => 'Reset the app']) ?>
        <p>Go to <a class="link" href="<?= APP_URL . '/reset.php' ?>">reset.php</a> to reset the application data.</p>
    </section>
    <section>
        <?php component('section-title', ['title' => 'API']) ?>
        <p>Go to the <a class="link" href="<?= APP_URL . '/api' ?>">API root</a>.</p>
    </section>
    <section>
        <?php component('section-title', ['title' => 'Posts']) ?>
        <p>Go to the <a class="link" href="<?= APP_URL . '/posts' ?>">posts page</a> to see all posts.</p>
    </section>
    <section>
        <?php component('section-title', ['title' => 'Buttons']) ?>
        <div>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'light', 'outline' => false]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'light', 'outline' => true]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'dark', 'outline' => false]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'dark', 'outline' => true]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'danger', 'outline' => false]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'warning', 'outline' => false]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'success', 'outline' => false]) ?>
            <?= component('button', ['href' => APP_URL, 'label' => 'Lorem', 'color' => 'info', 'outline' => false]) ?>
        </div>
    </section>
</main>

<?php component('footer') ?>