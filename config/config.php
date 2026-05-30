<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('MYSQL_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('MYSQL_PORT') ?: 3306),
        'name' => getenv('MYSQL_DATABASE') ?: 'blog',
        'user' => getenv('MYSQL_USER') ?: 'blog',
        'password' => getenv('MYSQL_PASSWORD') ?: 'blog',
    ],
    'app' => [
        'url' => rtrim(getenv('APP_URL') ?: 'http://localhost:8080', '/'),
    ],
    'smarty' => [
        'template_dir' => dirname(__DIR__) . '/templates',
        'compile_dir' => dirname(__DIR__) . '/templates_c',
        'cache_dir' => dirname(__DIR__) . '/cache',
    ],
];
