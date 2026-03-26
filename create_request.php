<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['workshop']) && !empty($_POST['date']) && !empty($_POST['payment'])) {

        $user_id = $_SESSION['user']['id'];
        $workshop = $_POST['workshop'];
        $date = $_POST['date'];
        $payment = $_POST['payment'];

        if (strtotime($date) < strtotime(date("Y-m-d"))) {
            $error = "Нельзя выбрать прошедшую дату";
        } else {

            $query = "INSERT INTO requests (user_id, workshop, date, payment)
                      VALUES ('$user_id', '$workshop', '$date', '$payment')";

            if (mysqli_query($conn, $query)) {
                $success = "Заявка успешно отправлена";
            } else {
                $error = "Ошибка: " . mysqli_error($conn);
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
    <title>Создание заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Создать заявку</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="workshop">Мастер-класс</label>
        <select name="workshop" id="workshop" required>
            <option value="">Выберите мастер-класс</option>
            <option>Python для начинающих</option>
            <option>Веб-разработка на React</option>
            <option>Анализ данных в Excel</option>
        </select>

        <label for="date">Дата проведения</label>
        <input type="date" name="date" id="date" required>

        <label>Способ оплаты</label>
        <div class="radio-group">
            <label>
                <input type="radio" name="payment" value="Банковская карта онлайн" required>
                Банковская карта онлайн
            </label>

            <label>
                <input type="radio" name="payment" value="Наличными на месте">
                Наличными на месте
            </label>
        </div>

        <button type="submit">Отправить заявку</button>

    </form>

    <p><a href="my_requests.php">Мои заявки</a></p>

</div>

</body>
</html>