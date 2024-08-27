<?php

namespace App\Observers;

use App\Models\Proyek;
use Illuminate\Support\Facades\Cache;

class ProyekObserver
{
    public function saved(Proyek $proyek)
    {
        $this->updateKlasifikasiCache();
    }

    public function deleted(Proyek $proyek)
    {
        $this->updateKlasifikasiCache();
    }

    private function updateKlasifikasiCache()
    {
        $klasifikasi = Proyek::pluck('klasifikasi')
            ->flatMap(function ($item) {
                return is_array($item) ? $item : explode(',', $item);
            })
            ->map(fn($item) => trim($item))
            ->filter()
            ->unique()
            ->implode(',');
        Cache::forever('proyek_klasifikasi', $klasifikasi);
    }
}
