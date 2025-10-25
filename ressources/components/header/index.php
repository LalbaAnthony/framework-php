<?php

use App\Component;

?>

<header class="header">
    <div class="header-title">
        <a href="<?= APP_URL ?>">
            <h1 class="header-title"><?= APP_NAME_SHORT ?></h1>
        </a>
    </div>
    <form action="<?= APP_URL ?>/posts" method="get" id="header-search-form" class="header-search">
        <input id="header-search-input" type="search" name="search" placeholder="Rechercher" />
    </form>
    <div class="header-login">
        <?= Component::display('button', ['href' => APP_URL . '/login', 'label' => 'Connexion', 'color' => 'dark', 'outline' => true]) ?>
    </div>
</header>
