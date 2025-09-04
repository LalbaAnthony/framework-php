<?php

use App\Component; ?>

<?php Component::display('header') ?>

<main>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma première section', 'color' => 'red']) ?>
        <?php var_dump($posts); ?>
    </section>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma deuxième section', 'color' => 'orange']) ?>
        test
    </section>
</main>

<?php Component::display('footer') ?>