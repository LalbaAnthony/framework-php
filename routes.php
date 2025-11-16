<?php

use App\Http\Route;

return [
    // Views
    '/' => [
        'GET'  => new Route('View\\HomeController@index'),
    ],
    // API
    '/api/posts' => [
        'GET'  => new Route('API\\PostController@index'),
    ],
    '/api/posts/{id}' => [
        'GET' => new Route('API\\PostController@show'),
    ],
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index'),
    ],
    '/api/categories/{id}' => [
        'GET' => new Route('API\\CategoryController@show'),
    ],
];
