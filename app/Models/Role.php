<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    const MANAGER = 'manager';

    const OPERATOR = 'operator';

    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isManager(): bool
    {
        return $this->name === self::MANAGER;
    }

    public function isOperator(): bool
    {
        return $this->name === self::OPERATOR;
    }
}
