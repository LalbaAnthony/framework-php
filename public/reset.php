<?php

use App\Database\Migrator;
use App\Database\Seeder;

require_once __DIR__ . '/../bootstrap.php';

if (APP_ENV !== 'development') {
    printLine("This script can only be run in development mode.");
    exit;
}

// Migrate the database
$migrator = new Migrator();
$migrator->crawl();
printLine("Database migrated successfully.");

// Seed the database
$seeder = new Seeder();
$seeder->crawl();
printLine("Database seeded successfully.");
