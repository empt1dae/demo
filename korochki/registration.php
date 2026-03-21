<?php
session_start();
include("db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !empty($_POST['login']) &&
        !empty($_POST['password']) &&
        !empty($_POST['fio']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['email'])
    ) {
        $login = mysqli_real_escape_string($conn, $_POST['login']);
        $password = md5($_POST['password']);
        $fio = mysqli_real_escape_string($conn, $_POST['fio']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $check = mysqli_query($conn, "SELECT * FROM users WHERE login='$login'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Пользователь уже существует";
        } else {
            $query = "INSERT INTO users (login, password, fio, phone, email)
                      VALUES ('$login', '$password', '$fio', '$phone', '$email')";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Ошибка регистрации: " . mysqli_error($conn);
            }
        }
    } else {
        $error = "Заполните все поля";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <div>
        <h2>Регистрация</h2>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>

    <div class="form">
        <form method="POST" class="form">
            <input type="text" class="input-padding" name="login" placeholder="Логин" required>
            <input type="password" class="input-padding" name="password" placeholder="Пароль" required>
            <input type="text" class="input-padding" name="fio" placeholder="ФИО" required>
            <input type="text" class="input-padding" name="phone" placeholder="Телефон" required>
            <input type="email" class="input-padding" name="email" placeholder="Email" required>
            <button>Зарегистрироваться</button>
        </form>
    </div>

    <a href="login.php">Уже есть аккаунт? Войти</a>
</div>

</body>
</html>
