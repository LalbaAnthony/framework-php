<?php

use App\Component;

?>

<main>
    <div class="error-container">
        <h1 class="error-title"><?= $code ?? '500' ?></h1>
        <p class="error-description"><?= $message ?? '' ?></p>
        <?= Component::display('button', ['href' => APP_URL, 'label' => 'Accueil', 'color' => 'light', 'outline' => true]) ?>
    </div>
</main>

<style>
    .error-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    .error-title {
        font-size: 5rem;
    }

    .error-description {
        text-align: center;
        font-size: 20px;
    }
</style>