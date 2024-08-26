<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pph extends Model
{
    use HasFactory;

    protected $fillable = ['perusahaan_id', 'tahun', 'bulan', 'nomor', 'tanggal', 'file_pph_id'];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function filePph(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'file_pph_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event and set the slug before saving
        static::creating(function (Pph $pph) {
            $pph->perusahaan_id = Filament::getTenant()->id;
        });
    }
}
