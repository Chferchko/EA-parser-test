<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\WbSync\DTO\SyncWbEntitiesInput;
use App\Application\WbSync\Handlers\SyncIncomesHandler;
use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Filters\CommonQueryFilter;

final class WbSyncIncomes extends AbstractSyncWbCommand
{
    protected $signature = 'wb:sync-incomes {--limit= : WB API limit}';

    protected $description = 'Synchronize incomes from WB API.';

    public function handle(SyncIncomesHandler $handler): int
    {
        return $this->finish($handler->handle(new SyncWbEntitiesInput(
            WbApiEndpoint::Incomes,
            new CommonQueryFilter(new \DateTimeImmutable('today')),
            $this->pagination(),
        )));
    }
}
