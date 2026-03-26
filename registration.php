<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !empty($_POST['login']) &&
        !empty($_POST['password']) &&
        !empty($_POST['confirm_password']) &&
        !empty($_POST['fio']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['email'])
    ) {

        $login = trim($_POST['login']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $fio = trim($_POST['fio']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        if (!preg_match("/^[a-z0-9_]{5,20}$/", $login)) {
            $error = "Логин: латиница, цифры, _, 5-20 символов";

        } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{7,}$/", $password)) {
            $error = "Пароль: минимум 7 символов, 1 заглавная, цифра и спецсимвол";

        } elseif ($password !== $confirm_password) {
            $error = "Пароли не совпадают";

        } elseif (!preg_match("/^[А-Яа-яЁё\s-]+$/u", $fio)) {
            $error = "ФИО: только кириллица, пробелы, дефис";

        } elseif (!preg_match("/^\+7 \d{3} \d{3}-\d{2}-\d{2}$/", $phone)) {
            $error = "Телефон: формат +7 XXX XXX-XX-XX";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Некорректный email";

        } else {

            $check = mysqli_query($conn, "SELECT id FROM users WHERE login='$login'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Логин уже занят";

            } else {

                $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
                if (mysqli_num_rows($check) > 0) {
                    $error = "Email уже используется";

                } else {

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $query = "INSERT INTO users (login, password, fio, phone, email, role)
                              VALUES ('$login', '$hashed_password', '$fio', '$phone', '$email', 'user')";

                    if (mysqli_query($conn, $query)) {
                        header("Location: login.php");
                        exit();
                    } else {
                        $error = "Ошибка: " . mysqli_error($conn);
                    }
                }
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
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Регистрация</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
        <input type="text" name="fio" placeholder="ФИО" required>
        <input type="text" name="phone" placeholder="+7 999 999-99-99" required>
        <input type="email" name="email" placeholder="Email" required>

        <button type="submit">Зарегистрироваться</button>

    </form>

    <p>Есть аккаунт? <a href="login.php">Войти</a></p>
</div>

</body>
</html>