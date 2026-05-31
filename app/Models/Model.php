<?php

declare(strict_types=1);

namespace App\Models;

abstract class Model
{
    abstract public static function fromRow(array $row): self;
}