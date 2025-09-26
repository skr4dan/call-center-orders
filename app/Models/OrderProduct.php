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

    public const UNIT_KILOGRAMS = 'kg';

    public const UNIT_LITERS = 'l';

    public const UNIT_METERS = 'm';

    public const UNIT_LABELS = [
        self::UNIT_PIECES => 'Штуки',
        self::UNIT_KILOGRAMS => 'Килограммы',
        self::UNIT_LITERS => 'Литры',
        self::UNIT_METERS => 'Метры',
    ];

    public const SHORT_UNIT_LABELS = [
        self::UNIT_PIECES => 'шт.',
        self::UNIT_KILOGRAMS => 'кг.',
        self::UNIT_LITERS => 'л.',
        self::UNIT_METERS => 'м.',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
