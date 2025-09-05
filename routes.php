<?php

use App\Http\Route;

return [
    // =================================================
    // Views
    // =================================================
    '/' => [
        'GET'  => new Route('View\\HomeControllerindex'),
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index'),
    ],
];
