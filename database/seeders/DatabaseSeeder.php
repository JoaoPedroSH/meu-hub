<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'development', 'testing'])) {
            return;
        }

        $email = config('app.demo_user.email');
        $password = config('app.demo_user.password');

        if (! $email || ! $password) {
            return;
        }

        DB::transaction(function () use ($email, $password): void {
            $demoUser = User::query()->firstOrNew(['email' => $email]);

            User::query()
                ->where('is_primary_user', true)
                ->where('email', '!=', $email)
                ->update(['is_primary_user' => false]);

            $demoUser->fill([
                'name' => 'Usuário de demonstração',
                'password' => $password,
                'is_primary_user' => true,
                'menu_permissions' => User::MENU_KEYS,
            ]);
            $demoUser->email_verified_at = now();
            $demoUser->save();
        });
    }
}
