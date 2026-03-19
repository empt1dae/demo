<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

require_guest();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errors[] = 'Заполните логин и пароль.';
    }

    if (empty($errors)) {
        $safeUsername = mysqli_real_escape_string($connection, $username);
        $result = mysqli_query($connection, "SELECT * FROM users WHERE username = '$safeUsername' LIMIT 1");
        $user = $result ? mysqli_fetch_assoc($result) : null;

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ];

            set_flash('success', 'Вы успешно вошли.');

            if ($user['role'] === 'admin') {
                redirect('admin/index.php');
            }

            redirect('index.php');
        }

        $errors[] = 'Неверный логин или пароль.';
    }
}

$pageTitle = 'Вход';
require_once __DIR__ . '/partials/header.php';
?>
<section class="container section-spacing auth-shell">
    <form method="POST" class="auth-card">
        <h1>Вход</h1>
        <p>Авторизуйтесь, чтобы управлять корзиной и получить доступ к своему аккаунту.</p>

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

        <button type="submit" class="button button-wide">Войти</button>
    </form>
</section>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
