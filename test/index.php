<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];

    if ($productId > 0) {
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId]++;
        set_flash('success', 'Товар добавлен в корзину.');
    }

    redirect('index.php');
}

$productsResult = mysqli_query($connection, 'SELECT * FROM products ORDER BY id DESC');
$pageTitle = 'Каталог товаров';

require_once __DIR__ . '/partials/header.php';
?>
<section class="hero container">
    <div class="hero-copy">
        <p class="eyebrow">Минималистичный интернет-магазин</p>
        <h1>Подборка товаров с быстрой корзиной и простой авторизацией</h1>
        <p class="hero-text">Светлый интерфейс, аккуратные карточки, плавные переходы и базовая админ-панель для управления каталогом.</p>
    </div>
</section>

<section class="container section-spacing">
    <div class="section-head">
        <h2>Товары</h2>
        <p><?= $productsResult ? mysqli_num_rows($productsResult) : 0; ?> позиций в каталоге</p>
    </div>

    <div class="product-grid">
        <?php if ($productsResult && mysqli_num_rows($productsResult) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($productsResult)): ?>
                <article class="product-card">
                    <div class="product-image-wrap">
                        <img
                            src="<?= !empty($product['image']) ? 'uploads/' . escape($product['image']) : 'https://via.placeholder.com/600x400?text=No+Image'; ?>"
                            alt="<?= escape($product['title']); ?>"
                            class="product-image"
                        >
                    </div>
                    <div class="product-body">
                        <h3><?= escape($product['title']); ?></h3>
                        <p class="product-description"><?= escape($product['description']); ?></p>
                        <div class="product-footer">
                            <span class="price"><?= number_format((int) $product['price'], 0, '', ' '); ?> ₽</span>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?= (int) $product['id']; ?>">
                                <button type="submit" class="button">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <h3>Пока нет товаров</h3>
                <p>Добавьте первую позицию через админ-панель.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
