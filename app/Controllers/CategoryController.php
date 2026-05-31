<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\CategoryRepository;

final class CategoryController extends Controller
{
    const int PER_PAGE = 4;
    private CategoryRepository $categoryRepository;

    protected function init()
    {
        $this->categoryRepository = new CategoryRepository($this->pdo);
    }

    public function show(string $slug): void
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            new NotFoundController($this->view)->show();
            return;
        }

        $sort = $_GET['sort'] ?? 'date';
        if (!in_array($sort, ['date', 'views'], true)) {
            $sort = 'date';
        }

        $page = max(1, (int)($_GET['page'] ?? 1));

        $this->view->render('pages/category.tpl', [
            'page_title' => $category->name,
            'category' => $category,
            'articles' => [],
            'sort' => $sort,
            'page' => $page,
            'per_page' => self::PER_PAGE,
        ]);
    }
}
