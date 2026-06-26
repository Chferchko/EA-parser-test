<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Enums\PersistenceOutcome;

interface WbEntityRepositoryInterface
{
    public function save(WbEntityInterface $entity): PersistenceOutcome;
}
