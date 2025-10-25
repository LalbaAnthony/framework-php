<?php

use App\Component; ?>

<?php Component::display('header') ?>

<main>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma première section', 'color' => '#eb4034']) ?>
        <?php var_dump($posts); ?>
    </section>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma deuxième section', 'color' => '#8c34eb']) ?>
        test
    </section>
</main>

<?php Component::display('footer') ?>