<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
redirect(current_user() ? 'dashboard.php' : 'login.php');
