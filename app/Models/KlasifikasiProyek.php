<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KlasifikasiProyek extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perusahaan_id',
        'kode',
        'judul',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->perusahaan_id = Filament::getTenant()->id;

        });
    }
}
