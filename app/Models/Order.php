<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_DONE = 'done';

    public const STATUS_LABELS = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_DONE => 'Завершен',
    ];

    protected $fillable = [
        'fio',
        'phone',
        'email',
        'inn',
        'company',
        'address',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
