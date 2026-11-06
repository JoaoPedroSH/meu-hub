<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class LocalUserController extends Controller
{
    public function setup(): Response|RedirectResponse
    {
        if (User::query()->exists()) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/Setup');
    }

    public function createPrimary(Request $request): RedirectResponse
    {
        if (User::query()->exists()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            abort_if(User::query()->exists(), 409, 'A configuração inicial já foi concluída.');

            return User::create([
                ...$validated,
                'is_primary_user' => true,
                'menu_permissions' => User::MENU_KEYS,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function index(): Response
    {
        return Inertia::render('Profile/Users', [
            'users' => User::query()
                ->orderByDesc('is_primary_user')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'is_primary_user', 'menu_permissions']),
            'availableMenus' => [
                ...collect(config('meuhub.menu_items'))
                    ->map(fn (array $item): array => ['key' => $item['key'], 'label' => $item['label']])
                    ->all(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'menu_permissions' => ['required', 'array', 'min:1'],
            'menu_permissions.*' => ['required', 'string', Rule::in(User::MENU_KEYS)],
        ]);

        User::create($validated);

        return redirect()->route('settings.users.index')->with('success', 'Usuário local criado.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is_primary_user, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'menu_permissions' => ['required', 'array', 'min:1'],
            'menu_permissions.*' => ['required', 'string', Rule::in(User::MENU_KEYS)],
        ]);

        $user->fill(collect($validated)->only(['name', 'email', 'menu_permissions'])->all());

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('settings.users.index')->with('success', 'Acessos do usuário atualizados.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is_primary_user, 403);

        $user->delete();

        return redirect()->route('settings.users.index')->with('success', 'Usuário local removido.');
    }
}
