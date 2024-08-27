<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Awcodes\Curator\Models\Media;

class Perusahaan extends Model implements HasAvatar
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'alamat',
        'no_telepon',
        'logo_id',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        $media = $this->logo;
        return $media ? Storage::url($media->path) : null;
    }

    public function getFilamentAvatarColor(): ?string
    {
        return 'primary';
    }

    public function getFilamentAvatarIcon(): ?string
    {
        return 'heroicon-o-building-office';
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function aktePendirians(): HasOne
    {
        return $this->hasOne(AktePendirian::class);
    }

    public function aktePerubahans(): HasMany
    {
        return $this->hasMany(AktePerubahan::class);
    }

    public function pemiliks(): HasMany
    {
        return $this->hasMany(Pemilik::class);
    }

    public function penguruses(): HasMany
    {
        return $this->hasMany(Pengurus::class);
    }

    public function izinUsahas(): HasMany
    {
        return $this->hasMany(IzinUsaha::class);
    }

    public function npwps(): HasOne
    {
        return $this->hasOne(Npwp::class);
    }

    public function sptTahunans(): HasMany
    {
        return $this->hasMany(SptTahunan::class);
    }

    public function kliens(): HasMany
    {
        return $this->hasMany(Klien::class);
    }

    public function proyeks(): HasMany
    {
        return $this->hasMany(Proyek::class);
    }

    public function tenagaAhlis(): HasMany
    {
        return $this->hasMany(TenagaAhli::class);
    }

    public function peralatans(): HasMany
    {
        return $this->hasMany(Peralatan::class);
    }

    public function rekeningKorans(): HasMany
    {
        return $this->hasMany(RekeningKoran::class);
    }

    public function neracas(): HasMany
    {
        return $this->hasMany(Neraca::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function ppns(): HasMany
    {
        return $this->hasMany(Ppn::class);
    }

    public function pphs(): HasMany
    {
        return $this->hasMany(Pph::class);
    }
}
