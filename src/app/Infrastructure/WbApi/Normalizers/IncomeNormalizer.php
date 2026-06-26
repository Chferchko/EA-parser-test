<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Normalizers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Income;

final class IncomeNormalizer extends AbstractRawWbEntityNormalizer
{
    protected function makeEntity(string $payloadHash, array $payload): WbEntityInterface
    {
        return new Income($payloadHash, $payload);
    }
}
