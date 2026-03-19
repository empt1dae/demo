<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

require_admin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);

    if ($title === '' || $description === '' || $price <= 0) {
        $errors[] = 'Заполните все поля и укажите корректную цену.';
    }

    $imageName = upload_product_image($_FILES['image'] ?? []);

    if ($imageName === false) {
        $errors[] = 'Не удалось загрузить изображение. Разрешены jpg, jpeg, png, gif, webp.';
    }

    if (empty($errors)) {
        $safeTitle = mysqli_real_escape_string($connection, $title);
        $safeDescription = mysqli_real_escape_string($connection, $description);
        $safeImage = mysqli_real_escape_string($connection, $imageName);
        $query = "INSERT INTO products (title, description, price, image) VALUES ('$safeTitle', '$safeDescription', $price, '$safeImage')";

        if (mysqli_query($connection, $query)) {
            set_flash('success', 'Товар успешно добавлен.');
            redirect('index.php');
        }

        $errors[] = 'Не удалось сохранить товар.';
    }
}

$pageTitle = 'Добавление товара';
$isAdminPage = true;

require_once __DIR__ . '/../partials/header.php';
?>
<section class="container section-spacing auth-shell">
    <form method="POST" enctype="multipart/form-data" class="auth-card product-form">
        <h1>Добавить товар</h1>
        <p>Заполните данные товара и загрузите изображение.</p>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= escape($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label class="field">
            <span>Название</span>
            <input type="text" name="title" value="<?= escape($_POST['title'] ?? ''); ?>" required>
        </label>

        <label class="field">
            <span>Описание</span>
            <textarea name="description" rows="5" required><?= escape($_POST['description'] ?? ''); ?></textarea>
        </label>

        <label class="field">
            <span>Цена</span>
            <input type="number" name="price" min="1" value="<?= escape($_POST['price'] ?? ''); ?>" required>
        </label>

        <label class="field">
            <span>Изображение</span>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
        </label>

        <button type="submit" class="button button-wide">Сохранить товар</button>
    </form>
</section>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
