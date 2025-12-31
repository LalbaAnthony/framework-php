<?php

namespace App\Database;

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
     * @param Database $instance The database instance.
     */
    public static function init(Database $instance): void
    {
        if (self::$instance !== null) throw new Exception('Database already initialized');

        self::$instance = $instance;
    }

    /**
     * Get the Database instance.
     * 
     * @return Database
     */
    public static function get(): Database
    {
        if (self::$instance === null) throw new Exception('Database not initialized');

        return self::$instance;
    }
}
