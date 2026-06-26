<?php

declare(strict_types=1);

namespace App\Api;

final readonly class CommonQueryFilter implements QueryFilterInterface
{
    public function __construct(
        public \DateTimeImmutable $dateFrom,
        public ?\DateTimeImmutable $dateTo = null,
    ) {}

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return array_filter([
            FilterOption::DateFrom->toString() => $this->dateFrom->format('Y-m-d'),
            FilterOption::DateTo->toString() => $this->dateTo?->format('Y-m-d'),
        ], static fn (?string $value): bool => $value !== null);
    }
}
