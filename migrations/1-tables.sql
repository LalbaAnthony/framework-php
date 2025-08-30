-- Drop in FK-safe order
DROP TABLE IF EXISTS `post_category`;
DROP TABLE IF EXISTS `post`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `user`;

#------------------------------------------------------------
# Table: user
#------------------------------------------------------------
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

#------------------------------------------------------------
# Table: category
#------------------------------------------------------------
CREATE TABLE `category`(
        `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
        `slug` VARCHAR(50) NOT NULL,
        `label` VARCHAR(50) NOT NULL,
        `updated_at` DATETIME,
        `created_at` DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT `category_PK` PRIMARY KEY (`id`)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: post
#------------------------------------------------------------
CREATE TABLE `post`(
        `id` INT AUTO_INCREMENT NOT NULL UNIQUE,
        `user_id` INT,
        `date` DATE,
        `slug` VARCHAR(100) NOT NULL,
        `title` VARCHAR(200) NOT NULL,
        `content` VARCHAR(1000),
        `updated_at` DATETIME,
        `created_at` DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT `post_PK` PRIMARY KEY (`id`),
        CONSTRAINT `post_user_FK` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
) ENGINE=InnoDB;

#------------------------------------------------------------
# Table: post_category
#------------------------------------------------------------
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
