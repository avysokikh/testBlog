<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Category;
use App\Models\Model;

/** @extends Repository<Category> */
final class CategoryRepository extends Repository
{
    /** @var class-string<Category> */
    protected string|Model|null $entityClass = Category::class;

    public function findBySlug(string $slug): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE slug=:slug"
        );
        $stmt->execute(['slug' => $slug]);

        $row = $stmt->fetch();

        return $row ? $this->entityClass::fromRow($row) : null;
    }
}
