<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'moderator') {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $request_id = (int)$_POST['request_id'];
    $status = $_POST['status'];
    $comment = trim($_POST['comment']);

    $query = "UPDATE requests
              SET status='$status', comment='$comment'
              WHERE id='$request_id'";

    mysqli_query($conn, $query);
}

$query = "
SELECT r.*, u.login, rev.rating, rev.comment AS review_text
FROM requests r
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN reviews rev ON r.id = rev.request_id
ORDER BY r.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Панель модератора</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="header">
        <h1>Панель модератора</h1>
        <div class="nav">
            <a href="my_requests.php">Мои заявки</a>
            <a href="logout.php">Выход</a>
        </div>
    </header>

    <div class="container">

        <h2>Все заявки</h2>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="order-card">

                <h3><?= $row['workshop'] ?></h3>

                <p><b>Пользователь:</b> <?= $row['login'] ?></p>
                <p><b>Дата:</b> <?= $row['date'] ?></p>
                <p><b>Оплата:</b> <?= $row['payment'] ?></p>
                <p><b>Статус:</b> <?= $row['status'] ?></p>

                <?php if ($row['rating']): ?>
                    <p><b>Оценка:</b> <?= $row['rating'] ?> / 5</p>
                    <p><b>Отзыв:</b> <?= $row['review_text'] ?></p>
                <?php endif; ?>

                <form method="POST" class="admin-form">

                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">

                    <select name="status">
                        <option <?= $row['status'] == 'На рассмотрении' ? 'selected' : '' ?>>На рассмотрении</option>
                        <option <?= $row['status'] == 'Подтверждена' ? 'selected' : '' ?>>Подтверждена</option>
                        <option <?= $row['status'] == 'Отклонена' ? 'selected' : '' ?>>Отклонена</option>
                        <option <?= $row['status'] == 'Проведен' ? 'selected' : '' ?>>Проведен</option>
                    </select>

                    <textarea name="comment" placeholder="Комментарий модератора"><?= $row['comment'] ?></textarea>

                    <button>Сохранить</button>

                </form>

            </div>
        <?php endwhile; ?>

    </div>

</body>

</html>