<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemilik extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cascade',
        'nama',
        'jenis_kepemilikan',
        'kewarganegaraan',
        'nik_paspor',
        'npwp',
        'saham',
        'tipe_saham',
        'alamat',
        'provinsi',
        'kabupaten_kota',
        'file_ktp_id',
        'file_npwp_id',
    ];

    /**
     * Get the perusahaan that owns the Pemilik
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
