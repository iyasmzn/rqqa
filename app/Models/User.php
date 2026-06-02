<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'google_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['super_admin', 'panel_user']);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function authorRequest(): HasOne
    {
        return $this->hasOne(AuthorRequest::class);
    }

    public function isAuthor(): bool
    {
        return $this->hasRole('author');
    }

    public function hasPendingAuthorRequest(): bool
    {
        return $this->authorRequest()->where('status', 'pending')->exists();
    }
}
