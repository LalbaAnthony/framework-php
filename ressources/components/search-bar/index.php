<?php

use App\Component;
use App\Helpers;

$action = isset($action) ? $action : APP_URL;
$method = isset($method) ? $method : 'GET';

?>

<form action="<?= $action ?>" method="<?= $method ?>" id="search-form" class="search-form">
    <input id="search-input" class="search-input" type="search" name="search" placeholder="<?= $placeholder ?? 'Search' ?>" value="<?= isset($search) ? e($search) : '' ?>" />
    <?= Component::display('button', ['label' => 'Search', 'type' => 'submit', 'color' => 'dark']) ?>
</form>