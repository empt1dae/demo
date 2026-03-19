<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

require_guest();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errors[] = 'Заполните все поля.';
    }

    if (mb_strlen($username) < 3) {
        $errors[] = 'Логин должен содержать минимум 3 символа.';
    }

    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль должен содержать минимум 6 символов.';
    }

    $safeUsername = mysqli_real_escape_string($connection, $username);
    $existingUser = mysqli_query($connection, "SELECT id FROM users WHERE username = '$safeUsername' LIMIT 1");

    if ($existingUser && mysqli_num_rows($existingUser) > 0) {
        $errors[] = 'Пользователь с таким логином уже существует.';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $safePassword = mysqli_real_escape_string($connection, $hashedPassword);
        $insertQuery = "INSERT INTO users (username, password, role) VALUES ('$safeUsername', '$safePassword', 'user')";

        if (mysqli_query($connection, $insertQuery)) {
            set_flash('success', 'Регистрация выполнена. Теперь войдите в аккаунт.');
            redirect('login.php');
        }

        $errors[] = 'Не удалось зарегистрировать пользователя.';
    }
}

$pageTitle = 'Регистрация';
require_once __DIR__ . '/partials/header.php';
?>
<section class="container section-spacing auth-shell">
    <form method="POST" class="auth-card">
        <h1>Регистрация</h1>
        <p>Создайте аккаунт для доступа к корзине и покупкам.</p>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= escape($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label class="field">
            <span>Логин</span>
            <input type="text" name="username" value="<?= escape($_POST['username'] ?? ''); ?>" required>
        </label>

        <label class="field">
            <span>Пароль</span>
            <input type="password" name="password" required>
        </label>

        <button type="submit" class="button button-wide">Зарегистрироваться</button>
    </form>
</section>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
