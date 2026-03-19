<?php

if (!function_exists('escape')) {
    function escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('set_flash')) {
    function set_flash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}

if (!function_exists('get_flash')) {
    function get_flash()
    {
        if (empty($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return !empty($_SESSION['user']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return !empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
}

if (!function_exists('require_guest')) {
    function require_guest()
    {
        if (is_logged_in()) {
            redirect('index.php');
        }
    }
}

if (!function_exists('require_login')) {
    function require_login()
    {
        if (!is_logged_in()) {
            set_flash('error', 'Сначала войдите в аккаунт.');
            redirect('login.php');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin()
    {
        if (!is_admin()) {
            set_flash('error', 'Доступ разрешен только администратору.');
            redirect('../login.php');
        }
    }
}

if (!function_exists('cart_total_items')) {
    function cart_total_items()
    {
        if (empty($_SESSION['cart'])) {
            return 0;
        }

        return array_sum($_SESSION['cart']);
    }
}

if (!function_exists('cart_total_price')) {
    function cart_total_price($cartProducts)
    {
        $total = 0;

        foreach ($cartProducts as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}

if (!function_exists('fetch_cart_products')) {
    function fetch_cart_products($connection)
    {
        $items = [];

        if (empty($_SESSION['cart'])) {
            return $items;
        }

        $productIds = array_map('intval', array_keys($_SESSION['cart']));
        $placeholders = implode(',', $productIds);
        $query = "SELECT * FROM products WHERE id IN ($placeholders) ORDER BY id DESC";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            return $items;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $row['quantity'] = $_SESSION['cart'][$row['id']] ?? 1;
            $items[] = $row;
        }

        return $items;
    }
}

if (!function_exists('upload_product_image')) {
    function upload_product_image($file)
    {
        if (empty($file['name'])) {
            return '';
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        $fileName = uniqid('product_', true) . '.' . $extension;
        $targetPath = __DIR__ . '/../uploads/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return false;
        }

        return $fileName;
    }
}
