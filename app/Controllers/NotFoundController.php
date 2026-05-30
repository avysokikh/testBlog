<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;

final readonly class NotFoundController
{
    public function __construct(private View $view)
    {
    }

    public function show(): void
    {
        http_response_code(404);
        $this->view->render('pages/404.tpl', ['page_title' => 'Страница не найдена']);
    }
}
