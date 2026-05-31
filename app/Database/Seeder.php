<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Seeder
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $seedDir,
    ) {
    }

    public function run(bool $fresh = true): void
    {
        $categories = $this->loadJson('categories.json');
        $articles = $this->loadJson('articles.json');

        if ($fresh) {
            $this->truncate();
        }

        $this->pdo->beginTransaction();

        try {
            $categoryIds = $this->seedCategories($categories);
            $this->seedArticles($articles, $categoryIds);

            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    private function truncate(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->pdo->exec('TRUNCATE TABLE article_category');
        $this->pdo->exec('TRUNCATE TABLE articles');
        $this->pdo->exec('TRUNCATE TABLE categories');
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * @param list<array<string, mixed>> $categories
     * @return array<string, int> slug => id
     */
    private function seedCategories(array $categories): array
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (name, description, slug) VALUES (:name, :description, :slug)'
        );

        $ids = [];

        foreach ($categories as $category) {
            $slug = $this->requireString($category, 'slug', 'category');

            $stmt->execute([
                'name' => $this->requireString($category, 'name', 'category'),
                'description' => $this->requireString($category, 'description', 'category'),
                'slug' => $slug,
            ]);

            $ids[$slug] = (int) $this->pdo->lastInsertId();
        }

        return $ids;
    }

    /**
     * @param list<array<string, mixed>> $articles
     * @param array<string, int> $categoryIds
     */
    private function seedArticles(array $articles, array $categoryIds): void
    {
        $insertArticle = $this->pdo->prepare(
            'INSERT INTO articles (image, title, description, body, views, published_at)
             VALUES (:image, :title, :description, :body, :views, :published_at)'
        );

        $insertLink = $this->pdo->prepare(
            'INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)'
        );

        foreach ($articles as $article) {
            $insertArticle->execute([
                'image' => (string) ($article['image'] ?? ''),
                'title' => $this->requireString($article, 'title', 'article'),
                'description' => $this->requireString($article, 'description', 'article'),
                'body' => $this->requireString($article, 'body', 'article'),
                'views' => (int) ($article['views'] ?? 0),
                'published_at' => $this->requireString($article, 'published_at', 'article'),
            ]);

            $articleId = (int) $this->pdo->lastInsertId();
            $slugs = $article['categories'] ?? [];

            if (!is_array($slugs)) {
                throw new RuntimeException('Article categories must be an array of slugs.');
            }

            foreach ($slugs as $slug) {
                if (!is_string($slug) || !isset($categoryIds[$slug])) {
                    throw new RuntimeException(sprintf('Unknown category slug "%s" in article "%s".', (string) $slug, $article['title'] ?? ''));
                }

                $insertLink->execute([
                    'article_id' => $articleId,
                    'category_id' => $categoryIds[$slug],
                ]);
            }
        }
    }

    /** @return list<array<string, mixed>> */
    private function loadJson(string $filename): array
    {
        $path = rtrim($this->seedDir, '/') . '/' . $filename;

        if (!is_readable($path)) {
            throw new RuntimeException(sprintf('Seed file not found: %s', $path));
        }

        $data = json_decode((string) file_get_contents($path), true);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Invalid JSON in seed file: %s', $path));
        }

        return $data;
    }

    /** @param array<string, mixed> $row */
    private function requireString(array $row, string $key, string $entity): string
    {
        if (!isset($row[$key]) || !is_string($row[$key]) || trim($row[$key]) === '') {
            throw new RuntimeException(sprintf('Missing or invalid "%s" for %s.', $key, $entity));
        }

        return $row[$key];
    }
}
