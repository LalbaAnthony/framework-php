# Framework PHP

This is a simple PHP framework for building web applications.
Just a pure mix of Laravel, Symfony, ... with a bit of my own touch.

It isnt mean to be used in production (at least for now), since it might be buggy and not secure enough.

Basically, its a learning project to figure out how a framework can work under the hood and how to build one from scratch.

## 🚀 Quick start

- Copy the `.env.example` file to `.env` and set your environment variables.
- Web server setup:
  - Config the `RewriteBase` in the `public/.htaccess` file if needed.
  - Start a local server (ex: XAMPP, MAMP, ...)
- Database setup:
  - Create a database and set the connection parameters in the `.env` file.
  - Go to `/reset` script in the `public/` folder to create the database schema and seed initial data.

## 📐 Architecture

- `app/` : contains the application code (Controllers, Models, Database, Http, ...)
  - `Controllers/` `*` : contains the controllers
  - `Database/` : contains the database related classes (Connection, Migrator, Seeder, ...)
  - `Exceptions/` `*` : contains the custom exceptions
  - `Http/` : contains the HTTP related classes (Request, Response, Router, Controller, ...)
  - `Models/` `*` : contains the models
  - `Util/` : contains utility classes as helpers, faker, ...
  - `View/` : contains the view related classes (View, Component, ...) 
- `config/` : contains the configuration files
- `public/` : contains the public files (entrypoint index.php, assets, ...)
- `ressources/` : contains the views, icons, ...
- ...

In the `app/` folder, folders marked with * are meant to be modified by the user to add application specific code. All the other folders are meant to contain framework code only.

There is no `vendor/` folder since this project is dependency free (and will always be).

## ⌨️ Code

### Models

Usage example:

```php
// =========================
// Creating a new Post
// =========================

$post = new Post([
    'title' => 'My first post',
    'content' => 'This is the content of my first post.',
]);
$post->save(); // Inserts a new record and sets the ID, created_at, updated_at fields
dump($post->id); // Newly assigned ID
dump($post->created_at); // Automatically set

// =========================
// Retrieving a Post by ID
// =========================

$post = Post::findByPk($post->id);
dump($post);
```

### Aliases

You can define class aliases in `aliases.php` to simplify class references throughout your application.

### Icons

All icons are stored in `ressources/icons` and come from [Lucide Icons](https://lucide.dev/).
Can download them at https://github.com/mallardduck/blade-lucide-icons/tree/main/resources/svg.