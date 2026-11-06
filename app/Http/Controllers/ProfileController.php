<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): Response
    {
        return Inertia::render('Profile/Edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil atualizado.');
    }

    public function editMenu(Request $request): Response
    {
        $menuItems = collect(config('meuhub.menu_items'))
            ->filter(fn (array $item): bool => $request->user()->canAccessMenu($item['permission']))
            ->values();

        return Inertia::render('Profile/MenuPreferences', [
            'menuGroups' => $menuItems
                ->groupBy('group')
                ->map(fn ($items, string $group): array => [
                    'label' => $group,
                    'items' => $items->values(),
                ])
                ->values(),
            'hiddenMenuItems' => array_values(array_intersect(
                $request->user()->hidden_menu_items ?? [],
                $menuItems->pluck('key')->all(),
            )),
        ]);
    }

    public function updateMenu(Request $request): RedirectResponse
    {
        $menuKeys = collect(config('meuhub.menu_items'))->pluck('key')->all();

        $validated = $request->validate([
            'hidden_menu_items' => ['nullable', 'array'],
            'hidden_menu_items.*' => ['required', 'string', Rule::in($menuKeys)],
        ]);

        $request->user()->fill([
            'hidden_menu_items' => array_values(array_unique($validated['hidden_menu_items'] ?? [])),
        ])->save();

        return Redirect::route('settings.menu.edit')->with('success', 'Seu menu foi atualizado.');
    }
}
