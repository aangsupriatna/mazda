<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AktePendirian extends Model
{
    use HasFactory;

    protected $casts = [
        'file_akte' => 'array',
        'file_pengesahan' => 'array',
    ];

    protected $fillable = [
        'perusahaan_id',
        'nomor',
        'tanggal',
        'nama_notaris',
        'nomor_pengesahan',
        'tanggal_pengesahan',
        'file_akte_id',
        'file_pengesahan_id',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
