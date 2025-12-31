<?php

use App\Database\Database;
use App\Database\DatabaseManager;

require_once __DIR__ . '/autoloader.php';
require_once __DIR__ . '/exceptionhandler.php';

require_once __DIR__ . '/dotenvloader.php';

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/html.php';
require_once __DIR__ . '/config/routing.php';

unset($_envs); // Clean up $_envs variable once we're done with it

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (!gc_enabled()) gc_enable();

require_once __DIR__ . '/aliases.php';

DatabaseManager::init(new Database(DATABASE_HOST, DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD));
