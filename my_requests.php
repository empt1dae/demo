<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_id'])) {

    $request_id = (int)$_POST['request_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    $check = mysqli_query($conn, "SELECT id FROM reviews WHERE request_id='$request_id'");

    if (mysqli_num_rows($check) == 0) {

        $query = "INSERT INTO reviews (request_id, rating, comment)
                  VALUES ('$request_id', '$rating', '$comment')";
        mysqli_query($conn, $query);
    }
}

$query = "
SELECT r.*, rev.rating, rev.comment AS review_text
FROM requests r
LEFT JOIN reviews rev ON r.id = rev.request_id
WHERE r.user_id = '$user_id'
ORDER BY r.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <h1>Мои заявки</h1>
    <div class="nav">
        <a href="create_request.php">Создать заявку</a>
        <a href="logout.php">Выход</a>
    </div>
</header>

<div class="container">

<div class="slider-container">
    <div class="slider">
        <div class="slide active">
            <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800" alt="Python курсы">
            <div class="slide-content">
                <h3>Python для начинающих</h3>
                <p>Освойте основы программирования с нуля. Изучите синтаксис, переменные, циклы и функции.</p>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800" alt="React курсы">
            <div class="slide-content">
                <h3>Веб-разработка на React</h3>
                <p>Создавайте современные веб-приложения. React, компоненты, хуки и состояние.</p>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1537432376149-e84978a99ba6?w=800" alt="Excel курсы">
            <div class="slide-content">
                <h3>Анализ данных в Excel</h3>
                <p>Мастерство работы с таблицами, диаграммами и формулами для аналитики.</p>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800" alt="Программирование">
            <div class="slide-content">
                <h3>Программирование с нуля</h3>
                <p>Начните карьеру в IT. Прокачайте навыки решения задач и написания кода.</p>
            </div>
        </div>
    </div>
    <button class="slider-arrow prev">❮</button>
    <button class="slider-arrow next">❯</button>
    <div class="slider-dots">
        <span class="dot active" data-slide="0"></span>
        <span class="dot" data-slide="1"></span>
        <span class="dot" data-slide="2"></span>
        <span class="dot" data-slide="3"></span>
    </div>
</div>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="order-card">

        <h3><?= $row['workshop'] ?></h3>

        <p><b>Дата:</b> <?= $row['date'] ?></p>
        <p><b>Оплата:</b> <?= $row['payment'] ?></p>
        <p><b>Статус:</b> <?= $row['status'] ?></p>

        <?php if ($row['comment']): ?>
            <p><b>Комментарий модератора:</b> <?= $row['comment'] ?></p>
        <?php endif; ?>

         ОТЗЫВ 
        <?php if ($row['status'] === 'Проведен'): ?>

            <?php if (!$row['rating']): ?>
                <form method="POST" class="review-form">

                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">

                    <select name="rating" required>
                        <option value="">Оценка</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                    <textarea name="comment" placeholder="Ваш отзыв" required></textarea>

                    <button>Отправить отзыв</button>

                </form>
            <?php else: ?>

                <p><b>Оценка:</b> <?= $row['rating'] ?></p>
                <p><b>Отзыв:</b> <?= $row['review_text'] ?></p>

            <?php endif; ?>

        <?php endif; ?>

    </div>
<?php endwhile; ?>

</div>

<script src="script.js"></script>

</body>
</html>