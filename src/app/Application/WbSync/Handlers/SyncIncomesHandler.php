<?php

declare(strict_types=1);

namespace App\Application\WbSync\Handlers;

use App\Domain\Contracts\IncomeRepositoryInterface;
use App\Infrastructure\WbApi\Client\WbApiClient;
use App\Infrastructure\WbApi\Normalizers\IncomeNormalizer;

final class SyncIncomesHandler extends AbstractSyncWbEntitiesHandler
{
    public function __construct(
        WbApiClient $client,
        IncomeNormalizer $normalizer,
        IncomeRepositoryInterface $repository,
    ) {
        parent::__construct($client, $normalizer, $repository);
    }
}
