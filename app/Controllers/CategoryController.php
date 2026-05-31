<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

final class CategoryController extends Controller
{
    const int PER_PAGE = 6;
    private CategoryRepository $categoryRepository;
    private ArticleRepository $articleRepository;

    protected function init()
    {
        $this->categoryRepository = new CategoryRepository($this->pdo);
        $this->articleRepository = new ArticleRepository($this->pdo);
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

        $result = $this->articleRepository->findByCategory(
            (int) $category->id,
            $sort,
            $page,
            self::PER_PAGE
        );

        $totalPages = (int) max(1, ceil($result['total'] / self::PER_PAGE));

        if ($page > $totalPages && $result['total'] > 0) {
            $page = $totalPages;
            $result = $this->articleRepository->findByCategory(
                (int) $category->id,
                $sort,
                $page,
                self::PER_PAGE
            );
        }

        $this->view->render('pages/category.tpl', [
            'page_title' => $category->name,
            'category' => $category,
            'articles' => $result['items'],
            'sort' => $sort,
            'page' => $page,
            'total_pages' => $totalPages,
            'total' => $result['total'],
            'per_page' => self::PER_PAGE,
        ]);
    }
}
