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
    '/api/posts' => [
        'GET'  => new Route('API\\PostController@index'),
    ],
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index'),
    ],
];
