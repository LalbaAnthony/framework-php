<?php

namespace App\Models;

use App\Util\Helpers;
use App\Database\Model;

/**
 * The User model represents a record in the "user" table.
 */
class User extends Model
{
    public int $id = null;
    public string $name = '';
    public string $birthdate = '';
    public string $token = '';
    public string $password = '';
    public string $last_login = '';
    public bool $is_admin = false;
    public ?string $updated_at = null;
    public ?string $created_at = null;

    public static function getTableName(): string
    {
        return 'user';
    }

    public static function getFillableColumns(): array
    {
        return ['id', 'name', 'birthdate', 'token', 'password', 'last_login', 'is_admin', 'updated_at', 'created_at'];
    }

    public static function getSearchableColumns(): array
    {
        return ['name', 'birthdate'];
    }

    public function updateLastLogin(): void
    {
        $this->last_login = Helpers::currentDateTime();
        $this->save();
    }
}
