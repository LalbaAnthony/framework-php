<?php

use App\Http\Route;

return [
    // Views
    '/hello' => [ // Example route with a closure
        'GET'  => function ($request) {
            $name = $request->params['name'] ?? 'Guest';
            echo "Hello $name!";
        },
    ],
    '/' => [
        'GET'  => new Route('View\\HomeController@index'),
    ],
    '/posts' => [
        'GET'  => new Route('View\\PostController@index'),
    ],
    '/posts/{id}' => [
        'GET'  => new Route('View\\PostController@show'),
        'PUT'  => new Route('View\\PostController@update'),
    ],
    // API
    '/api/posts' => [
        'GET'  => new Route('API\\PostController@index'),
    ],
    '/api/posts/{slug}' => [
        'GET' => new Route('API\\PostController@show'),
    ],
    '/api/categories' => [
        'GET'  => new Route('API\\CategoryController@index'),
    ],
    '/api/categories/{slug}' => [
        'GET' => new Route('API\\CategoryController@show'),
    ],
];
