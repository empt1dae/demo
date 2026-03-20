<?php
    $conn = new mysqli("localhost", "root", "", "korochki");
    if($conn->connect_error){
        die("Ошибка  подключения!");
    }

    mysqli_set_charset($conn, "utf8mb4");
