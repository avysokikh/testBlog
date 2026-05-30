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
}
