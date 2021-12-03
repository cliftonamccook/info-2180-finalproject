CREATE DATABASE bugme;

USE bugme;

CREATE TABLE `users` (
    `id` INTEGER AUTO_INCREMENT PRIMARY KEY,
    `firstname` VARCHAR(255),
    `lastname` VARCHAR(255),
    `password` VARCHAR(255),
    `email` VARCHAR(255),
    `date_joined` DATETIME
);

CREATE TABLE `issues` (
    `id` INTEGER AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255),
    `description` TEXT,
    `type` VARCHAR(10),
    `priority` VARCHAR(10),
    `status` VARCHAR(20) DEFAULT 'OPEN',
    `assigned_to` INTEGER,
    `created_by` INTEGER,
    `created` DATETIME,
    `updated` DATETIME
);

INSERT INTO `users` (`firstname`, `lastname`, `password`, `email`, `date_joined`)
VALUES ('Admin', 'User', '$2y$10$BG7vYTMyKoa2HDmcXVdJYuavGr3zLLdj0ha.3zwfadpb5PeL794m6', 'admin@project2.com', NOW());
