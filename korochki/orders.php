<?php
session_start();
include("db_copy.php");

ini_set('display_errors', 1);

if (!isset($_SESSION['users'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['kurses']) && !empty($_POST['date']) && !empty($_POST['payment_type'])) {
        $kurses = mysqli_real_escape_string($conn, $_POST['kurses']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $payment = mysqli_real_escape_string($conn, $_POST['payment_type']);
        $user_id = $_SESSION['users']['id'];

        $query = "INSERT INTO orders (kurses, date, payment_type, user_id)
                  VALUES ('$kurses', '$date', '$payment', '$user_id')";

        if (mysqli_query($conn, $query)) {
            $success = "Заявка отправлена!";
        } else {
            $error = "Ошибка: " . mysqli_error($conn);
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
    <title>Заявка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1> <a href="orders.php">Курсы</a> </h1>
    <div class="nav">
        <a href="orders_all.php">Все заявки</a>
        <a href="logout.php">Выход</a>
    </div>
</header>

<div class="slider">
    <button class="slider-arrow prev" type="button" aria-label="Previous slide">&#8249;</button>
    <img src="media/image08.webp" class="slide active" alt="Курс 1">
    <img src="media/image09.webp" class="slide" alt="Курс 2">
    <img src="media/image06.jpg" class="slide" alt="Курс 3">
    <img src="media/image07.jpg" class="slide" alt="Курс 4">
    <img src="media/image11.jpg" class="slide" alt="Курс 5">
    <img src="media/image13.webp" class="slide" alt="Курс 6">
    <div class="slider-overlay">
        <h2>Выберите курс и отправьте заявку</h2>
        <p></p>
    </div>
    <button class="slider-arrow next" type="button" aria-label="Next slide">&#8250;</button>
</div>

<div class="form-container">
    <div>
        <h2>Подать заявку</h2>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color:green"><?= $success ?></p>
        <?php endif; ?>
    </div>

    <div class="form">
        <form method="POST" class="form">
            <input type="text" class="input-padding" name="kurses" placeholder="Название курса" required>
            <input type="date" class="input-padding" name="date" required>

            <select name="payment_type" class="input-padding" required>
                <option value="">Способ оплаты</option>
                <option value="Карта">Карта</option>
                <option value="Наличные">Наличные</option>
            </select>

            <button>Отправить</button>
        </form>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
