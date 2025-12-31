<?php

namespace App\Database;

use PDO;
use PDOException;
use PDOStatement;
use App\Exceptions\DatabaseException;

/**
 * Class Database
 * 
 * This class is used to interact with the database.
 */
class Database
{
    /**
     * Hostname of the database server.
     */
    private string $host = '';

    /**
     * Database name.
     */
    private string $name = '';

    /**
     * Username to connect to the database.
     */
    private string $user = '';

    /**
     * Password to connect to the database.
     */
    private string $password = '';

    /**
     * Connection to the database.
     */
    private ?PDO $connection = null;

    /**
     * Database class constructor.
     */
    public function __construct(string $host = '', string $name = '', string $user = '', string $password = '')
    {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
        $this->connect();
    }

    /**
     * Destructor to close the connection.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Establishes a connection to the database.
     * 
     * @return void
     * @throws DatabaseException If the connection fails.
     */
    private function connect(): void
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4",
                $this->user,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new DatabaseException("Error connecting to the database: " . $e->getMessage());
        }
    }

    /**
     * Closes the connection to the database.
     * 
     * @return void
     */
    public function close(): void
    {
        $this->connection = null;
    }

    /**
     * Ensures that a connection to the database is established.
     * 
     * @return void
     */
    private function connectIfIsnt(): void
    {
        if (!$this->connection) $this->connect();
    }

    /**
     * Enables or disables foreign key checks.
     * 
     * @param bool $enabled True to enable, false to disable.
     * @return void
     * @throws DatabaseException
     */
    public function setForeignKeyChecks(bool $enabled): void
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = " . ($enabled ? '1' : '0'));
    }

    /**
     * Checks if a table exists in the database.
     * 
     * @param string $table The name of the table.
     * @return bool True if the table exists, false otherwise.
     * @throws DatabaseException
     */
    public function tableExists(string $table): bool
    {
        $result = $this->fetchFirst("SHOW TABLES LIKE ?", [$table]);
        return $result !== false;
    }

    /**
     * Deletes all data from a table.
     * 
     * @param string $table The name of the table.
     * @return void
     * @throws DatabaseException
     */
    public function truncate(string $table, bool $fkCheck = false): void
    {
        if (!$fkCheck) $this->setForeignKeyChecks(false);

        if ($this->tableExists($table)) {
            $this->execute("TRUNCATE TABLE `$table`");
        }

        if (!$fkCheck) $this->setForeignKeyChecks(true);
    }

    /**
     * Drops a table from the database.
     * 
     * @param string $table The name of the table.
     * @return void
     * @throws DatabaseException
     */
    public function drop(string $table, bool $fkCheck = false): void
    {
        if (!$fkCheck) $this->setForeignKeyChecks(false);

        $this->truncate($table, $fkCheck);
        $this->execute("DROP TABLE IF EXISTS `$table`");

        if (!$fkCheck) $this->setForeignKeyChecks(true);
    }

    /**
     * Resets the auto increment value of a table.
     * 
     * @param string $table The name of the table.
     * @return void
     * @throws DatabaseException
     */
    public function resetAutoIncrement(string $table): void
    {
        if ($this->tableExists($table)) {
            $this->execute("ALTER TABLE `$table` AUTO_INCREMENT = 1;");
        }
    }

    /**
     * Inserts multiple rows into a table.
     * 
     * @param string $table The name of the table.
     * @param array $data An array of associative arrays representing the rows to insert.
     * @return void
     * @throws DatabaseException
     */
    public function bulkCreate(string $table, array $data, bool $fkCheck = false): void
    {
        if (!$fkCheck) $this->setForeignKeyChecks(false);

        foreach ($data as $row) {
            $columns = implode(', ', array_keys($row));
            $values = implode("', '", array_map('addslashes', array_values($row)));
            $query = "INSERT INTO $table ($columns) VALUES ('$values');";
            $this->execute($query);
        }

        if (!$fkCheck) $this->setForeignKeyChecks(true);
    }

    /**
     * Rebuilds the query with the parameters.
     * 
     * @param string $query SQL request with placeholders.
     * @param array $params Parameters to inject into the request.
     * @return string
     * @throws DatabaseException
     */
    public function mergeQueryAndParams(string $query, array $params = []): string
    {
        // ! This method is unsecure and should be used for nothing but debugging.

        if (!is_string($query)) {
            throw new DatabaseException("Query must be a string.");
        }
        if (empty($params)) {
            return $query;
        }
        if (count($params) !== substr_count($query, '?')) {
            throw new DatabaseException("The number of parameters does not match the number of placeholders in the query.");
        }

        foreach ($params as $key => $value) {
            $query = str_replace($key, "\"" . $value . "\"", $query);
        }

        return $query;
    }

    /**
     * Binds values to a PDO statement.
     * 
     * @param PDOStatement $statement The PDO statement.
     * @param array $params Parameters to bind.
     * @return void
     * @throws DatabaseException
     */
    private static function bindValues(PDOStatement &$statement, array $params): void
    {
        if (!is_array($params)) {
            throw new DatabaseException("Params must be an array.");
        }
        if (empty($params)) {
            return;
        }

        // Handle positional (numeric) parameters.
        if (array_keys($params) === range(0, count($params) - 1)) {
            foreach ($params as $index => $value) {
                // PDO positional parameters are 1-indexed.
                $statement->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        } else {
            // Named parameters.
            foreach ($params as $key => $value) {
                $statement->bindValue($key, $value);
            }
        }
    }

    /**
     * Prepares and executes a query, returning the PDO statement.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return PDOStatement
     * @throws DatabaseException
     */
    private function executeQuery(string $query, array $params = []): PDOStatement
    {
        $this->connectIfIsnt();

        try {
            $statement = $this->connection->prepare($query);
            self::bindValues($statement, $params);
            $statement->execute();
            return $statement;
        } catch (PDOException $e) {
            throw new DatabaseException("Error executing the query: " . $this->mergeQueryAndParams($query, $params) .  " | " . $e->getMessage());
        }
    }

    /**
     * Executes a query that modifies data (INSERT, UPDATE, DELETE).
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return bool True on success, false on failure.
     * @throws DatabaseException
     */
    public function execute(string $query, array $params = []): bool
    {
        $statement = $this->executeQuery($query, $params);
        return $statement !== false; // Even if rowCount is 0, the query might have executed successfully.
    }

    /**
     * Executes a SELECT query and returns all results as an associative array.
     * This method is used by Model for fetching data.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return array The resulting rows.
     * @throws DatabaseException
     */
    public function query(string $query, array $params = []): array
    {
        $statement = $this->executeQuery($query, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches the first result of a SELECT query.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return array|false The first row of the result set, or false if none.
     * @throws DatabaseException
     */
    public function fetchFirst(string $query, array $params = [])
    {
        $statement = $this->executeQuery($query, $params);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the last inserted ID.
     * 
     * @return int
     * @throws DatabaseException
     */
    public function lastInsertId(): int
    {
        $this->connectIfIsnt();

        try {
            return (int) $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new DatabaseException("Error getting the last inserted id: " . $e->getMessage());
        }
    }

    /**
     * Begins a transaction.
     * 
     * @return void
     * @throws DatabaseException
     */
    public function startTransaction(): void
    {
        $this->connectIfIsnt();

        try {
            $this->connection->beginTransaction();
        } catch (PDOException $e) {
            throw new DatabaseException("Error starting the transaction: " . $e->getMessage());
        }
    }

    /**
     * Commits a transaction.
     * 
     * @return void
     * @throws DatabaseException
     */
    public function commit(): void
    {
        $this->connectIfIsnt();

        try {
            $this->connection->commit();
        } catch (PDOException $e) {
            throw new DatabaseException("Error committing the transaction: " . $e->getMessage());
        }
    }

    /**
     * Rolls back a transaction.
     * 
     * @return void
     * @throws DatabaseException
     */
    public function rollback(): void
    {
        $this->connectIfIsnt();

        try {
            $this->connection->rollBack();
        } catch (PDOException $e) {
            throw new DatabaseException("Error rolling back the transaction: " . $e->getMessage());
        }
    }
}
