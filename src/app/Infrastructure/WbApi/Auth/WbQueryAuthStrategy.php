<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Auth;

final readonly class WbQueryAuthStrategy implements AuthStrategyInterface
{
    public function __construct(
        private string $key,
    ) {}

    /**
     * @param array<string, string> $query
     *
     * @return array<string, string>
     */
    public function apply(array $query): array
    {
        return array_merge($query, ['key' => $this->key]);
    }
}
