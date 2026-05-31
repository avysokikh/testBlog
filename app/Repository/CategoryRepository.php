<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Article;
use App\Models\Category;
use App\Models\Model;

final class CategoryRepository extends Repository
{
    protected string|Model|null $entityClass = Category::class;

    public const ARTICLES_PER_CATEGORY = 3;

    public function findBySlug(string $slug): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE slug=:slug"
        );
        $stmt->execute(['slug' => $slug]);

        $row = $stmt->fetch();

        return $row ? $this->entityClass::fromRow($row) : null;
    }

    /**
     * @param list<Category> $categories
     *
     * @return list<Category>
     */
    public function loadArticles(array $categories): array
    {
        if (empty($categories)) {
            return [];
        }

        $articleTable = Article::$table;
        $articlesPerCategory = self::ARTICLES_PER_CATEGORY;

        $categoryIds = array_map(fn(Category $category): int => $category->id, $categories);
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $stmt = $this->pdo->prepare(
            "SELECT c.id AS category_id, a.*
             FROM {$this->table} c
             JOIN LATERAL (
                 SELECT a.id, a.image, a.title, a.description, a.views, a.published_at, a.created_at
                 FROM article_category ac
                 INNER JOIN {$articleTable} a ON a.id = ac.article_id
                 WHERE ac.category_id = c.id
                 ORDER BY a.published_at DESC
                 LIMIT {$articlesPerCategory}
             ) a ON TRUE
             WHERE c.id IN ({$placeholders})
             ORDER BY c.id, a.published_at DESC"
        );

        $stmt->execute($categoryIds);

        $articlesByCategory = [];
        foreach ($stmt->fetchAll() as $row) {
            $articlesByCategory[(int) $row['category_id']][] = Article::fromRow($row);
        }

        foreach ($categories as $category) {
            $category->articles = $articlesByCategory[$category->id] ?? [];
        }


        return $categories;
    }
}
