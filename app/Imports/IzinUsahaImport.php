<?php

namespace App\Imports;

use App\Models\IzinUsaha;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class IzinUsahaImport implements ToModel, WithStartRow
{
    // Fungsi untuk menentukan mulai membaca dari baris ke berapa
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua
    }

    /**
     * Setiap baris dari file Excel akan dimapping ke method ini
     */
    public function model(array $row)
    {
        return new IzinUsaha([
            'jenis_izin' => $row[0],
            'nomor_izin' => $row[1],
            'pemberi_izin' => $row[2],
            'kualifikasi_izin' => $row[3],
            // 'tanggal_izin' => \Carbon\Carbon::parse($row[4]),
            // 'tanggal_kadaluarsa' => \Carbon\Carbon::parse($row[5]),
            'tanggal_izin' => '2015-10-28 19:18:44',
            'tanggal_kadaluarsa' => '2015-10-28 19:18:44',
        ]);
    }
}
