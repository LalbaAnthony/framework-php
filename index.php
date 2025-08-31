<?php

use App\Http\Router;
use App\Http\Request;

require_once __DIR__ . '/bootstrap.php';

session_start();

$routes = require_once __DIR__ . '/routes.php';

// Dispatch the request
$request = new Request();
$router = new Router($request, $routes);
$router->dispatch();
