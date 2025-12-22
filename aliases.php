<?php

use App\Helpers;
use App\Component;
use App\Http\Router;
use App\Icon;

if (!function_exists('dump')) {
    function dump(...$vars): void
    {
        Helpers::dump(...$vars);
    }
}

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

if (!function_exists('e')) {
    function e(...$vars): string
    {
        return Helpers::e(...$vars);
    }
}

if (!function_exists('comp')) {
    function component(...$vars): void
    {
        Component::display(...$vars);
    }
}

if (!function_exists('icon')) {
    function icon(...$vars): void
    {
        Icon::display(...$vars);
    }
}

if (!function_exists('methodTag')) {
    function methodTag(...$vars): string
    {
        return Router::methodTag(...$vars);
    }
}
