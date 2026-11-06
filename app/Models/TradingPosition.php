<?php

namespace App\Models;

use Database\Factories\TradingPositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradingPosition extends Model
{
    /** @use HasFactory<TradingPositionFactory> */
    use HasFactory;

    protected $fillable = ['symbol', 'quantity', 'average_entry_price', 'realized_pnl'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:8',
            'average_entry_price' => 'decimal:8',
            'realized_pnl' => 'decimal:8',
        ];
    }

    public function tradingAccount(): BelongsTo
    {
        return $this->belongsTo(TradingAccount::class);
    }
}
