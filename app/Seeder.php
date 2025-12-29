<?php

namespace App;

use Exception;
use App\Database;
use App\Logger;
use App\Exceptions\DatabaseException;
use App\Exceptions\FileException;
use App\Exceptions\NotFoundException;

/**
 * Class Seeder
 *
 * This class is used to seed the database with data.
 */
class Seeder
{
    /**
     * The Database instance.
     *
     * @var Database|null
     */
    protected static ?Database $db = null;

    /**
     * Char that will split the order and table name in seeds files.
     */
    private const SEEDS_FILE_SEPARATOR = '-';

    /**
     * Path to the seeds directory.
     */
    private const SEEDS_PATH = __DIR__ . '/../seeds';

    /**
     * Destructor to clean up the model.
     */
    public function __destruct()
    {
        static::$db = null;
    }

    /**
     * Set the database connection.
     *
     * @param Database $db
     */
    public static function setDatabase(Database $db): void
    {
        static::$db = $db;
    }

    /**
     * Reset the auto increment value of a table.
     * 
     * @param string $table
     * @return void
     */
    public function resetAutoIncrement(string $table): void
    {
        try {
            static::$db->execute("ALTER TABLE $table AUTO_INCREMENT = 1;");
        } catch (Exception $e) {
            throw new DatabaseException("Error resetting the auto increment of the table $table: " . $e->getMessage());
        }
    }

    /**
     * Remove all data from a table.
     * 
     * @param string $table
     *
     * @return void
     */
    public function truncate(string $table): void
    {
        try {
            static::$db->setForeignKeyChecks(false);
            static::$db->execute("TRUNCATE TABLE $table;");
            static::$db->execute("ALTER TABLE $table AUTO_INCREMENT = 1;");
            static::$db->setForeignKeyChecks(true);
        } catch (Exception $e) {
            throw new DatabaseException("Error truncating the table $table: " . $e->getMessage());
        }
    }

    /**
     * Seeds the database with data.
     *
     * @return void
     */
    public function crawl(): void
    {
        if (!is_dir(self::SEEDS_PATH)) throw new NotFoundException("The seeds directory does not exist.");

        $files = glob(self::SEEDS_PATH . '/*.json');

        foreach ($files as $file) {
            $this->seed($file);
        }
    }

    public function seed(string $path): void
    {
        if (!file_exists($path)) throw new NotFoundException("The file $path does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        try {
            $basename = basename($path, '.json'); // basename as 0-user
            $table = explode(self::SEEDS_FILE_SEPARATOR, $basename)[1] ?? $basename;

            $data = json_decode(file_get_contents($path), true);
            if (empty($data)) return;

            $this->resetAutoIncrement($table);
            $this->truncate($table);

            static::$db->setForeignKeyChecks(false);
            foreach ($data as $row) {
                $columns = implode(', ', array_keys($row));
                $values = implode("', '", array_map('addslashes', array_values($row)));
                $query = "INSERT INTO $table ($columns) VALUES ('$values');";
                static::$db->execute($query);
            }
            static::$db->setForeignKeyChecks(true);

            Logger::success("Seeded the database with file $path.");
        } catch (Exception $e) {
            throw new DatabaseException("Error seeding the database with file $path: " . $e->getMessage());
        }
    }
}
