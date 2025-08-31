<?php

use App\Logger;
use App\Http\Router;
use App\Http\Request;


set_exception_handler(function ($e) {
    $code = $e->getCode() ?: 500;

    Logger::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    http_response_code($code);

    echo "<h1>Error {$code}</h1><p>{$e->getMessage()}</p>";
});
