<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController extends Controller
{
    public function index(): void
    {
        $categories = [];

        $this->view->render('pages/home.tpl', [
            'page_title' => 'Главная',
            'categories' => $categories,
        ]);
    }
}
