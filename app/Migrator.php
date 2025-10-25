<?php

namespace App;

use Exception;
use App\Database;
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
     * The Database instance.
     *
     * @var Database|null
     */
    protected static ?Database $db = null;

    /**
     * Char that will split the order and table name in migrations files.
     */
    private const MIGRATIONS_FILE_SEPARATOR = '-';

    /**
     * Path to the migrations directory.
     */
    private const MIGRATIONS_PATH = __DIR__ . '/../migrations';

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
     * Migrate the database with data.
     *
     * @return void
     */
    public function crawl(): void
    {
        if (!is_dir(self::MIGRATIONS_PATH)) throw new NotFoundException("The migrations path does not exist: " . self::MIGRATIONS_PATH);

        $files = glob(self::MIGRATIONS_PATH . '/*.sql');

        foreach ($files as $file) {
            $this->migrate($file);
        }
    }

    public function migrate(string $path): void
    {
        if (!file_exists($path)) throw new NotFoundException("The file $path does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        try {
            $basename = basename($path, '.sql'); // basename as 0-main
            $table = explode(self::MIGRATIONS_FILE_SEPARATOR, $basename)[1] ?? $basename;

            $sql = file_get_contents($path);
            if ($sql === false) throw new FileException("The file $path could not be read.");
            
            static::$db->query($sql);

            Logger::info("Migrated the database with file $path");
        } catch (Exception $e) {
            throw new DatabaseException("Error migrating the database with file $path: " . $e->getMessage());
        }
    }
}
