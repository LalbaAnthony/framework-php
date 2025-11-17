<?php

use App\Helpers;

$action = isset($action) ? $action : Helpers::currentUrl(false);
$method = isset($method) ? $method : 'GET';

?>

<form action="<?= $action ?>" method="<?= $method ?>" id="search-form" class="search-form">
    <input id="search-input" class="search-input" type="search" name="search" placeholder="<?= $placeholder ?? 'Search' ?>" value="<?= isset($search) ? e($search) : '' ?>" />
    <?= component('button', ['label' => 'Search', 'type' => 'submit', 'color' => 'dark']) ?>
</form>