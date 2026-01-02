<?php

use App\Database\Migration;
use App\Database\Database;

return new class extends Migration
{
    public function up(Database $db): void
    {
        $sql = <<<SQL
        CREATE TABLE `user`(
            `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
            `name` VARCHAR(50) NOT NULL,
            `birthdate` DATE,
            `token` VARCHAR(500),
            `password` VARCHAR(150) NOT NULL,
            `last_login` DATETIME,
            `is_admin` BOOLEAN NOT NULL DEFAULT 0,
            `updated_at` DATETIME,
            `created_at` DATETIME NOT NULL DEFAULT NOW(),
            CONSTRAINT `user_PK` PRIMARY KEY (`id`)
        ) ENGINE=InnoDB;
        SQL;

        $db->query($sql);
    }

    public function down(Database $db): void
    {
        $db->dropIfExists('user');
    }
};
