<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Pagination;

use App\Infrastructure\WbApi\Enums\PaginationOption;

final readonly class Pagination
{
    public function __construct(
        private ?string $page = null,
        private ?string $limit = null,
    ) {}

    /**
     * @return array{page: string, limit: string}
     */
    public function toQuery(): array
    {
        return [
            PaginationOption::Page->toString() => $this->page ?? PaginationOption::Page->toDefault(),
            PaginationOption::Limit->toString() => $this->limit ?? PaginationOption::Limit->toDefault(),
        ];
    }
}
