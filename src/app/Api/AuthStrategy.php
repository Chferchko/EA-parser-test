<?php

declare(strict_types=1);

namespace App\Api;

interface AuthStrategy
{
    public function apply(array $query): array;
}
