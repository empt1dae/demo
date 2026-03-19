<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int) ($_POST['product_id'] ?? 0);

    if (isset($_POST['remove']) && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        set_flash('success', 'Товар удален из корзины.');
        redirect('cart.php');
    }

    if (isset($_POST['decrease']) && isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]--;

        if ($_SESSION['cart'][$productId] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }

        set_flash('success', 'Количество товара обновлено.');
        redirect('cart.php');
    }

    if (isset($_POST['increase'])) {
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId]++;
        set_flash('success', 'Количество товара обновлено.');
        redirect('cart.php');
    }
}

$cartProducts = fetch_cart_products($connection);
$pageTitle = 'Корзина';

require_once __DIR__ . '/partials/header.php';
?>
<section class="container section-spacing">
    <div class="section-head">
        <h1>Корзина</h1>
        <p>Проверьте выбранные товары и итоговую стоимость.</p>
    </div>

    <?php if (!empty($cartProducts)): ?>
        <div class="cart-layout">
            <div class="cart-list">
                <?php foreach ($cartProducts as $item): ?>
                    <article class="cart-item">
                        <img
                            src="<?= !empty($item['image']) ? 'uploads/' . escape($item['image']) : 'https://via.placeholder.com/300x220?text=No+Image'; ?>"
                            alt="<?= escape($item['title']); ?>"
                            class="cart-image"
                        >
                        <div class="cart-content">
                            <h3><?= escape($item['title']); ?></h3>
                            <p><?= escape($item['description']); ?></p>
                            <div class="cart-meta">
                                <span><?= number_format((int) $item['price'], 0, '', ' '); ?> ₽</span>
                                <span>Количество: <?= (int) $item['quantity']; ?></span>
                                <span>Сумма: <?= number_format((int) $item['price'] * (int) $item['quantity'], 0, '', ' '); ?> ₽</span>
                            </div>
                            <div class="cart-actions">
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= (int) $item['id']; ?>">
                                    <button type="submit" name="decrease" class="button button-light">-</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= (int) $item['id']; ?>">
                                    <button type="submit" name="increase" class="button button-light">+</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= (int) $item['id']; ?>">
                                    <button type="submit" name="remove" class="button button-danger">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <aside class="summary-card">
                <h2>Итого</h2>
                <p class="summary-line">
                    <span>Товаров</span>
                    <strong><?= cart_total_items(); ?></strong>
                </p>
                <p class="summary-line">
                    <span>Общая сумма</span>
                    <strong><?= number_format(cart_total_price($cartProducts), 0, '', ' '); ?> ₽</strong>
                </p>
            </aside>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Корзина пуста</h3>
            <p>Вернитесь в каталог и добавьте товары.</p>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
