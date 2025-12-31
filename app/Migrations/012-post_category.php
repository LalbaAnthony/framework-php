<?php

use App\Database\Migration;
use App\Database\Database;

return new class extends Migration
{
    public function up(Database $db): void
    {
        $sql = "
            CREATE TABLE `post_category`(
                    `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
                    `post_id` INT NOT NULL,
                    `category_id` INT NOT NULL,
                    `updated_at` DATETIME,
                    `created_at` DATETIME NOT NULL DEFAULT NOW(),
                    CONSTRAINT `post_category_PK` PRIMARY KEY (`id`),
                    CONSTRAINT `post_category_post_FK` FOREIGN KEY (`post_id`) REFERENCES `post`(`id`) ON DELETE CASCADE,
                    CONSTRAINT `post_category_category_FK` FOREIGN KEY (`category_id`) REFERENCES `category`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ";
        $db->query($sql);
    }

    public function down(Database $db): void
    {
        $db->drop('post_category');
    }
};
