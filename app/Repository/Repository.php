<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Model;
use PDO;

class Repository
{
    protected string $table;

    protected string|Model|null $entityClass = null;

    public function __construct(protected readonly PDO $pdo)
    {
        if ($this->entityClass !== null && property_exists($this->entityClass, 'table')) {
            $this->table = $this->entityClass::$table;
        }
    }

    public function find(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table}"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll();

        if ($rows === []) {
            return [];
        }

        if ($this->entityClass === null) {
            return $rows;
        }

        return array_map(
            fn(array $row): Model => $this->entityClass::fromRow($row),
            $rows
        );
    }
}
