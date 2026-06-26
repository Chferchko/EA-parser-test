<?php

declare(strict_types=1);

namespace App\Api;

interface QueryFilterInterface
{
    /**
     * @return array<string, string>
     */
    public function toQuery(): array;
}
