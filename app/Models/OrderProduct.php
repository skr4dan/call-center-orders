<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    /** @use HasFactory<\Database\Factories\OrderProductFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'quantity',
        'unit',
    ];

    public const UNIT_PIECES = 'pcs';

    public const UNIT_SETS = 'sets';

    public const UNIT_LABELS = [
        self::UNIT_PIECES => 'Штуки',
        self::UNIT_SETS => 'Комплекты',
    ];

    public const SHORT_UNIT_LABELS = [
        self::UNIT_PIECES => 'шт.',
        self::UNIT_SETS => 'компл.',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
