<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trading_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trading_account_id')->constrained()->cascadeOnDelete();
            $table->string('symbol', 16);
            $table->decimal('quantity', 24, 8)->default(0);
            $table->decimal('average_entry_price', 24, 8)->default(0);
            $table->decimal('realized_pnl', 24, 8)->default(0);
            $table->timestamps();
            $table->unique(['trading_account_id', 'symbol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_positions');
    }
};
