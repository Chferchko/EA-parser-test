<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final readonly class Stock
{
    public function __construct(
        public \DateTimeImmutable $date,
        public \DateTimeImmutable $lastChangeDate,
        public string $supplierArticle,
        public string $techSize,
        public int $barcode,
        public int $quantity,
        public bool $isSupply,
        public bool $isRealization,
        public int $quantityFull,
        public string $warehouseName,
        public int $inWayToClient,
        public int $inWayFromClient,
        public int $nmId,
        public string $subject,
        public string $category,
        public string $brand,
        public int $scCode,
        public int $price,
        public int $discount,
    ) {}
}
