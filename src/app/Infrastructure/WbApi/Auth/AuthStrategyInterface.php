<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Auth;

interface AuthStrategyInterface
{
    /**
     * @param array<string, string> $query
     *
     * @return array<string, string>
     */
    public function apply(array $query): array;
}
