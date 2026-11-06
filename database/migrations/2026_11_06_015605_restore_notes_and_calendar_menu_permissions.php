<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->select(['id', 'menu_permissions'])
            ->whereNotNull('menu_permissions')
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $permissions = json_decode($user->menu_permissions, true) ?? [];

                    if (count($permissions) === 2 && count(array_diff(['dashboard', 'resumes'], $permissions)) === 0) {
                        DB::table('users')->where('id', $user->id)->update([
                            'menu_permissions' => json_encode(['dashboard', 'resumes', 'notes', 'calendar'], JSON_THROW_ON_ERROR),
                        ]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->select(['id', 'menu_permissions'])
            ->whereNotNull('menu_permissions')
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $permissions = json_decode($user->menu_permissions, true) ?? [];

                    if (count($permissions) === 4 && count(array_diff(['dashboard', 'resumes', 'notes', 'calendar'], $permissions)) === 0) {
                        DB::table('users')->where('id', $user->id)->update([
                            'menu_permissions' => json_encode(['dashboard', 'resumes'], JSON_THROW_ON_ERROR),
                        ]);
                    }
                }
            });
    }
};
