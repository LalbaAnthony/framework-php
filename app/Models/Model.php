<?php

namespace App\Models;

use App\Database;
use App\DatabaseManager;
use App\Helpers;
use App\Exceptions\DatabaseException;
use App\Exceptions\ModelException;

/**
 * Abstract base model class to provide Active Record style functionality.
 */
abstract class Model
{
    /**
     * Default values for properties that are common to all models.
     */
    const DEFAULT_PRIMARY_KEY = 'id';
    const DEFAULT_CREATED_AT = 'created_at';
    const DEFAULT_UPDATED_AT = 'updated_at';

    /**
     * Columns that should be hidden when converting the model to an array or JSON.
     */
    const DEFAULT_SENSITIVE_COLUMNS = ['id', 'created_at', 'updated_at'];

    /**
     * Maximum items per page for pagination.
     */
    const MAX_PER_PAGE = 99;

    /**
     * Default values for methods that accept optional parameters.
     */
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC']];

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
     * Construct a model with an optional data array.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Get the database table name.
     *
     * Each child class must implement this.
     *
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * Override this in child classes to specify which columns should be searched.
     *
     * @return array
     */
    abstract public static function getSearchableColumns(): array;

    /**
     * Get the primary key column name.
     *
     * Override this in child classes if your primary key differs.
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return static::DEFAULT_PRIMARY_KEY;
    }

    /**
     * Get the created_at column name.
     *
     * Override this in child classes if your created_at column differs.
     *
     * @return string
     */
    protected static function getUpdatedAtColumn(): string
    {
        return static::DEFAULT_UPDATED_AT;
    }

    /**
     * Get the updated_at column name.
     *
     * Override this in child classes if your updated_at column differs.
     *
     * @return string
     */
    protected static function getCreatedAtColumn(): string
    {
        return static::DEFAULT_CREATED_AT;
    }

    /**
     * Get the columns that should be hidden when converting to array or JSON.
     * 
     * Override this in child classes to specify sensitive columns.
     * 
     * @return array
     */
    public static function getSensitiveColumns(): array
    {
        return static::DEFAULT_SENSITIVE_COLUMNS;
    }

    /**
     * Convert the model to an associative array.
     *
     * @param array $options
     * @return array
     */
    public function toArray(array $options = ['includeSensitive' => true]): array
    {
        $array = get_object_vars($this);

        if (isset($options['includeSensitive']) && !$options['includeSensitive']) {
            foreach (static::getSensitiveColumns() as $column) {
                if (array_key_exists($column, $array)) {
                    unset($array[$column]);
                }
            }
        }

        return $array;
    }

    /**
     * Convert the model to an associative array, excluding sensitive columns.
     *
     * @return array
     */
    public function toArraySafe(): array
    {
        return $this->toArray(['includeSensitive' => false]);
    }

    /**
     * Parse an array of models or data into an array of associative arrays.
     *
     * @param $items
     * @param array $options
     * @return array
     */
    public static function parseArray($items, array $options): array
    {
        if (!is_array($items)) return [];
        if (empty($items)) return [];

        $parseds = array_map(function ($item) use ($options) {
            if ($item instanceof Model) { // Check if item is an instance or subclass of Model
                return $item->toArray($options);
            }
            return $item;
        }, $items);

        return $parseds;
    }

    /**
     * Parse an array of models or data into an array of associative arrays,
     * excluding sensitive columns.
     *
     * @param array $items
     * @return array
     */
    public static function parseArraySafe($items): array
    {
        return static::parseArray($items, ['includeSensitive' => false]);
    }

