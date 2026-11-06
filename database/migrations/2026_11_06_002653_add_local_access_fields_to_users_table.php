<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_primary_user')->nullable()->unique();
            $table->jsonb('menu_permissions')->nullable();
        });

        $primaryUserId = DB::table('users')->where('is_admin', true)->orderBy('id')->value('id')
            ?? DB::table('users')->orderBy('id')->value('id');

        if ($primaryUserId !== null) {
            DB::table('users')->where('id', $primaryUserId)->update(['is_primary_user' => true]);
        }

        DB::table('users')->whereNull('menu_permissions')->update([
            'menu_permissions' => json_encode(['dashboard', 'resumes'], JSON_THROW_ON_ERROR),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_primary_user', 'menu_permissions']);
        });
    }
};
