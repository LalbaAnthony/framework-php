# Framework PHP

This is a simple PHP framework for building web applications.
Just a pure mix of Laravel, Symfony, CodeIgniter, Yii, ... with a bit of my own touch.

It isnt mean to be used in production (at least for now), since it might be buggy and not secure enough.

## 📦 Config

- Copy the `.env.example` file to `.env` and set your environment variables.
- Config the `RewriteBase` in the `public/.htaccess` file.

## ⌨️ Code

### Models

Usage example:

```php
// Creating a new Post
$post = new Post([
    // ...
    'created_at' => date('Y-m-d H:i:s')
]);
$post->save();  // Inserts a new record and sets $post->id

// Retrieving a Post by ID
$post = Post::findOne($post->id);
var_dump($post);
```

### Icons

All icons are stored in `ressources/icons` and come from [Lucide Icons](https://lucide.dev/).
Can download them at https://github.com/mallardduck/blade-lucide-icons/tree/main/resources/svg.