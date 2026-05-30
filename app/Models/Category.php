<?php

declare(strict_types=1);

namespace App\Models;

final class Category extends Model
{
    public static string $table = 'categories';

    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public string $slug,
        public ?\DateTimeImmutable $publishedAt = null,
        public ?\DateTimeImmutable $createdAt = null,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int)$row['id'],
            name: $row['name'],
            description: $row['description'],
            slug: $row['slug'],
            publishedAt: isset($row['published_at'])
                ? new \DateTimeImmutable($row['published_at'])
                : null,
            createdAt: isset($row['created_at'])
                ? new \DateTimeImmutable($row['created_at'])
                : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
