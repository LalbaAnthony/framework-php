<?php

namespace App\Models;

use App\Database\Model;

class Post extends Model
{
    public ?int $id = null;
    public ?int $user_id = null;
    public ?string $date = null;
    public string $title = '';
    public bool $published = false;
    public string $slug = '';
    public string $content = '';
    public ?string $updated_at = null;
    public ?string $created_at = null;

    protected static array $belongsToMany = [
        'categories' => [Category::class, 'post_category', 'post_id', 'category_id'],
    ];

    protected static array $belongsTo = [
        'user' => [User::class, 'user_id', 'id'],
    ];

    public static function getTableName(): string
    {
        return 'post';
    }

    public static function getFillableColumns(): array
    {
        return ['id', 'user_id', 'date', 'title', 'published', 'slug', 'content', 'updated_at', 'created_at'];
    }

    public static function getSearchableColumns(): array
    {
        return ['title', 'slug', 'content'];
    }

    public static function getSensitiveColumns(): array
    {
        return ['id', 'user_id', 'created_at', 'updated_at', 'published'];
    }
}
