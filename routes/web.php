<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LocalUserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\TradingController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (! User::query()->exists()) {
        return redirect()->route('setup');
    }

    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->canShowMenu('dashboard')) {
        return redirect()->route('dashboard');
    }

    if ($user->canShowMenu('resumes')) {
        return redirect()->route('resumes.index');
    }

    if ($user->canShowMenu('notes')) {
        return redirect()->route('notes.index');
    }

    if ($user->canShowMenu('calendar')) {
        return redirect()->route('calendar.index');
    }

    if ($user->canShowMenu('finance.trader')) {
        return redirect()->route('finance.trader.index');
    }

    if ($user->canShowMenu('finance')) {
        return redirect()->route('finance.index');
    }

    return redirect()->route('profile.edit');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/configuracao-inicial', [LocalUserController::class, 'setup'])->name('setup');
    Route::post('/configuracao-inicial', [LocalUserController::class, 'createPrimary'])
        ->middleware('throttle:5,1')
        ->name('setup.store');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $canShowResumes = $user->canShowMenu('resumes');
    $latestResume = $canShowResumes ? $user->resumes()->first() : null;
    $canShowNotes = $user->canShowMenu('notes');
    $canShowCalendar = $user->canShowMenu('calendar');

    return Inertia::render('Dashboard', [
        'canShowResumes' => $canShowResumes,
        'canShowNotes' => $canShowNotes,
        'canShowCalendar' => $canShowCalendar,
        'resumesCount' => $canShowResumes ? $user->resumes()->count() : 0,
        'notesCount' => $canShowNotes ? $user->notes()->count() : 0,
        'upcomingEventsCount' => $canShowCalendar ? $user->calendarEvents()->where('starts_at', '>=', now())->count() : 0,
        'latestResume' => $latestResume ? [
            'id' => $latestResume->id,
            'title' => $latestResume->title,
            'updated_at_formatted' => $latestResume->updated_at->diffForHumans(),
            'updated_at_full' => $latestResume->updated_at->format('d/m/Y H:i'),
        ] : null,
    ]);
})->middleware(['auth', 'menu:dashboard'])->name('dashboard');

Route::middleware(['auth', 'menu:resumes'])->group(function () {
    Route::get('/profissional/curriculos', [ResumeController::class, 'index'])->name('resumes.index');
    Route::post('/profissional/curriculos', [ResumeController::class, 'store'])->name('resumes.store');
    Route::get('/profissional/curriculos/{resume}', [ResumeController::class, 'show'])->name('resumes.show');
    Route::get('/profissional/curriculos/{resume}/edit', [ResumeController::class, 'edit'])->name('resumes.edit');
    Route::put('/profissional/curriculos/{resume}', [ResumeController::class, 'update'])->name('resumes.update');
    Route::delete('/profissional/curriculos/{resume}', [ResumeController::class, 'destroy'])->name('resumes.destroy');
    Route::post('/profissional/curriculos/{resume}/duplicate', [ResumeController::class, 'duplicate'])->name('resumes.duplicate');
    Route::get('/profissional/curriculos/{resume}/pdf', [ResumeController::class, 'pdf'])->name('resumes.pdf');
});

Route::middleware(['auth', 'menu:finance'])->get('/financas', fn () => Inertia::render('Finances/Index', [
    'canShowTrader' => auth()->user()->canShowMenu('finance.trader'),
]))
    ->name('finance.index');

Route::middleware(['auth', 'menu:finance.trader'])->prefix('/financas/trader')->name('finance.trader.')->group(function () {
    Route::get('/', [TradingController::class, 'index'])->name('index');
    Route::post('/carteiras', [TradingController::class, 'storeAccount'])->name('accounts.store');
    Route::post('/carteiras/{account}/ordens-simuladas', [TradingController::class, 'storeOrder'])->name('orders.store');
});

Route::middleware(['auth', 'menu:notes'])->group(function () {
    Route::get('/anotacoes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/anotacoes', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/anotacoes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/anotacoes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::delete('/anotacoes-tags/{tag}', [NoteController::class, 'destroyTag'])->name('notes.tags.destroy');
});

Route::middleware(['auth', 'menu:calendar'])->group(function () {
    Route::get('/agenda', [CalendarEventController::class, 'index'])->name('calendar.index');
    Route::post('/agenda', [CalendarEventController::class, 'store'])->name('calendar.store');
    Route::put('/agenda/{calendarEvent}', [CalendarEventController::class, 'update'])->name('calendar.update');
    Route::delete('/agenda/{calendarEvent}', [CalendarEventController::class, 'destroy'])->name('calendar.destroy');

    Route::get('/configuracoes/integracoes/google', [IntegrationController::class, 'index'])
        ->name('settings.integrations.index');
    Route::get('/configuracoes/integracoes/google/conectar', [IntegrationController::class, 'connect'])
        ->name('settings.integrations.connect');
    Route::get('/configuracoes/integracoes/google/callback', [IntegrationController::class, 'callback'])
        ->name('settings.integrations.callback');
    Route::post('/configuracoes/integracoes/google/sync', [IntegrationController::class, 'sync'])
        ->name('settings.integrations.sync');
    Route::delete('/configuracoes/integracoes/google', [IntegrationController::class, 'disconnect'])
        ->name('settings.integrations.disconnect');
});

Route::middleware('auth')->group(function () {
    Route::get('/configuracoes', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/configuracoes', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/configuracoes/menu', [ProfileController::class, 'editMenu'])->name('settings.menu.edit');
    Route::patch('/configuracoes/menu', [ProfileController::class, 'updateMenu'])->name('settings.menu.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware('primary')->prefix('configuracoes/usuarios')->name('settings.users.')->group(function () {
        Route::get('/', [LocalUserController::class, 'index'])->name('index');
        Route::post('/', [LocalUserController::class, 'store'])->name('store');
        Route::patch('/{user}', [LocalUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [LocalUserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
