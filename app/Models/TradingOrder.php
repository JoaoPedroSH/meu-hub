<?php

namespace App\Models;

use Database\Factories\TradingOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradingOrder extends Model
{
    /** @use HasFactory<TradingOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'symbol', 'side', 'quantity', 'execution_price', 'gross_amount',
        'fee_amount', 'realized_pnl', 'status', 'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'execution_price' => 'decimal:8',
            'gross_amount' => 'decimal:8',
            'fee_amount' => 'decimal:8',
            'realized_pnl' => 'decimal:8',
            'executed_at' => 'datetime',
        ];
    }

    public function tradingAccount(): BelongsTo
    {
        return $this->belongsTo(TradingAccount::class);
    }
}
