<?php

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => 'Home']) ?>
        <p>Welcome to the home page!</p>
        <p>Go to the <a class="link" href="<?= APP_URL . '/posts' ?>">posts page</a> to see all posts.</p>
    </section>
</main>

<?php component('footer') ?>