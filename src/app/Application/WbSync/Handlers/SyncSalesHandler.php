<?php

declare(strict_types=1);

namespace App\Application\WbSync\Handlers;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Infrastructure\WbApi\Client\WbApiClient;
use App\Infrastructure\WbApi\Normalizers\SaleNormalizer;

final class SyncSalesHandler extends AbstractSyncWbEntitiesHandler
{
    public function __construct(
        WbApiClient $client,
        SaleNormalizer $normalizer,
        SaleRepositoryInterface $repository,
    ) {
        parent::__construct($client, $normalizer, $repository);
    }
}
