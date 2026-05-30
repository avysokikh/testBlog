<?php

namespace App\Models;

abstract class Model
{
    public static abstract function fromRow(array $row): self;
}