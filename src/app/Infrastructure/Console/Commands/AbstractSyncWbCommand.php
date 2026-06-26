<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\WbSync\DTO\SyncWbEntitiesResult;
use App\Infrastructure\WbApi\Pagination\Pagination;
use Illuminate\Console\Command;

abstract class AbstractSyncWbCommand extends Command
{
    protected function pagination(): Pagination
    {
        return new Pagination(
            limit: $this->limit(),
        );
    }

    protected function finish(SyncWbEntitiesResult $result): int
    {
        $this->outputWarnings($result->warnings);

        if ($result->hasErrors()) {
            $this->outputErrors($result->errors);

            return self::FAILURE;
        }

        $this->outputProcessed($result);

        return self::SUCCESS;
    }

    private function limit(): ?string
    {
        $limit = $this->option('limit');

        if ($limit === null || $limit === '') {
            return null;
        }

        if (filter_var($limit, FILTER_VALIDATE_INT) === false || (int) $limit <= 0) {
            throw new \InvalidArgumentException('The "limit" option must be a positive integer.');
        }

        return (string) $limit;
    }

    /**
     * @param list<string> $warnings
     */
    private function outputWarnings(array $warnings): void
    {
        foreach ($warnings as $warning) {
            $this->warn($warning);
        }
    }

    /**
     * @param list<string> $errors
     */
    private function outputErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->error($error);
        }
    }

    private function outputProcessed(SyncWbEntitiesResult $result): void
    {
        $this->info(\sprintf(
            'Done. Processed: %d. Created: %d. Updated: %d. Skipped invalid: %d. Skipped outdated: %d.',
            $result->processed,
            $result->created,
            $result->updated,
            $result->skippedInvalid,
            $result->skippedOutdated,
        ));
    }
}
