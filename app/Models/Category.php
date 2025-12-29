<?php

namespace App\Models;

/**
 * The Category model represents a record in the "category" table.
 */
class Category extends Model
{
    public ?int $id = null;
    public string $slug = '';
    public string $label = '';
    public ?string $updated_at = null;
    public ?string $created_at = null;

    public static function getTableName(): string
    {
        return 'category';
    }

    public static function getSearchableColumns(): array
    {
        return ['label', 'slug'];
    }
}
