<?php

$filename = __DIR__ . '/.env';

$envs = [];

if (!file_exists($filename)) throw new Exception("The file $filename does not exist.");
if (!is_readable($filename)) throw new Exception("The file $filename is not readable.");

$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) {
        continue;
    }

    [$key, $value] = array_map('trim', explode('=', $line, 2));
    $envs[$key] = $value;
}