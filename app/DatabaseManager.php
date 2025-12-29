<?php

namespace App;

use Exception;

final class DatabaseManager
{
    /**
     * Singleton instance of Database
     */
    private static ?Database $instance = null;

    private function __construct() {} // Private constructor to prevent instantiation

    /**
     * Initialize the Database instance.
     * 
     * @param string $host
     * @param string $name
     * @param string $user
     * @param string $password
     */
    public static function init(string $host, string $name, string $user, string $password): void
    {
        if (self::$instance !== null) {
            throw new Exception('Database already initialized');
        }

        self::$instance = new Database($host, $name, $user, $password);
    }

    /**
     * Get the Database instance.
     * 
     * @return Database
     */
    public static function get(): Database
    {
        if (self::$instance === null) {
            throw new Exception('Database not initialized');
        }

        return self::$instance;
    }
}
