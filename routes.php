<?php

use App\Http\Route;

return [
    // Views
    '/hello-world' => [
        'GET'  => function ($request) {
            echo "Hello World!";
            // var_dump($request);
        },
    ],
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
