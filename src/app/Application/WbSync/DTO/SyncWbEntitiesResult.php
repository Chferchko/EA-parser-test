<?php

declare(strict_types=1);

namespace App\Application\WbSync\DTO;

use App\Domain\Enums\PersistenceOutcome;

final class SyncWbEntitiesResult
{
    public int $processed = 0;

    public int $created = 0;

    public int $updated = 0;

    public int $skippedInvalid = 0;

    public int $skippedOutdated = 0;

    /** @var list<string> */
    public array $warnings = [];

    /** @var list<string> */
    public array $errors = [];

    public function registerPersistence(PersistenceOutcome $outcome): void
    {
        match ($outcome) {
            PersistenceOutcome::Created => $this->registerCreated(),
            PersistenceOutcome::Updated => $this->registerUpdated(),
            PersistenceOutcome::SkippedOutdated => ++$this->skippedOutdated,
        };
    }

    public function registerInvalidSkipped(string $warning): void
    {
        ++$this->skippedInvalid;
        $this->warnings[] = $warning;
    }

    public function addWarning(string $warning): void
    {
        $this->warnings[] = $warning;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    private function registerCreated(): void
    {
        ++$this->processed;
        ++$this->created;
    }

    private function registerUpdated(): void
    {
        ++$this->processed;
        ++$this->updated;
    }
}
