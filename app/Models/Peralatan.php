<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peralatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perusahaan_id',
        'nama',
        'jumlah',
        'kapasitas',
        'satuan',
        'merk',
        'kondisi',
        'tahun_pembuatan',
        'kepemilikan',
        'lokasi',
        'keterangan',
        'media_id',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(PeralatanStatus::class);
    }
}
