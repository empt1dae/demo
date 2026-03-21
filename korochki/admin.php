<?php
session_start();
include("db_copy.php");

ini_set('display_errors', 1);

if (!isset($_SESSION['users'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['users']['role_id'] != 2) {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = (int)$_POST['order_id'];
    $status_id = (int)$_POST['status_id'];

    $query = "UPDATE orders SET statsus_id='$status_id' WHERE id_orders='$order_id'";
    mysqli_query($conn, $query);
}

$query = "
SELECT orders.*, users.login, status.name AS status_name, reviews.description
FROM orders
LEFT JOIN users ON orders.user_id = users.id
LEFT JOIN status ON orders.statsus_id = status.id
LEFT JOIN reviews ON reviews.order_id = orders.id_orders
ORDER BY orders.id_orders DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="header">
        <h1>Админ-панель</h1>
        <div class="nav">
            <a href="orders_all.php">Мои заявки</a>
            <a href="logout.php">Выход</a>
        </div>
    </header>

    <div class="container">
        <h2 class="text">Все заявки</h2>

        <div class="orders">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="order-card">
                    <h3><?= $row['kurses'] ?></h3>
                    <p><b>Пользователь:</b> <?= $row['login'] ?></p>
                    <p><b>Дата:</b> <?= $row['date'] ?></p>
                    <p><b>Оплата:</b> <?= $row['payment_type'] ?></p>
                    <p><b>Статус:</b> <?= $row['status_name'] ?></p>
                    <?php if (!empty($row['description'])): ?>
                        <p><b>Отзыв:</b> <?= $row['description'] ?></p>
                    <?php endif; ?>

                    <form method="POST" class="status-form">
                        <input type="hidden" name="order_id" value="<?= $row['id_orders'] ?>">

                        <select name="status_id">
                            <option value="1">Новая</option>
                            <option value="2">Идет обучение</option>
                            <option value="3">Обучение завершено</option>
                        </select>

                        <button>Изменить</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>

</html>