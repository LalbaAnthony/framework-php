<?php

use App\Util\Helpers;
use App\View\Component;
use App\Http\Router;
use App\View\Icon;

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

if (!function_exists('printLine')) {
    function printLine(...$vars): void
    {
        Helpers::printLine(...$vars);
    }
}

if (!function_exists('lorem')) {
    function lorem(...$vars): string
    {
        return Helpers::lorem(...$vars);
    }
}

if (!function_exists('jsonDecode')) {
    function jsonDecode(...$vars): mixed
    {
        return Helpers::jsonDecode(...$vars);
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

if (!function_exists('methodInputTag')) {
    function methodInputTag(...$vars): string
    {
        return Router::methodInputTag(...$vars);
    }
}
