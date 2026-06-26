<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Filters;

interface QueryFilterInterface
{
    /**
     * @return array<string, string>
     */
    public function toQuery(): array;
}
