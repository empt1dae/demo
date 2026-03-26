<?php
session_start();
require_once 'db.php';

$error = '';

if (isset($_SESSION['user'])) {
    header("Location: my_requests.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['login']) && !empty($_POST['password'])) {

        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        $query = "SELECT * FROM users WHERE login='$login' OR email='$login'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $error = "Неверный логин или пароль";
        } else {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'login' => $user['login'],
                    'fio' => $user['fio'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                if ($user['role'] === 'moderator') {
                    header("Location: moderator.php");
                } else {
                    header("Location: my_requests.php");
                }

                exit();

            } else {
                $error = "Неверный логин или пароль";
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
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Вход</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="login" placeholder="Логин или Email" required>
        <input type="password" name="password" placeholder="Пароль" required>

        <button type="submit">Войти</button>

    </form>

    <p>Нет аккаунта? <a href="register.php">Регистрация</a></p>
</div>

</body>
</html>