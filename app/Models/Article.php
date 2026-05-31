<?php

declare(strict_types=1);

namespace App\Models;

final class Article extends Model
{
    public static string $table = 'articles';

    /**
     * @param list<Category> $categories
     */
    public function __construct(
        public int $id,
        public string $image,
        public string $title,
        public string $description,
        public int $views,
        public \DateTimeImmutable $publishedAt,
        public string $body = '',
        public ?\DateTimeImmutable $createdAt = null,
        public array $categories = [],
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            image: $row['image'] ?? '',
            title: $row['title'],
            description: $row['description'],
            views: (int) ($row['views'] ?? 0),
            publishedAt: new \DateTimeImmutable($row['published_at']),
            body: $row['body'] ?? '',
            createdAt: isset($row['created_at'])
                ? new \DateTimeImmutable($row['created_at'])
                : null,
        );
    }

    /** @return list<int> */
    public function categoryIds(): array
    {
        return array_map(fn (Category $category): int => $category->id, $this->categories);
    }
}
