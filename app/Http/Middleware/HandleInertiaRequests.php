<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user?->only(['id', 'name', 'email', 'is_primary_user']),
                'menus' => [
                    'dashboard' => $user?->canShowMenu('dashboard') ?? false,
                    'resumes' => $user?->canShowMenu('resumes') ?? false,
                    'notes' => $user?->canShowMenu('notes') ?? false,
                    'calendar' => $user?->canShowMenu('calendar') ?? false,
                    'finance' => $user?->canShowMenu('finance') ?? false,
                    'trader' => $user?->canShowMenu('finance.trader') ?? false,
                ],
                'canManageUsers' => (bool) $user?->is_primary_user,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
