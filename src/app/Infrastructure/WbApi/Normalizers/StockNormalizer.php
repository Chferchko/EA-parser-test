<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Normalizers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Contracts\WbEntityNormalizerInterface;
use App\Domain\Entities\Stock;

final class StockNormalizer implements WbEntityNormalizerInterface
{
    public function normalize(array $payload): WbEntityInterface
    {
        return new Stock(
            $this->dateValue($payload, 'date'),
            $this->dateValue($payload, 'last_change_date'),
            $this->stringValue($payload, 'supplier_article'),
            $this->nullableStringValue($payload, 'tech_size'),
            $this->intValue($payload, 'barcode'),
            $this->intValue($payload, 'quantity'),
            $this->boolValue($payload, 'is_supply'),
            $this->boolValue($payload, 'is_realization'),
            $this->intValue($payload, 'quantity_full'),
            $this->stringValue($payload, 'warehouse_name'),
            $this->intValue($payload, 'in_way_to_client'),
            $this->intValue($payload, 'in_way_from_client'),
            $this->intValue($payload, 'nm_id'),
            $this->stringValue($payload, 'subject'),
            $this->stringValue($payload, 'category'),
            $this->stringValue($payload, 'brand'),
            $this->intValue($payload, 'sc_code'),
            $this->intValue($payload, 'price'),
            $this->intValue($payload, 'discount'),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function dateValue(array $payload, string $key): \DateTimeImmutable
    {
        $value = $payload[$key] ?? null;

        if (!\is_string($value) || $value === '') {
            throw new \UnexpectedValueException(\sprintf('Stock payload missing "%s".', $key));
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Throwable $exception) {
            throw new \UnexpectedValueException(\sprintf('Stock payload has invalid "%s".', $key), 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function stringValue(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;

        if (!\is_scalar($value) || (string) $value === '') {
            throw new \UnexpectedValueException(\sprintf('Stock payload missing "%s".', $key));
        }

        return (string) $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function nullableStringValue(array $payload, string $key): ?string
    {
        $value = $payload[$key] ?? null;

        if ($value === null || $value === '') {
            return null;
        }

        if (!\is_scalar($value)) {
            throw new \UnexpectedValueException(\sprintf('Stock payload has invalid "%s".', $key));
        }

        return (string) $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function intValue(array $payload, string $key): int
    {
        $value = $payload[$key] ?? null;

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new \UnexpectedValueException(\sprintf('Stock payload has invalid "%s".', $key));
        }

        return (int) $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function boolValue(array $payload, string $key): bool
    {
        $value = $payload[$key] ?? null;

        if (\is_bool($value)) {
            return $value;
        }

        if ($value === 0 || $value === 1 || $value === '0' || $value === '1') {
            return (bool) $value;
        }

        throw new \UnexpectedValueException(\sprintf('Stock payload has invalid "%s".', $key));
    }
}
