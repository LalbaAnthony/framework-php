<?php

use App\Database\Migration;
use App\Database\Database;

return new class extends Migration
{
    public function up(Database $db): void
    {
        $sql = "
            CREATE TABLE `post`(
                    `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
                    `user_id` INT,
                    `date` DATE,
                    `slug` VARCHAR(100) NOT NULL,
                    `title` VARCHAR(200) NOT NULL,
                    `content` VARCHAR(1000),
                    `published` BOOLEAN NOT NULL DEFAULT 0,
                    `updated_at` DATETIME,
                    `created_at` DATETIME NOT NULL DEFAULT NOW(),
                    CONSTRAINT `post_PK` PRIMARY KEY (`id`),
                    CONSTRAINT `post_user_FK` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
            ) ENGINE=InnoDB;
        ";
        $db->query($sql);
    }

    public function down(Database $db): void
    {
        $db->dropIfExists('post');
    }
};
