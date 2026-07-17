<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], '', (bool)$params['secure'], true);
}
session_destroy();
redirect('login.php');
