<?php

namespace App\Models;

use App\Enums\Setting\Font;
use App\Enums\Setting\PrimaryColor;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tampilan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'perusahaan_id',
        'user_id',
        'primary_color',
        'font',
        'table_sort_direction',
        'records_per_page',
    ];

    protected $casts = [
        'primary_color' => PrimaryColor::class,
        'font' => Font::class,
        'table_sort_direction' => TableSortDirection::class,
        'records_per_page' => RecordsPerPage::class,
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Listen for the creating event and set the slug before saving
    //     static::creating(function (Tampilan $tampilan) {
    //         $tampilan->perusahaan_id = Filament::getTenant()->id;
    //     });
    // }
}
