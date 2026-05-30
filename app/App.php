<?php

declare(strict_types=1);

namespace App;

final readonly class App
{

    public function __construct(private array $config = []) {}

    public function run(): void
    {
        $router = new Router($this->config);
        $router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
    }
}
