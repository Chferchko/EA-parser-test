<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\WbSync\DTO\SyncWbEntitiesInput;
use App\Application\WbSync\Handlers\SyncSalesHandler;
use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Filters\CommonQueryFilter;

final class WbSyncSales extends AbstractSyncWbCommand
{
    protected $signature = 'wb:sync-sales {--limit= : WB API limit}';

    protected $description = 'Synchronize sales from WB API.';

    public function handle(SyncSalesHandler $handler): int
    {
        return $this->finish($handler->handle(new SyncWbEntitiesInput(
            WbApiEndpoint::Sales,
            new CommonQueryFilter(new \DateTimeImmutable('today')),
            $this->pagination(),
        )));
    }
}
