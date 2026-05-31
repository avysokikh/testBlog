<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Article;
use PDO;

final class ArticleRepository extends Repository
{
    public function findById(int $id): ?Article
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, image, title, description, body, views, published_at
             FROM articles WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch();

        if (!$article) {
            return null;
        }

        return Article::fromRow($article);
    }

    /**
     * @return array{items: list<array<string, mixed>>, total: int}
     */
    public function findByCategory(
        int $categoryId,
        string $sort,
        int $page,
        int $perPage
    ): array {
        $items = [];

        $orderBy = $sort === 'views'
            ? 'a.views DESC, a.published_at DESC'
            : 'a.published_at DESC';

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM article_category ac WHERE ac.category_id = :category_id'
        );
        $countStmt->execute(['category_id' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);

        $sql = <<<SQL
            SELECT a.id, a.image, a.title, a.description, a.views, a.published_at
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id AND published_at <= NOW()
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            $items[] = Article::fromRow($row);
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
