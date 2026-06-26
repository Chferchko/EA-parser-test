<?php

declare(strict_types=1);

namespace App\Application\WbSync\Handlers;

use App\Domain\Contracts\OrderRepositoryInterface;
use App\Infrastructure\WbApi\Client\WbApiClient;
use App\Infrastructure\WbApi\Normalizers\OrderNormalizer;

final class SyncOrdersHandler extends AbstractSyncWbEntitiesHandler
{
    public function __construct(
        WbApiClient $client,
        OrderNormalizer $normalizer,
        OrderRepositoryInterface $repository,
    ) {
        parent::__construct($client, $normalizer, $repository);
    }
}
