<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface WbEntityInterface
{
    public function uniqueKey(): string;
}
