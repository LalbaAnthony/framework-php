<?php

namespace App\Database;

use App\Exceptions\NotFoundException;
use App\Exceptions\FileException;
use App\Exceptions\DatabaseException;

/**
 * Class Migrator
 *
 * This class is used to migrate the database with data.
 */
class Migrator
{
    /**
     * Path to the migrations directory.
     */
    private const MIGRATIONS_PATH = __DIR__ . '/../Migrations';

    /**
     * The Database instance.
     *
     * @var Database|null
     */
    protected static function db(): Database
    {
        return DatabaseManager::get();
    }

    /**
     * List of migration files.
     *
     * @var Migration[]
     */
    private array $migrations = [];

    /**
     * Runs the migrator to populate the database.
     *
     * @return void
     */
    public static function run(): void
    {
        $migrator = new self();
        $migrator->crawl();
        $migrator->execute();
    }

    /**
     * Crawl for migration files.
     *
     * @return void
     */
    public function crawl(): void
    {
        if (!is_dir(self::MIGRATIONS_PATH)) throw new NotFoundException("The migrations directory does not exist.");

        $files = glob(self::MIGRATIONS_PATH . '/*.php');
        sort($files);

        foreach ($files as $file) {
            require_once $file;
            $migration = include $file;
            $this->migrations[] = $migration;
        }
    }

    /**
     * Migrate the database with data from all migration files.
     *
     * @return void
     */
    public function execute(): void
    {
        // First, down all migrations to reset the database.
        // ! Execute in reverse order to avoid foreign key constraint issues.
        for ($i = count($this->migrations) - 1; $i >= 0; $i--) {
            $migration = $this->migrations[$i];
            $migration->down(self::db());
        }

        // Then, up all migrations to populate the database.
        for ($i = 0; $i < count($this->migrations); $i++) {
            $migration = $this->migrations[$i];
            $migration->up(self::db());
        }
    }
}
