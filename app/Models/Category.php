<?php

namespace App\Models;

use App\Database\Model;

class Category extends Model
{
    public ?int $id = null;
    public string $slug = '';
    public string $label = '';
    public ?string $color = null;
    public ?string $updated_at = null;
    public ?string $created_at = null;

    protected static array $belongsToMany = [
        'posts' => [Post::class, 'post_category', 'category_id', 'post_id'],
    ];

    public static function getTableName(): string
    {
        return 'category';
    }

    public static function getFillableColumns(): array
    {
        return ['id', 'slug', 'label', 'color', 'updated_at', 'created_at'];
    }

    public static function getSearchableColumns(): array
    {
        return ['label', 'slug', 'color'];
    }
}
