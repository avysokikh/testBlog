<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

final class ArticleController extends Controller
{
    private CategoryRepository $categoryRepository;
    private ArticleRepository $articleRepository;

    protected function init()
    {
        $this->categoryRepository = new CategoryRepository($this->pdo);
        $this->articleRepository = new ArticleRepository($this->pdo);
    }

    public function show(int $id): void
    {
        $article = $this->articleRepository->findById($id);
        if ($article === null) {
            http_response_code(404);
            $this->view->render('pages/404.tpl', ['page_title' => 'Страница не найдена']);
            return;
        }

        $this->view->render('pages/article.tpl', [
            'page_title' => $article->title,
            'article' => $article,
        ]);
    }
}
