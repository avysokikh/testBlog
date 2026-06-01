<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\NotFoundController;

final readonly class Router
{
    public function __construct(private array $config) {}

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        $view = new View($this->config['smarty'], $this->config['app']['url']);
        $pdo = DB::connect($this->config['db']);

        if ($method !== 'GET') {
            new NotFoundController($view)->show();

            return;
        }

        if ($path === '/') {
            new HomeController($view, $pdo)->index();

            return;
        }

        if (preg_match('#^/category/([a-z0-9\-]+)$#', $path, $matches)) {
            new CategoryController($view, $pdo)->show($matches[1]);
            return;
        }

        if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
            new ArticleController($view, $pdo)->show((int) $matches[1]);
            return;
        }

        new NotFoundController($view)->show();
    }
}
