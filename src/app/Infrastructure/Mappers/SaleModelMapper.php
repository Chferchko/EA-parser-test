<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Sale;

final class SaleModelMapper extends AbstractRawWbModelMapper
{
    protected function payload(WbEntityInterface $entity): array
    {
        if (!$entity instanceof Sale) {
            throw new \InvalidArgumentException('Sale entity expected.');
        }

        return $entity->payload;
    }

    protected function makeDomain(string $payloadHash, array $payload): WbEntityInterface
    {
        return new Sale($payloadHash, $payload);
    }
}
