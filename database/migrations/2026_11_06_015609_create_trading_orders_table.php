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
        Schema::create('trading_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trading_account_id')->constrained()->cascadeOnDelete();
            $table->string('symbol', 16);
            $table->string('side', 8);
            $table->decimal('quantity', 24, 8);
            $table->decimal('execution_price', 24, 8);
            $table->decimal('gross_amount', 24, 8);
            $table->decimal('fee_amount', 24, 8);
            $table->decimal('realized_pnl', 24, 8)->default(0);
            $table->string('status', 16)->default('filled');
            $table->dateTime('executed_at');
            $table->timestamps();
            $table->index(['trading_account_id', 'executed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_orders');
    }
};
