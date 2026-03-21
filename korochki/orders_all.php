<?php
session_start();
include("db_copy.php");

ini_set('display_errors', 1);

if (!isset($_SESSION['users'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['users']['id'];

$query = "
SELECT orders.*, status.name AS status_name
FROM orders
LEFT JOIN status ON orders.statsus_id = status.id
WHERE orders.user_id = '$user_id'
ORDER BY orders.id_orders DESC
";

$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['review_text'])) {

    $review = mysqli_real_escape_string($conn, $_POST['review_text']);
    $user_id = $_SESSION['users']['id'];
    $order_id = (int)$_POST['order_id'];

    $query = "INSERT INTO reviews (description, user_id, order_id)
              VALUES ('$review', '$user_id', '$order_id')";

    mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="header">
        <h1><a href="orders.php">Мои заявки</a></h1>
        <div class="nav">
            <a href="orders.php">Новая заявка</a>
            <a href="logout.php">Выход</a>
        </div>
    </header>

    <div class="container">
        <h2>Список заявок</h2>

        <div class="orders">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="order-card">
                    <h3><?= $row['kurses'] ?></h3>
                    <p><b>Дата:</b> <?= $row['date'] ?></p>
                    <p><b>Оплата:</b> <?= $row['payment_type'] ?></p>
                    <p><b>Статус:</b> <?= $row['status_name'] ?></p>
                </div>
                <?php if ($row['statsus_id'] == 3): ?>

                    <form method="POST">
                        <textarea name="review_text" required></textarea>

                        <input type="hidden" name="order_id" value="<?= $row['id_orders'] ?>">

                        <button>Оставить отзыв</button>
                    </form>

                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>

</body>

</html>