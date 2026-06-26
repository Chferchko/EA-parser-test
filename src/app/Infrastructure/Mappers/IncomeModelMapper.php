<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Income;

final class IncomeModelMapper extends AbstractRawWbModelMapper
{
    protected function payload(WbEntityInterface $entity): array
    {
        if (!$entity instanceof Income) {
            throw new \InvalidArgumentException('Income entity expected.');
        }

        return $entity->payload;
    }

    protected function makeDomain(string $payloadHash, array $payload): WbEntityInterface
    {
        return new Income($payloadHash, $payload);
    }
}
