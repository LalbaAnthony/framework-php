<?php

use App\Helpers;

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        Helpers::dd(...$vars);
    }
}

if (!function_exists('dataGet')) {
    function dataGet(...$vars): mixed
    {
        return Helpers::dataGet(...$vars);
    }
}