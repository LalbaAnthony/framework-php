<?php

use App\Util\Logger;
use App\Http\Route;

set_exception_handler(function ($e) {
    global $router;

    $code = $e->getCode() ?: 500;
    $file = $e->getFile() ?: 'unknown file';
    $line = $e->getLine() ?: 0;
    $message = $e->getMessage() ?: 'An error occurred';

    $full = $message . ': ' . $file . ' on line ' . $line;

    $hidden = match ($code) {
        404 => 'Page not found.',
        default => 'An error occurred. Turn on APP_DEBUG to see more details.',
    };

    http_response_code($code);

    try {
        Logger::error($full);
        $router->force(new Route('View\\ErrorController@error'), ['code' => $code, 'message' => (APP_DEBUG ? $full : $hidden)]);
    } catch (Throwable $e) {
        // If an error occurs while handling the exception, display a simple message.
        // Cannot throw another exception since we are already in an exception handler
        echo "<h1 style='color: crimson;'>Error " . $code . "</h1>";
        echo "<p>This page appears because an error occurred while handling the previous error. Check the logs for more details.</p>";
        echo "<p>" . $message . "</p>";
    }
});
