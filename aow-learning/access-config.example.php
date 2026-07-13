<?php
declare(strict_types=1);

// Copy to access-config.php. Keep that file private and out of Git.
return [
    // Store the database outside public_html in production.
    'database_path' => dirname(__DIR__, 2) . '/private-data/aow-learning.sqlite',
    // Generate with: php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
    'app_key' => 'REPLACE_WITH_64_RANDOM_HEX_CHARACTERS',
    // Generate with: php -r "echo password_hash(\'ADMIN-PASSWORD\', PASSWORD_DEFAULT), PHP_EOL;"
    'admin_password_hash' => '$2y$12$REPLACE_WITH_A_REAL_PASSWORD_HASH',
];
