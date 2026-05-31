<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\ArticleRepository;

final class ArticleController extends Controller
{
    private ArticleRepository $articleRepository;

    protected function init(): void
    {
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

        $this->articleRepository->incrementViews($article);

        $this->articleRepository->attachCategories([$article]);

        $similar = $this->articleRepository->findSimilar(
            $article,
            3
        );

        $this->view->render('pages/article.tpl', [
            'page_title' => $article->title,
            'article' => $article,
            'similar' => $similar,
        ]);
    }
}
