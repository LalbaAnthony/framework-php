<?php

use App\Http\Route;

return [
    // =================================================
    // Views
    // =================================================
    '/' => [
        'GET'  => new Route('View\\HomeController@index', 'view'),
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index', 'api'),
    ],
];
