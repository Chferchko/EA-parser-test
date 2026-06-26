<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Stock as StockEntity;
use App\Infrastructure\Models\Stock as StockModel;
use Illuminate\Database\Eloquent\Model;

final class StockModelMapper implements WbModelMapperInterface
{
    public function toAttributes(WbEntityInterface $entity): array
    {
        if (!$entity instanceof StockEntity) {
            throw new \InvalidArgumentException('Stock entity expected.');
        }

        return [
            'date' => $entity->date->format('Y-m-d'),
            'last_change_date' => $entity->lastChangeDate->format('Y-m-d'),
            'supplier_article' => $entity->supplierArticle,
            'tech_size' => $entity->techSize,
            'barcode' => $entity->barcode,
            'quantity' => $entity->quantity,
            'is_supply' => $entity->isSupply,
            'is_realization' => $entity->isRealization,
            'quantity_full' => $entity->quantityFull,
            'warehouse_name' => $entity->warehouseName,
            'in_way_to_client' => $entity->inWayToClient,
            'in_way_from_client' => $entity->inWayFromClient,
            'nm_id' => $entity->nmId,
            'subject' => $entity->subject,
            'category' => $entity->category,
            'brand' => $entity->brand,
            'sc_code' => $entity->scCode,
            'price' => $entity->price,
            'discount' => $entity->discount,
        ];
    }

    public function toDomain(Model $model): WbEntityInterface
    {
        if (!$model instanceof StockModel) {
            throw new \InvalidArgumentException('Stock model expected.');
        }

        $date = $model->getAttribute('date');
        $lastChangeDate = $model->getAttribute('last_change_date');

        if (!$date instanceof \DateTimeInterface || !$lastChangeDate instanceof \DateTimeInterface) {
            throw new \UnexpectedValueException('Stock model dates are invalid.');
        }

        return new StockEntity(
            \DateTimeImmutable::createFromInterface($date),
            \DateTimeImmutable::createFromInterface($lastChangeDate),
            (string) $model->getAttribute('supplier_article'),
            $model->getAttribute('tech_size'),
            (int) $model->getAttribute('barcode'),
            (int) $model->getAttribute('quantity'),
            (bool) $model->getAttribute('is_supply'),
            (bool) $model->getAttribute('is_realization'),
            (int) $model->getAttribute('quantity_full'),
            (string) $model->getAttribute('warehouse_name'),
            (int) $model->getAttribute('in_way_to_client'),
            (int) $model->getAttribute('in_way_from_client'),
            (int) $model->getAttribute('nm_id'),
            (string) $model->getAttribute('subject'),
            (string) $model->getAttribute('category'),
            (string) $model->getAttribute('brand'),
            (int) $model->getAttribute('sc_code'),
            (int) $model->getAttribute('price'),
            (int) $model->getAttribute('discount'),
        );
    }
}
