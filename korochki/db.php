<?php
    $conn = new mysqli("127.0.0.1", "root", "", "korochki");
    if($conn->connect_error){
        die("Ошибка  подключения!");
    }

    mysqli_set_charset($conn, "utf8mb4");
