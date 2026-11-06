<?php

namespace App\Models;

use Database\Factories\TradingAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradingAccount extends Model
{
    /** @use HasFactory<TradingAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'market', 'provider', 'mode', 'base_currency',
        'initial_balance', 'cash_balance', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'initial_balance' => 'decimal:8',
            'cash_balance' => 'decimal:8',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(TradingPosition::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(TradingOrder::class);
    }
}
