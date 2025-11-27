
SET NAMES 'utf8mb4';

CREATE DATABASE IF NOT EXISTS `emailsender` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `emailsender`;

CREATE TABLE IF NOT EXISTS `option` (
    `option_version` VARCHAR(11) NOT NULL DEFAULT '1.1.0',
    `option_installed` TINYINT NOT NULL DEFAULT 1,
    `option_registered` TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `option` () VALUES ();

CREATE TABLE IF NOT EXISTS `user` (
    `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_admin` TINYINT NOT NULL DEFAULT 0,
    `user_active` TINYINT NOT NULL DEFAULT 1,
    `user_login` VARCHAR(20) NOT NULL DEFAULT '',
    `user_login_canonical` VARCHAR(20) NOT NULL DEFAULT '',
    `user_email` VARCHAR(100) NOT NULL DEFAULT '',
    `user_email_canonical` VARCHAR(100) NOT NULL DEFAULT '',
    `user_password` VARCHAR(255) NOT NULL DEFAULT '',
    `user_key` VARCHAR(255) NOT NULL DEFAULT '',
    `user_ip_added` VARCHAR(15) NOT NULL DEFAULT '',
    `user_date_added` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
    `user_ip_updated` VARCHAR(15) NOT NULL DEFAULT '',
    `user_date_updated` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
    `user_ip_loged` VARCHAR(15) NOT NULL DEFAULT '',
    `user_date_loged` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `unique_login_canonical` (`user_login_canonical`),
    UNIQUE KEY `unique_email_canonical` (`user_email_canonical`),
--  KEY `user_id` (`user_id`),
--  KEY `user_admin` (`user_admin`),
--  KEY `user_active` (`user_active`),
--  KEY `user_login` (`user_login`),
--  KEY `user_login_canonical` (`user_login_canonical`),
--  KEY `user_email` (`user_email`),
--  KEY `user_email_canonical` (`user_email_canonical`),
    KEY `user_password` (`user_password`)
--  KEY `user_key` (`user_key`),
--  KEY `user_ip_added` (`user_ip_added`),
--  KEY `user_date_added` (`user_date_added`),
--  KEY `user_ip_updated` (`user_ip_updated`),
--  KEY `user_date_updated` (`user_date_updated`),
--  KEY `user_ip_loged` (`user_ip_loged`),
--  KEY `user_date_loged` (`user_date_loged`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `list` (
    `list_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `list_name` VARCHAR(100) NOT NULL DEFAULT '',
    PRIMARY KEY (`list_id`),
--  KEY `list_id` (`list_id`),
    KEY `list_name` (`list_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `email` (
    `email_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `list_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `email_name` VARCHAR(100) NOT NULL DEFAULT '',
    `email_email` VARCHAR(100) NOT NULL DEFAULT '',
    `email_email_canonical` VARCHAR(100) NOT NULL DEFAULT '',
    `email_ip_added` VARCHAR(15) NOT NULL DEFAULT '',
    `email_date_added` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
    PRIMARY KEY (`email_id`),
--  KEY `email_id` (`email_id`),
--  KEY `list_id` (`list_id`),
--  KEY `email_name` (`email_name`),
--  KEY `email_email` (`email_email`),
    KEY `email_email_canonical` (`email_email_canonical`),
    KEY `email_ip_added` (`email_ip_added`),
    KEY `email_date_added` (`email_date_added`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `text` (
    `text_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `text_subject` VARCHAR(100) NOT NULL DEFAULT '',
    `text_message` TEXT NOT NULL,
    PRIMARY KEY (`text_id`),
--  KEY `text_id` (`text_id`),
    KEY `text_subject` (`text_subject`)
--  KEY `text_message` (`text_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `send` (
    `list_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `email_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `text_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `send_count` INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `send` () VALUES ();

ALTER TABLE `email`
    ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`list_id`);
