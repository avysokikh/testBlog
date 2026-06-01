<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\PlainText;

final class Category extends Model
{
    public static string $table = 'categories';

    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public string $slug,
        public ?\DateTimeImmutable $createdAt = null,
        public array $articles = [],
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            name: PlainText::sanitize($row['name']),
            description: PlainText::sanitize($row['description']),
            slug: $row['slug'],
            createdAt: isset($row['created_at'])
                ? new \DateTimeImmutable($row['created_at'])
                : null,
        );
    }
}
