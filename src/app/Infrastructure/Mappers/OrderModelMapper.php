<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Order;

final class OrderModelMapper extends AbstractRawWbModelMapper
{
    protected function payload(WbEntityInterface $entity): array
    {
        if (!$entity instanceof Order) {
            throw new \InvalidArgumentException('Order entity expected.');
        }

        return $entity->payload;
    }

    protected function makeDomain(string $payloadHash, array $payload): WbEntityInterface
    {
        return new Order($payloadHash, $payload);
    }
}
