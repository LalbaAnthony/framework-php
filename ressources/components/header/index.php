<?php

use App\Component;

?>

<header class="header">
    <div class="header-title">
        <a href="<?= APP_URL ?>">
            <h1 class="header-title"><?= APP_NAME_SHORT ?></h1>
        </a>
    </div>
    <div class="header-login">
        <?= comp('button', ['href' => APP_URL . '/login', 'label' => 'Connexion', 'color' => 'dark', 'outline' => true]) ?>
    </div>
</header>
