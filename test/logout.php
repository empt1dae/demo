<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

unset($_SESSION['user']);
set_flash('success', 'Вы вышли из аккаунта.');
redirect('index.php');
