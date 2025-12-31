<?php

use App\Database\Database;
use App\Database\Migration;

return new class extends Migration
{
    public function up(Database $db): void
    {
        $sql = "
            CREATE TABLE `category`(
                `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
                `slug` VARCHAR(50) NOT NULL,
                `label` VARCHAR(50) NOT NULL,
                `updated_at` DATETIME,
                `created_at` DATETIME NOT NULL DEFAULT NOW(),
                CONSTRAINT `category_PK` PRIMARY KEY (`id`)
            ) ENGINE=InnoDB;
        ";
        $db->query($sql);
    }

    public function down(Database $db): void
    {
        $db->dropIfExists('category');
    }
};
