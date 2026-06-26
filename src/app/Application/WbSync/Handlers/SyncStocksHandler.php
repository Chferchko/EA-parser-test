<?php

declare(strict_types=1);

namespace App\Application\WbSync\Handlers;

use App\Domain\Contracts\StockRepositoryInterface;
use App\Infrastructure\WbApi\Client\WbApiClient;
use App\Infrastructure\WbApi\Normalizers\StockNormalizer;

final class SyncStocksHandler extends AbstractSyncWbEntitiesHandler
{
    public function __construct(
        WbApiClient $client,
        StockNormalizer $normalizer,
        StockRepositoryInterface $repository,
    ) {
        parent::__construct($client, $normalizer, $repository);
    }
}
