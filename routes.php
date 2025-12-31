<?php

use App\Database\Migrator;
use App\Database\Seeder;
use App\Http\Route;

return [
    // Scripts
    '/seed' => [
        'GET'  => function ($request) {
            if (APP_ENV !== 'development') {
                printLine("This script can only be run in development mode.");
                exit;
            }

            Seeder::run();
            printLine("Database seeded successfully.");
        },
    ],
    '/migrate' => [
        'GET'  => function ($request) {
            if (APP_ENV !== 'development') {
                printLine("This script can only be run in development mode.");
                exit;
            }

            $migrator = new Migrator();
            $migrator->crawl();
            printLine("Database migrated successfully.");
        },
    ],
    '/reset' => [
        'GET'  => function ($request) {
            if (APP_ENV !== 'development') {
                printLine("This script can only be run in development mode.");
                exit;
            }

            $migrator = new Migrator();
            $migrator->crawl();
            printLine("Database migrated successfully.");

            Seeder::run();
            printLine("Database seeded successfully.");
        },
    ],
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
        'DELETE'  => new Route('View\\PostController@delete'),
    ],
    // API
    '/api' => [
        'GET'  => new Route('API\\DefaultController@index'),
    ],
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
