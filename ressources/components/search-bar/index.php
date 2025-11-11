<?php

use App\Component;

$action = isset($action) ? $action : APP_URL;
$method = isset($method) ? $method : 'get';

?>

<form action="<?= $action ?>" method="<?= $method ?>" id="search-form" class="search-form">
    <input id="search-input" class="search-input" type="search" name="search" placeholder="<?= $placeholder ?? 'Search' ?>" />
    <?= Component::display('button', ['label' => 'Search', 'type' => 'submit', 'color' => 'dark']) ?>
</form>