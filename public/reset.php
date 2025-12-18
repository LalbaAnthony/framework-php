<?php

use App\Migrator;
use App\Seeder;

require_once __DIR__ . '/../bootstrap.php';

if (APP_ENV !== 'development') {
    print "This script can only be run in development mode.\n";
    exit;
}

// Migrate the database
$migrator = new Migrator();
$migrator->crawl();
print "Database migrated successfully.\n";

// Seed the database
$seeder = new Seeder();
$seeder->crawl();
print "Database seeded successfully.\n";
