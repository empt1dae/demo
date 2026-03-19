<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

require_admin();

$productId = (int) ($_GET['id'] ?? 0);
$productResult = mysqli_query($connection, "SELECT * FROM products WHERE id = $productId LIMIT 1");
$product = $productResult ? mysqli_fetch_assoc($productResult) : null;

if (!$product) {
    set_flash('error', 'Товар не найден.');
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $imageName = $product['image'];

    if ($title === '' || $description === '' || $price <= 0) {
        $errors[] = 'Заполните все поля и укажите корректную цену.';
    }

    if (!empty($_FILES['image']['name'])) {
        $uploadedImage = upload_product_image($_FILES['image']);

        if ($uploadedImage === false) {
            $errors[] = 'Не удалось загрузить новое изображение.';
        } else {
            if (!empty($product['image'])) {
                $oldImagePath = __DIR__ . '/../uploads/' . $product['image'];

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imageName = $uploadedImage;
        }
    }

    if (empty($errors)) {
        $safeTitle = mysqli_real_escape_string($connection, $title);
        $safeDescription = mysqli_real_escape_string($connection, $description);
        $safeImage = mysqli_real_escape_string($connection, $imageName);
        $query = "UPDATE products SET title = '$safeTitle', description = '$safeDescription', price = $price, image = '$safeImage' WHERE id = $productId";

        if (mysqli_query($connection, $query)) {
            set_flash('success', 'Товар обновлен.');
            redirect('index.php');
        }

        $errors[] = 'Не удалось обновить товар.';
    }
}

$pageTitle = 'Редактирование товара';
$isAdminPage = true;

require_once __DIR__ . '/../partials/header.php';
?>
<section class="container section-spacing auth-shell">
    <form method="POST" enctype="multipart/form-data" class="auth-card product-form">
        <h1>Редактировать товар</h1>
        <p>Обновите информацию о товаре и при необходимости загрузите новое изображение.</p>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= escape($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label class="field">
            <span>Название</span>
            <input type="text" name="title" value="<?= escape($_POST['title'] ?? $product['title']); ?>" required>
        </label>

        <label class="field">
            <span>Описание</span>
            <textarea name="description" rows="5" required><?= escape($_POST['description'] ?? $product['description']); ?></textarea>
        </label>

        <label class="field">
            <span>Цена</span>
            <input type="number" name="price" min="1" value="<?= escape($_POST['price'] ?? $product['price']); ?>" required>
        </label>

        <label class="field">
            <span>Текущее изображение</span>
            <img
                src="<?= !empty($product['image']) ? '../uploads/' . escape($product['image']) : 'https://via.placeholder.com/420x260?text=No+Image'; ?>"
                alt="<?= escape($product['title']); ?>"
                class="edit-preview"
            >
        </label>

        <label class="field">
            <span>Новое изображение</span>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
        </label>

        <button type="submit" class="button button-wide">Обновить товар</button>
    </form>
</section>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
