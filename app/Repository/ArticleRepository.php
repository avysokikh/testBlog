<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Article;
use App\Models\Category;
use App\Models\Model;
use PDO;

final class ArticleRepository extends Repository
{
    protected string|Model|null $entityClass = Article::class;

    public function findById(int $id): ?Article
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, image, title, description, body, views, published_at
             FROM {$this->table} WHERE id = :id LIMIT 1"
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
            FROM {$this->table} a
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

        self::attachCategories($items);

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    /**
     * @param list<Article> $articles
     */
    public function attachCategories(array $articles): void
    {
        if ($articles === []) {
            return;
        }

        $categoryTable = Category::$table;
        $ids = array_map('intval', array_column($articles, 'id'));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = <<<SQL
            SELECT ac.article_id, c.*
            FROM article_category ac
            INNER JOIN {$categoryTable} c ON c.id = ac.category_id
            WHERE ac.article_id IN ($placeholders)
            ORDER BY c.name ASC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($ids);
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $articleId = (int) $row['article_id'];
            $grouped[$articleId][] = $row;
        }

        foreach ($articles as &$article) {
            if ($categories = $grouped[(int) $article->id] ?? []) {
                $categories = array_map(fn(array $row): Category => Category::fromRow($row), $categories);
                $article->categories = $categories;
            }
        }
        unset($article);
    }

    public function incrementViews(Article $article): void
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $article->id]);

        $article->views++;
    }

    /** @return list<Article> */
    public function findSimilar(Article $article, int $limit): array
    {
        $categoryIds = $article->categoryIds();
        if (! $categoryIds) {
            return [];
        }

        $items = [];

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $sql = <<<SQL
            SELECT DISTINCT a.id, a.image, a.title, a.description, a.views, a.published_at
            FROM {$this->table} a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id IN ($placeholders)
              AND a.id != ?
            ORDER BY RAND() DESC
            LIMIT ?
        SQL;

        $params = array_merge($categoryIds, [$article->id, $limit]);
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $index => $value) {
            $stmt->bindValue($index + 1, $value, PDO::PARAM_INT);
        }
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            $items[] = Article::fromRow($row);
        }

        self::attachCategories($items);

        return $items;
    }
}
