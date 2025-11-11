<?php

use App\Logger;
use App\Http\Router;
use App\Http\Route;
use App\Http\Request;

set_exception_handler(function ($e) {
    $code = $e->getCode() ?: 500;
    $file = $e->getFile() ?: 'unknown file';
    $line = $e->getLine() ?: 0;
    $message = $e->getMessage() ?: 'An error occurred';
    $full = $message . ': ' . $file . ' on line ' . $line;
    $hidden = "An error occurred. Turn on APP_DEBUG to see more details.";

    try {
        Logger::error($full);
        $request = new Request();
        $router = new Router($request, []);
        $router->force(new Route('View\\ErrorController@error'), ['code' => $code, 'message' => (APP_DEBUG ? $full : $hidden)]);
    } catch (Exception $e) {
        // If an error occurs while handling the exception, display a simple message.
        // Cannot throw another exception since we are already in an exception handler
        echo "<h1>Error " . $code . "</h1><p>" . $message . "</p><p>This page appears because an error occurred while handling the previous error. Check the logs for more details.</p>";
    }
});