    /**
     * Fill the model's properties from an array.
     *
     * Only properties that already exist in the object will be set.
     *
     * @param array $data
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (gettype($value) === 'boolean') {
                    $this->$key = ($value == '1' or $value === true) ? true : false;
                    continue;
                }
                if (gettype($value) === 'string') {
                    $this->$key = (string) $value;
                    continue;
                }
                if (gettype($value) === 'integer') {
                    $this->$key = (int) $value;
                    continue;
                }
                if (gettype($value) === 'double') {
                    $this->$key = (float) $value;
                    continue;
                }
                if (gettype($value) === 'float') {
                    $this->$key = (float) $value;
                    continue;
                }
                if (gettype($value) === 'array') {
                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $this->$key = $decoded;
                            continue;
                        }
                    }
                    continue;
                }
                if (is_null($value)) {
                    $this->$key = NULL;
                    continue;
                }

                throw new ModelException("Unsupported property type for '$key' in " . static::class);
            } else {
                throw new ModelException("Property '$key' does not exist in " . static::class);
            }
        }
    }

    /**
     * Create a new record in the database.
     * 
     * Sets the primary key of the model upon successful insertion.
     *
     * @return bool
     * @throws DatabaseException
     */
    public function create(array $attributes = []): bool
    {
        if (array_key_exists($primaryKey, $attributes)) unset($attributes[$primaryKey]);

        $columns = array_keys($attributes);
        $placeholders = implode(", ", array_fill(0, count($columns), "?"));
        $params = array_values($attributes);

        $sql = "INSERT INTO " . static::getTableName() . " (" . implode(", ", $columns) . ") VALUES ($placeholders)";
        $result = self::db()->execute($sql, $params);

        if ($result) $this->$primaryKey = self::db()->lastInsertId();

        if ($result) return true;

        return false;
    }

    /**
     * Update a record in the database.
     * 
     * Uses the primary key of the current model instance to identify the record to update.
     *
     * @return bool
     * @throws DatabaseException
     */
    public function update(array $attributes = []): bool
    {
        $primaryKey = static::getPrimaryKey();
        if (!isset($this->$primaryKey)) {
            return false;
        }

        $columns = array_keys($attributes);
        $placeholders = implode(", ", array_map(fn($column) => "$column = ?", $columns));
        $params = array_values($attributes);

        $sql = "UPDATE " . static::getTableName() . " SET $placeholders WHERE $primaryKey = ?";
        $params[] = $this->$primaryKey;

        $result = self::db()->execute($sql, $params);

        if ($result) return true;

        return false;
    }

    /**
     * Save the current model to the database.
     *
     * Performs an UPDATE if the primary key exists, or an INSERT otherwise.
     *
     * @return bool
     * @throws DatabaseException
     */
    public function save(bool $refresh = true): bool
    {
        $primaryKey = static::getPrimaryKey();
        $isUpdate = isset($this->$primaryKey) && !empty($this->$primaryKey);
        $attributes = $this->toArray();

        foreach ($attributes as $column => &$value) {
            if ($column === static::getUpdatedAtColumn()) $value = Helpers::currentDateTime();
            if ($column === static::getCreatedAtColumn() && !$isUpdate) $value = Helpers::currentDateTime();
        }

        if ($isUpdate) {
            $result = $this->update($attributes);
        } else {
            $result = $this->create($attributes);
        }

        if ($refresh && $result) {
            $this->refresh();
        }

        if ($result) return true;

        return false;
    }

    /**
     * Delete the current model from the database.
     *
     * @return bool
     * @throws DatabaseException
     */
    public function delete(): bool
    {
        $primaryKey = static::getPrimaryKey();
        if (!isset($this->$primaryKey)) {
            return false;
        }
        $sql = "DELETE FROM " . static::getTableName() . " WHERE $primaryKey = ?";
        return self::db()->execute($sql, [$this->$primaryKey]);
    }

    /**
     * Refresh the current model's data from the database.
     *
     * @return bool
     * @throws DatabaseException
     */
    public function refresh(): bool
    {
        $primaryKey = static::getPrimaryKey();

        if (!isset($this->$primaryKey)) {
            return false;
        }

        $fresh = static::findByPk($this->$primaryKey);

        if ($fresh) {
            $this->fill($fresh->toArray());
            return true;
        }

        return false;
    }

    /**
     * Retrieve a record by its primary key.
     *
     * @param int $primaryKey
     * @return static|null
     * @throws DatabaseException
     */
    public static function findByPk(int $primaryKey): ?static
    {
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE " . static::getPrimaryKey() . " = ?";
        $result = self::db()->query($sql, [$primaryKey]);

        if ($result && count($result) > 0) {
            return new static($result[0]);
        }

        return null;
    }

    /**
     * Retrieve a record by a given column and its value.
     *
     * @param mixed $value
     * @param string $column
     * @return static|null
     * @throws DatabaseException
     */
    public static function findByCol(mixed $value, ?string $column = null, ?string $table = null): ?static
    {
        $column = $column ?? static::getPrimaryKey();
        $table = $table ?? static::getTableName();

        $sql = "SELECT * FROM $table WHERE $column = ?";
        $result = self::db()->query($sql, [$value]);

        if ($result && count($result) > 0) {
            return new static($result[0]);
        }

        return null;
    }

