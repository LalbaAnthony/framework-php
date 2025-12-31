<?php

namespace App\Database;

use Exception;
use App\Database\Database;
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
     * Char that will split the order and table name in seeds files.
     */
    private const SEEDS_FILE_SEPARATOR = '-';

    /**
     * Path to the seeds directory.
     */
    private const SEEDS_PATH = __DIR__ . '/../../seeds';

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
     * List of seed files.
     *
     * @var array
     */
    private array $files = [];

    /**
     * Runs the seeder to populate the database.
     *
     * @return void
     * @throws NotFoundException
     * @throws FileException
     * @throws DatabaseException
     */
    public static function run(): void
    {
        $seeder = new self();
        $seeder->crawl();
        $seeder->execute();
    }

    /**
     * Crawl for seed files.
     *
     * @return void
     */
    public function crawl(): void
    {
        if (!is_dir(self::SEEDS_PATH)) throw new NotFoundException("The seeds directory does not exist.");

        $files = glob(self::SEEDS_PATH . '/*.json');
        sort($files);

        $this->files = $files;
    }

    /**
     * Seeds the database with data from all seed files.
     *
     * @return void
     * @throws NotFoundException
     * @throws FileException
     * @throws DatabaseException
     */
    public function execute(): void
    {
        foreach ($this->files as $file) {
            $this->seed($file);
        }
    }

    /**
     * Seeds the database with data from a specific file.
     *
     * @param string $path The path to the seed file.
     * @return void
     * @throws NotFoundException
     * @throws FileException
     * @throws DatabaseException
     */
    public function seed(string $path): void
    {
        if (!file_exists($path)) throw new NotFoundException("The file $path does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        try {
            $basename = basename($path, '.json'); // basename as 0-user
            $table = explode(self::SEEDS_FILE_SEPARATOR, $basename)[1] ?? $basename;

            $data = jsonDecode(file_get_contents($path), true);
            if (empty($data)) return;

            self::db()->resetAutoIncrement($table);
            self::db()->truncate($table);
            self::db()->bulkCreate($table, $data);
        } catch (Exception $e) {
            throw new DatabaseException("Error seeding the database with file $path: " . $e->getMessage());
        }
    }
}
