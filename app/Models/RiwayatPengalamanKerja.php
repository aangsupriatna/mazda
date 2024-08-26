<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatPengalamanKerja extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perusahaan_id',
        'tenaga_ahli_id',
        'tahun',
        'nama_proyek',
        'lokasi_proyek',
        'pengguna_jasa',
        'nama_perusahaan',
        'uraian_tugas',
        'posisi_penugasan',
        'status_kepegawaian',
        'surat_referensi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->perusahaan_id = Filament::getTenant()->id;

        });
    }

    public function tenagaAhli(): BelongsTo
    {
        return $this->belongsTo(TenagaAhli::class);
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
