<?php

use App\Helpers;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        Helpers::dd(...$vars);
    }
}