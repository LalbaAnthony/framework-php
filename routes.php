<?php

use App\Http\Route;

return [
    // =================================================
    // Views
    // =================================================
    '/' => [
        'GET'  => new Route('View\\HomeController@index'),
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index'),
    ],
];
