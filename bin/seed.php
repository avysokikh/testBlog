#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\DB;
use App\Database\Seeder;

require dirname(__DIR__) . '/vendor/autoload.php';

loadEnv(dirname(__DIR__) . '/.env');

$config = require dirname(__DIR__) . '/config/config.php';
$fresh = !in_array('--append', $argv ?? [], true);

$pdo = DB::connect($config['db']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$seedDir = dirname(__DIR__) . '/database/seed';

try {
    (new Seeder($pdo, $seedDir))->run($fresh);
    $mode = $fresh ? 'fresh' : 'append';
    echo sprintf("Database seeded successfully (%s mode).\n", $mode);
} catch (Throwable $e) {
    fwrite(STDERR, 'Seeding failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

function loadEnv(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$name, $value] = array_map('trim', explode('=', $line, 2));

        if ($name === '' || getenv($name) !== false) {
            continue;
        }

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
