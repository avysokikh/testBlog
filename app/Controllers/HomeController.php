<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\CategoryRepository;

final class HomeController extends Controller
{
    private CategoryRepository $categoryRepository;

    protected function init()
    {
        $this->categoryRepository = new CategoryRepository($this->pdo);
    }

    public function index(): void
    {
        $categories = $this->categoryRepository->find();
        $categories = $this->categoryRepository->loadArticles($categories);

        $this->view->render('pages/home.tpl', [
            'page_title' => 'Главная',
            'categories' => $categories,
        ]);
    }
}
