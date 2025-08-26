CREATE
DATABASE IF NOT EXISTS osontaklif;
USE
osontaklif;

CREATE TABLE `professions`
(
    `id`         bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `is_active`  tinyint(1) NOT NULL DEFAULT '1',
    `slug`       varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `professions_name_unique` (`name`),
    UNIQUE KEY `professions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `questions`
(
    `id`            bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `profession_id` bigint(20) unsigned NOT NULL,
    `question`      text NOT NULL,
    `content`       text DEFAULT NULL,
    `chance`        int(10) unsigned NOT NULL,
    `tag`           text DEFAULT NULL,
    `created_at`    timestamp NULL DEFAULT NULL,
    `updated_at`    timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY             `questions_profession_id_foreign` (`profession_id`),
    CONSTRAINT `questions_profession_id_foreign` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `interviews`
(
    `id`            bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title`         varchar(255) NOT NULL,
    `link`          varchar(255) NOT NULL,
    `profession_id` bigint(20) unsigned NOT NULL,
    `grade`         varchar(255) NOT NULL,
    `created_at`    timestamp NULL DEFAULT NULL,
    `updated_at`    timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY             `interviews_profession_id_foreign` (`profession_id`),
    CONSTRAINT `interviews_profession_id_foreign` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `profession_skills`
(
    `id`            bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `profession_id` bigint(20) unsigned NOT NULL,
    `skill_name`    varchar(255) NOT NULL,
    `count`         int(11) NOT NULL DEFAULT '0',
    `last_updated`  timestamp NULL DEFAULT NULL,
    `created_at`    timestamp NULL DEFAULT NULL,
    `updated_at`    timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `profession_skills_profession_id_skill_name_unique` (`profession_id`,`skill_name`),
    CONSTRAINT `profession_skills_profession_id_foreign` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
