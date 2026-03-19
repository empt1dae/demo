<?php
$flash = get_flash();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($pageTitle ?? 'Интернет-магазин'); ?></title>
    <link rel="stylesheet" href="<?= isset($isAdminPage) && $isAdminPage ? '../style.css' : 'style.css'; ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?= isset($isAdminPage) && $isAdminPage ? '../index.php' : 'index.php'; ?>">StoreLite</a>
            <nav class="nav">
                <a href="<?= isset($isAdminPage) && $isAdminPage ? '../index.php' : 'index.php'; ?>" class="<?= $currentPage === 'index.php' ? 'active' : ''; ?>">Каталог</a>
                <a href="<?= isset($isAdminPage) && $isAdminPage ? '../cart.php' : 'cart.php'; ?>" class="<?= $currentPage === 'cart.php' ? 'active' : ''; ?>">
                    Корзина
                    <span class="badge"><?= cart_total_items(); ?></span>
                </a>
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <a href="<?= isset($isAdminPage) && $isAdminPage ? 'index.php' : 'admin/index.php'; ?>">Админка</a>
                    <?php endif; ?>
                    <span class="user-pill"><?= escape($_SESSION['user']['username']); ?></span>
                    <a href="<?= isset($isAdminPage) && $isAdminPage ? '../logout.php' : 'logout.php'; ?>">Выход</a>
                <?php else: ?>
                    <a href="<?= isset($isAdminPage) && $isAdminPage ? '../login.php' : 'login.php'; ?>" class="<?= $currentPage === 'login.php' ? 'active' : ''; ?>">Вход</a>
                    <a href="<?= isset($isAdminPage) && $isAdminPage ? '../register.php' : 'register.php'; ?>" class="<?= $currentPage === 'register.php' ? 'active' : ''; ?>">Регистрация</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="container">
            <div class="alert alert-<?= escape($flash['type']); ?>" data-alert>
                <?= escape($flash['message']); ?>
            </div>
        </div>
    <?php endif; ?>

    <main class="page">
