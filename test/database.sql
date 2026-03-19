CREATE DATABASE IF NOT EXISTS online_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price INT NOT NULL,
    image VARCHAR(255) NOT NULL DEFAULT ''
);

INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$DCSx.djos05cjPodeEAS7OyP8qtn8gKk.1yTpthKfOVnVjrnOJEVq', 'admin')
ON DUPLICATE KEY UPDATE role = VALUES(role), password = VALUES(password);

INSERT INTO products (title, description, price, image)
VALUES
    ('Беспроводные наушники', 'Компактные наушники с чистым звучанием, шумоподавлением и быстрой зарядкой.', 5990, ''),
    ('Умные часы', 'Лаконичные смарт-часы для уведомлений, тренировок и контроля активности.', 8990, ''),
    ('Портативная колонка', 'Мобильная колонка с насыщенным звуком, Bluetooth и защитой от брызг.', 4590, '');
