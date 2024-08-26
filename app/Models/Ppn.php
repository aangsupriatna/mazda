<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ppn extends Model
{
    use HasFactory;

    protected $fillable = ['perusahaan_id', 'tahun', 'bulan', 'nomor', 'tanggal', 'file_ppn_id'];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function filePpn(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'file_ppn_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event and set the slug before saving
        static::creating(function (Ppn $ppn) {
            $ppn->perusahaan_id = Filament::getTenant()->id;
        });
    }
}
