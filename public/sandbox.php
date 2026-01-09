<?php

use App\Models\Post;

require_once __DIR__ . '/../bootstrap.php';

$post = Post::findByPk(1);
dump($post);

$post->title = "Updated Title";
$post->setCategories([2, 3]); // Assuming categories with IDs 2 and 3 exist
$post->save();
dump($post);

$post = Post::findByPk(1);
dump($post);