<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'payload_hash',
    'payload',
])]
final class Order extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
