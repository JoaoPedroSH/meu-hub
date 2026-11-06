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
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('market', 32)->default('crypto_spot');
            $table->string('provider', 32)->default('binance_public');
            $table->string('mode', 16)->default('paper');
            $table->string('base_currency', 8)->default('USDT');
            $table->decimal('initial_balance', 24, 8)->default(10000);
            $table->decimal('cash_balance', 24, 8)->default(10000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_accounts');
    }
};
