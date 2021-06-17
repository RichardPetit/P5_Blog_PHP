CREATE DATABASE `blog` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `blog`;

CREATE TABLE `users` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `pseudo` varchar(45) NOT NULL,
                         `email` varchar(100) NOT NULL,
                         `password` varchar(100) NOT NULL,
                         `is_admin` tinyint(1) NOT NULL DEFAULT '0',
                         `is_active` tinyint(1) NOT NULL DEFAULT '1',
                         `avatar` varchar(255) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `articles` (
                            `id` int NOT NULL AUTO_INCREMENT,
                            `users_id` int NOT NULL,
                            `title` varchar(100) NOT NULL,
                            `content` longtext NOT NULL,
                            `summary` longtext,
                            `date` datetime NOT NULL,
                            `picture` varchar(255) DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            KEY `fk_articles_users1_idx` (`users_id`),
                            CONSTRAINT `fk_articles_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
                            `id` int NOT NULL AUTO_INCREMENT,
                            `articles_id` int NOT NULL,
                            `users_id` int NOT NULL,
                            `title` varchar(100) NOT NULL,
                            `content` longtext NOT NULL,
                            `date` datetime NOT NULL,
                            `is_valid` tinyint(1) NOT NULL,
                            PRIMARY KEY (`id`),
                            KEY `fk_comments_articles_idx` (`articles_id`),
                            KEY `fk_comments_users1_idx` (`users_id`),
                            CONSTRAINT `fk_comments_articles` FOREIGN KEY (`articles_id`) REFERENCES `articles` (`id`),
                            CONSTRAINT `fk_comments_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
