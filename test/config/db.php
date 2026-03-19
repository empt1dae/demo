<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'online_store';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($connection, 'utf8mb4');
