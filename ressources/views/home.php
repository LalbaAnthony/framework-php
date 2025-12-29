<?php

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => 'Home']) ?>
        <p>Welcome to the home page!</p>
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