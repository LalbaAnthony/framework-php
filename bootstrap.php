<?php

use App\Database;
use App\Models\Model;
use App\Migrator;
use App\Seeder;

require_once __DIR__ . '/dotenvloader.php';

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/html.php';

require_once __DIR__ . '/autoloader.php';

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
Model::setDatabase($database);
Migrator::setDatabase($database);
Seeder::setDatabase($database);