<?php

return [
    // =================================================
    // Views
    // =================================================
    '/' => [
        'GET'  => new App\Http\Route('View\\HomeController@index', 'view', [
            'before' => ['components' => ['header']],
            'after' => ['components' => ['footer']],
        ]),
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => new App\Http\Route('API\\CategoryController@index', 'api'),
    ],
];
