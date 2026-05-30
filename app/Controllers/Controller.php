<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;

class Controller
{
    public function __construct(
        protected readonly View $view,
        protected readonly ?\PDO $pdo = null
    ) {
        $this->init();
    }

    protected function init()
    {
    }
}