    /**
     * Retrieve the first record matching the given params.
     *
     * @param array $params
     * @return array
     * @throws DatabaseException
     */
    public static function findOne(array $params): ?static
    {
        $params['perPage'] = 1;
        [$data, $meta] = static::findAll($params);

        return $data[0] ?? null;
    }

    /**
     * Retrieve records matching the given conditions.
     *
     * @param array $params
     * @return static[]
     * @throws DatabaseException
     */
    public static function findAll(array $params = []): array
    {
        // Set default values for optional parameters.
        if (!isset($params['perPage']) || !$params['perPage']) $params['perPage'] = static::DEFAULT_PER_PAGE;
        if (!isset($params['page']) || !$params['page']) $params['page'] = static::DEFAULT_PAGE;
        if (!isset($params['sort']) || !$params['sort']) $params['sort'] = static::DEFAULT_SORT;

        // Checking parameters validity
        if (isset($params['perPage'])) {
            $params['perPage'] = (int)$params['perPage'];
            if ($params['perPage'] < 1) $params['perPage'] = static::DEFAULT_PER_PAGE;
            if ($params['perPage'] > static::MAX_PER_PAGE) $params['perPage'] = static::MAX_PER_PAGE;
        }
        if (isset($params['page'])) {
            $params['page'] = (int)$params['page'];
            if ($params['page'] < 1) $params['page'] = static::DEFAULT_PAGE;
        }
        if (isset($params['sort']) && !is_array($params['sort'])) {
            $params['sort'] = static::DEFAULT_SORT;
        }

        // Calculate pagination values.
        if (isset($params['page']) && isset($params['perPage'])) {
            $params['limit'] = (int)$params['perPage'];
            $params['offset'] = ((int)$params['page'] - 1) * (int)$params['perPage'];
        }

        $bindings = [];
        $and = '';

        // Build a secure search clause using placeholders.
        if (isset($params['search']) && $params['search']) {
            $search = $params['search'];
            $searchClauseParts = [];
            foreach (static::getSearchableColumns() as $column) {
                // Validate the column name to allow only safe characters.
                if (preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                    $searchClauseParts[] = "$column LIKE ?";
                    $bindings[] = "%$search%";
                }
            }
            if (!empty($searchClauseParts)) {
                $and .= " AND (" . implode(" OR ", $searchClauseParts) . ")";
            }
        }

        // Build a secure sort clause by validating each sort option.
        $sort = '';
        if (isset($params['sort']) && $params['sort']) {
            $sortParts = [];
            foreach ($params['sort'] as $sortOption) {
                $column = $sortOption['column'] ?? '';
                $order = strtoupper($sortOption['order'] ?? '');
                // Only allow column names with alphanumerics and underscores, and orders ASC/DESC.
                if (preg_match('/^[a-zA-Z0-9_]+$/', $column) && in_array($order, ['ASC', 'DESC'])) {
                    $sortParts[] = "$column $order";
                }
            }
            if (!empty($sortParts)) {
                $sort .= " ORDER BY " . implode(", ", $sortParts);
            }
        }

        // Build pagination clause.
        $pagination = '';
        if (isset($params['limit']) && $params['limit']) {
            $limit = (int)$params['limit'];
            $pagination .= " LIMIT $limit";
        }
        if (isset($params['offset']) && $params['offset']) {
            $offset = (int)$params['offset'];
            $pagination .= " OFFSET $offset";
        }

        $table = static::getTableName();
        $sqlData = "SELECT * FROM $table WHERE 1 = 1 $and $sort $pagination";
        $sqlCount = "SELECT COUNT(*) as count FROM $table WHERE 1 = 1 $and";

        $resultsData = self::db()->query($sqlData, $bindings);
        $resultCount = self::db()->query($sqlCount, $bindings);

        $totalItems = isset($resultCount[0]['count']) ? (int)$resultCount[0]['count'] : 0;
        $lastPage = $params['perPage'] > 0 ? (int)ceil($totalItems / $params['perPage']) : 1;

        return [
            array_map(fn($row) => new static($row), $resultsData),
            [
                'page' => (int)$params['page'],
                'perPage' => (int)$params['perPage'],
                'lastPage' => $lastPage,
                'totalItems' => $totalItems,
            ]
        ];
    }
}
