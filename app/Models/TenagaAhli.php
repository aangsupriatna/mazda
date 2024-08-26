<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenagaAhli extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'pendidikan' => 'array',
        'riwayat_pengalaman_kerja' => 'array',
        'sertifikasi_keahlian' => 'array',
    ];

    protected $fillable = [
        'perusahaan_id',
        'nama',
        'jenis_tenaga_ahli',
        'kewarganegaraan',
        'nik_paspor',
        'npwp',
        'no_bpjs_kesehatan',
        'no_bpjs_ketenagakerjaan',
        'negara_tempat_lahir',
        'kabupaten_kota_tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nomor_telepon_hp',
        'email',
        'website',
        'alamat',
        'provinsi',
        'kabupaten_kota',
        'status_kepegawaian',
        'lama_pengalaman_kerja',
        'profesi_keahlian',
        'pendidikan',
        'sertifikasi_keahlian',
    ];

    public function riwayat_pengalaman_kerja(): HasMany
    {
        return $this->hasMany(RiwayatPengalamanKerja::class);
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
