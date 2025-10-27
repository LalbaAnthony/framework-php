<?php

use App\Helpers;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        Helpers::dd(...$vars);
    }
}

if (!function_exists('dataGet')) {
    function dataGet(...$vars)
    {
        Helpers::dataGet(...$vars);
    }
}