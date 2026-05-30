<?php

declare(strict_types=1);

namespace App;

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

        if ($method !== 'GET') {
            new NotFoundController($view)->show();

            return;
        }

        if ($path === '/') {
            new HomeController($view)->index();

            return;
        }

        new NotFoundController($view)->show();
    }
}
