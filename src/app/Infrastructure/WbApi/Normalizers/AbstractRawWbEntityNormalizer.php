<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Normalizers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Contracts\WbEntityNormalizerInterface;

abstract class AbstractRawWbEntityNormalizer implements WbEntityNormalizerInterface
{
    /**
     * @throws \JsonException
     */
    final public function normalize(array $payload): WbEntityInterface
    {
        return $this->makeEntity(
            $this->resolvePayloadHash($payload),
            $payload,
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    abstract protected function makeEntity(string $payloadHash, array $payload): WbEntityInterface;

    /**
     * @param array<string, mixed> $payload
     *
     * @throws \JsonException
     */
    private function resolvePayloadHash(array $payload): string
    {
        return hash('sha256', json_encode($payload, JSON_THROW_ON_ERROR));
    }
}
