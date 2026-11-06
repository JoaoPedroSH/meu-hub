<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_primary_user', 'menu_permissions', 'hidden_menu_items'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const MENU_KEYS = ['dashboard', 'resumes', 'notes', 'calendar', 'finance', 'finance.trader'];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_primary_user' => 'boolean',
            'menu_permissions' => 'array',
            'hidden_menu_items' => 'array',
            'password' => 'hashed',
        ];
    }

    public function canAccessMenu(string $menu): bool
    {
        if (! in_array($menu, self::MENU_KEYS, true)) {
            return false;
        }

        return $this->is_primary_user
            || in_array($menu, $this->menu_permissions ?? self::MENU_KEYS, true);
    }

    public function canShowMenu(string $menu): bool
    {
        return $this->canAccessMenu($menu)
            && ! in_array($menu, $this->hidden_menu_items ?? [], true);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class)->latest('updated_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function googleCalendarConnection(): HasOne
    {
        return $this->hasOne(GoogleCalendarConnection::class);
    }

    public function tradingAccounts(): HasMany
    {
        return $this->hasMany(TradingAccount::class);
    }
}
