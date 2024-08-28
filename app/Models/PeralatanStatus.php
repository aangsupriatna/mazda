<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeralatanStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'peralatan_id',
        'status',
        'peminjam',
        'keterangan',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
    ];

    public function peralatan(): BelongsTo
    {
        return $this->belongsTo(Peralatan::class);
    }
}
