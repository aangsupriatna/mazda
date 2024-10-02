<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements HasTenants, HasAvatar
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

    public function perusahaan(): BelongsToMany
    {
        return $this->belongsToMany(Perusahaan::class);
    }

    public function tampilan(): HasOne
    {
        return $this->hasOne(Tampilan::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->perusahaan;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->perusahaan()->whereKey($tenant)->exists();
    }

    public function currentPerusahaan(): ?Perusahaan
    {
        return $this->perusahaan()->wherePivot('is_current', true)->first();
    }

    public function switchPerusahaan(Perusahaan $perusahaan): void
    {
        $this->perusahaan()->updateExistingPivot($this->currentPerusahaan()?->id, ['is_current' => false]);
        $this->perusahaan()->updateExistingPivot($perusahaan->id, ['is_current' => true]);
        $this->refresh();
    }

    /**
     * Memeriksa apakah pengguna adalah super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Memeriksa apakah pengguna adalah admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
