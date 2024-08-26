<?php

namespace App\Models;

use Awcodes\Curator\Models\Media as MediaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends MediaModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function aktePendirian(): BelongsTo
    {
        return $this->belongsTo(AktePendirian::class);
    }

    public function aktePerubahan(): BelongsTo
    {
        return $this->belongsTo(AktePerubahan::class);
    }

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Pemilik::class);
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }

    public function npwp(): BelongsTo
    {
        return $this->belongsTo(Npwp::class);
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }


}
