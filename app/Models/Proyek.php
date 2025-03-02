<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Proyek extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'perusahaan_id',
        'klien_id',
        'nama',
        'lokasi',
        'nomor_kontrak',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_serah_terima',
        'nilai_kontrak',
        'kategori_proyek',
        'persentase_pekerjaan',
        'ruang_lingkup_pekerjaan',
        'deskripsi',
        'konsorsium',
        'lampiran',
        'klasifikasi',
    ];

    protected $casts = [
        'lampiran' => 'array',
        'klasifikasi' => 'array',
        'konsorsium' => 'boolean',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function klien(): BelongsTo
    {
        return $this->belongsTo(Klien::class);
    }
}
