<?php

namespace App\Models;
use Exception;
use App\Exceptions\ModelException;

/**
 * The Post model represents a record in the "post" table.
 */
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

    /**
     * A collection of Category objects associated with this post.
     *
     * @var Category[]
     */
    protected array $categories = [];

    /**
     * Return the associated database table name.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'post';
    }

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * @return string
     */
    public static function getSearchableColumns(): array
    {
        return ['title', 'slug', 'content'];
    }

    /**
     * Returns the collection of categories for this post.
     * If not loaded yet, it lazy-loads them from the database.
     *
     * @return Category[]
     * @throws ModelException
     */
    public function getCategories(): array
    {
        // Return already loaded categories.
        if (!empty($this->categories)) return $this->categories;

        if ($this->id === null) throw new ModelException("Post must be saved before loading categories.");

        $sql = "SELECT c.* FROM category c 
                INNER JOIN post_category qc ON c.id = qc.category_id
                WHERE qc.post_id = ?";
        $results = static::$db->query($sql, [$this->id]);
        $this->categories = array_map(fn($row) => new Category($row), $results);
        return $this->categories;
    }

    /**
     * Attaches a Category to this post.
     * Inserts a record into the pivot table (post_category).
     *
     * @param Category $category
     * @return bool True if successful.
     * @throws ModelException
     */
    public function attachCategory(Category $category): bool
    {
        if ($this->id === null) throw new ModelException("Post must be saved before attaching categories.");
        if ($category->id === null) throw new ModelException("Category must be saved before attaching.");

        // Optional: Check if the association already exists.
        $sqlCheck = "SELECT * FROM post_category WHERE post_id = ? AND category_id = ?";
        $existing = static::$db->query($sqlCheck, [$this->id, $category->id]);
        if (!empty($existing)) {
            return true; // Already attached.
        }

        $sql = "INSERT INTO post_category (post_id, category_id) VALUES (?, ?)";
        $result = static::$db->execute($sql, [$this->id, $category->id]);
        if ($result) {
            // Update local collection if already loaded.
            $this->categories[] = $category;
        }
        return $result;
    }

    /**
     * Detaches a Category from this post.
     * Deletes the record from the pivot table (post_category).
     *
     * @param Category $category
     * @return bool True if successful.
     * @throws ModelException
     */
    public function detachCategory(Category $category): bool
    {
        if ($this->id === null) throw new ModelException("Post must be saved before detaching categories.");
        if ($category->id === null) throw new ModelException("Category must be saved before detaching.");

        $sql = "DELETE FROM post_category WHERE post_id = ? AND category_id = ?";
        $result = static::$db->execute($sql, [$this->id, $category->id]);

        // Optionally update the local categories collection.
        $this->categories = array_filter($this->categories, fn($cat) => $cat->id !== $category->id);
        return $result;
    }

    /**
     * Syncs the categories for this post.
     * Removes all existing category associations and attaches the provided ones.
     *
     * @param Category[] $categories
     * @return bool True if successful.
     * @throws ModelException
     */
    public function syncCategories(array $categories): bool
    {
        if ($this->id === null) {
            throw new ModelException("Post must be saved before syncing categories.");
        }

        // Remove all existing associations.
        $sqlDelete = "DELETE FROM post_category WHERE post_id = ?";
        $result = static::$db->execute($sqlDelete, [$this->id]);
        if (!$result) {
            return false;
        }

        // Attach new categories.
        foreach ($categories as $category) {
            if (!$this->attachCategory($category)) {
                return false;
            }
        }

        // Update local collection.
        $this->categories = $categories;
        return true;
    }
}
