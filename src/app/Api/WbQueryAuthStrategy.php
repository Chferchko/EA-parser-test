<?php

declare(strict_types=1);

namespace App\Api;

final readonly class WbQueryAuthStrategy implements AuthStrategy
{
    private const string PARAM_NAME = 'key';

    public function __construct(
        private string $apiKey,
    ) {}

    public function apply(array $query): array
    {
        return array_merge($query, [
            self::PARAM_NAME => $this->apiKey,
        ]);
    }
}
