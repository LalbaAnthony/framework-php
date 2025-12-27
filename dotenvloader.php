<?php

use App\Exceptions\NotFoundException;
use App\Exceptions\FileException;

$filename = __DIR__ . '/.env';

$_envs = [];

if (!file_exists($filename)) throw new Exception("Error: .env file not found.");
if (!is_readable($filename)) throw new Exception("Error: .env file is not readable.");

$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $line = trim($line);

    // Skip empty lines
    if ($line === '') {
        continue;
    }

    // Skip lines that start with #
    if (str_starts_with($line, '#')) {
        continue;
    }

    // Remove comments after #
    if (strpos($line, '#') !== false) {
        $line = substr($line, 0, strpos($line, '#'));
        $line = trim($line);
    }

    // Skip lines that are now empty
    if ($line === '') {
        continue;
    }

    // Split key=value
    if (strpos($line, '=') !== false) {
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $_envs[$key] = $value;
    }
}
