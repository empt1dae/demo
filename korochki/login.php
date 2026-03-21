<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE login='$login' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $users = mysqli_fetch_assoc($result);

        $_SESSION['users'] = [
            'id' => $users['id'],
            'login' => $users['login'],
            'role_id' => $users['role_id']
        ];

        if ($users['role_id'] == 2) {
            header("Location: admin.php");
        } else {
            header("Location: orders.php");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Авторизация</h2>

    <form method="POST" class="form">
        <input type="text" class="input-padding" name="login" placeholder="Логин" required>
        <input type="password" class="input-padding" name="password" placeholder="Пароль" required>
        <button>Войти</button>
    </form>

    <a href="registration.php">Нет аккаунта? Регистрация</a>
</div>

</body>
</html>
