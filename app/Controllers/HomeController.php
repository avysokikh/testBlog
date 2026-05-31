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
        $this->categoryRepository->attachArticles($categories);
        $categories = array_values(array_filter(
            $categories,
            static fn ($category): bool => $category->articles !== [],
        ));

        $this->view->render('pages/home.tpl', [
            'page_title' => 'Главная',
            'categories' => $categories,
        ]);
    }
}
