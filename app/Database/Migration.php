<?php

namespace App\Database;

use App\Database\Database;

/**
 * Class Migration
 *
 * This class is the base class for all migrations.
 */
abstract class Migration
{
    /**
     * Run the migrations.
     * 
     * @param Database $db The database instance.
     * @return void
     */
    abstract public function up(Database $db): void;

    /**
     * Reverse the migrations.
     * 
     * @param Database $db The database instance.
     * @return void
     */
    abstract public function down(Database $db): void;
}
