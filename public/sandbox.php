<?php

use App\Models\Post;

require_once __DIR__ . '/../bootstrap.php';

$posts = Post::findAll()[0];

foreach ($posts as $post) {
    printLine($post->title);
    foreach ($post->categories as $category) {
        printLine(' - ' . $category->label);
    }
}