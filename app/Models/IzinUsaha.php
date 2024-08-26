<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IzinUsaha extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perusahaan_id',
        'jenis_izin',
        'nomor_izin',
        'pemberi_izin',
        'kualifikasi_izin',
        'tanggal_izin',
        'tanggal_kadaluarsa',
        'file_izin_id',
        'kualifikasi_usaha',
    ];

    protected $casts = [
        'kualifikasi_usaha' => 'array',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'file_izin_id');
    }
}
