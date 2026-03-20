<?php
$conn = new mysqli("10.0.52.8", "rwchqqqz", "0dZz6YuB6Kao", "rwchqqqz-m4");

if ($conn->connect_error) {
    die("Ошибка подключения!");
}

mysqli_set_charset($conn, "utf8mb4");