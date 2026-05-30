<?php

declare(strict_types=1);

namespace App\Controllers;

final class NotFoundController extends Controller
{
    public function show(): void
    {
        http_response_code(404);
        $this->view->render('pages/404.tpl', ['page_title' => 'Страница не найдена']);
    }
}
