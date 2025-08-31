<?php

use App\Logger;
use App\Http\Router;
use App\Http\Request;


set_exception_handler(function ($e) {
    $code = $e->getCode() ?: 500;
    $file = $e->getFile() ?: 'unknown file';
    $line = $e->getLine() ?: 0;
    $message = $e->getMessage() ?: 'An error occurred';

    Logger::error($message . ' in ' . $file . ' on line ' . $line);
    http_response_code($code);

    try {
        $request = new Request();
        $router = new Router($request, []);
        $router->setRoute([
            'type' => 'view',
            'path' => 'View\\ErrorController@error404',
        ]);
        $router->execute();
    } catch (Exception $e) {
        // If an error occurs while handling the exception, display a simple message.
        // Cannot throw another exception since we are already in an exception handler.
        echo "<h1>Error {$code}</h1><p>{$message}</p>";
    }
});
