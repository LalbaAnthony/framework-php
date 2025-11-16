<?php

use App\Component;

?>

<main>
    <div class="error-container">
        <h1 class="error-title"><?= $code ?? '500' ?></h1>
        <p class="error-description"><?= $message ?? '' ?></p>
        <div class="error-actions">
            <?= comp('button', ['href' => APP_URL, 'label' => 'Home', 'color' => 'light', 'outline' => true]) ?>
            <?= comp('button', ['id' => 'go-back', 'label' => 'Go back', 'color' => 'dark']) ?>
        </div>
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

    .error-actions {
        display: flex;
        gap: 1rem;
    }
</style>

<script>
    document.getElementById('go-back').addEventListener('click', function() {
        goBackSafe();
    });
</script>