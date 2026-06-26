<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Contracts\WbEntityInterface;

final readonly class Order implements WbEntityInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public string $payloadHash,
        public array $payload,
    ) {}

    public function uniqueKey(): string
    {
        return $this->payloadHash;
    }
}
