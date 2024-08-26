<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengurus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cascade',
        'nama',
        'jenis_kepengurusan',
        'orang_asli_papua',
        'kewarganegaraan',
        'no_ktp',
        'npwp',
        'no_bpjs_kesehatan',
        'no_bpjs_ketenagakerjaan',
        'alamat',
        'provinsi',
        'kabupaten_kota',
        'jabatan',
        'menjabat_sampai',
        'status',
        'masih_bekerja',
        'file_ktp_id',
        'file_npwp_id',
    ];
    protected $casts = [
        'masih_bekerja' => 'boolean',
        'orang_asli_papua' => 'boolean',
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
