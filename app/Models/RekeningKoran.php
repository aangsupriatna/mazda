<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekeningKoran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perusahaan_id',
        'nomor_rekening',
        'nama_rekening',
        'bank',
        'bulan',
        'tahun',
        'mata_uang',
        'jumlah',
        'media_id',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
