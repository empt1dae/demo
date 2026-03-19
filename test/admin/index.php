<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    $productId = (int) $_POST['delete_product_id'];
    $productResult = mysqli_query($connection, "SELECT image FROM products WHERE id = $productId LIMIT 1");
    $product = $productResult ? mysqli_fetch_assoc($productResult) : null;

    if ($product && mysqli_query($connection, "DELETE FROM products WHERE id = $productId")) {
        if (!empty($product['image'])) {
            $imagePath = __DIR__ . '/../uploads/' . $product['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        set_flash('success', 'Товар удален.');
    } else {
        set_flash('error', 'Не удалось удалить товар.');
    }

    redirect('index.php');
}

$productsResult = mysqli_query($connection, 'SELECT * FROM products ORDER BY id DESC');
$pageTitle = 'Админ-панель';
$isAdminPage = true;

require_once __DIR__ . '/../partials/header.php';
?>
<section class="container section-spacing">
    <div class="section-head section-head-spread">
        <div>
            <h1>Управление товарами</h1>
            <p>Добавляйте, редактируйте и удаляйте позиции каталога.</p>
        </div>
        <a href="create.php" class="button">Добавить товар</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($productsResult && mysqli_num_rows($productsResult) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($productsResult)): ?>
                        <tr>
                            <td><?= (int) $product['id']; ?></td>
                            <td>
                                <img
                                    src="<?= !empty($product['image']) ? '../uploads/' . escape($product['image']) : 'https://via.placeholder.com/120x90?text=No+Image'; ?>"
                                    alt="<?= escape($product['title']); ?>"
                                    class="admin-thumb"
                                >
                            </td>
                            <td><?= escape($product['title']); ?></td>
                            <td><?= number_format((int) $product['price'], 0, '', ' '); ?> ₽</td>
                            <td class="admin-actions">
                                <a href="edit.php?id=<?= (int) $product['id']; ?>" class="button button-light">Редактировать</a>
                                <form method="POST" onsubmit="return confirm('Удалить товар?');">
                                    <input type="hidden" name="delete_product_id" value="<?= (int) $product['id']; ?>">
                                    <button type="submit" class="button button-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Товары отсутствуют.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
