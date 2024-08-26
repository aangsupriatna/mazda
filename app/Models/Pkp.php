<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pkp extends Model
{
    use HasFactory;

    protected $fillable = ['perusahaan_id', 'nomor', 'tanggal', 'file_lampiran_id'];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'file_lampiran_id');
    }
}
