<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'date',
    'last_change_date',
    'supplier_article',
    'tech_size',
    'barcode',
    'quantity',
    'is_supply',
    'is_realization',
    'quantity_full',
    'warehouse_name',
    'in_way_to_client',
    'in_way_from_client',
    'nm_id',
    'subject',
    'category',
    'brand',
    'sc_code',
    'price',
    'discount',
])]
final class Stock extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'immutable_date',
            'last_change_date' => 'immutable_date',
            'barcode' => 'integer',
            'quantity' => 'integer',
            'is_supply' => 'boolean',
            'is_realization' => 'boolean',
            'quantity_full' => 'integer',
            'in_way_to_client' => 'integer',
            'in_way_from_client' => 'integer',
            'nm_id' => 'integer',
            'sc_code' => 'integer',
            'price' => 'integer',
            'discount' => 'integer',
        ];
    }
}
