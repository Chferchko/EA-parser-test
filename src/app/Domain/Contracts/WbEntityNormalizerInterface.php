<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface WbEntityNormalizerInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function normalize(array $payload): WbEntityInterface;
}
