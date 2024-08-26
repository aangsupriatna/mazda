<?php

namespace App\Filament\Resources\ProyekResource\Widgets;

use App\Models\Proyek;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProyekStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProyek = Proyek::count();
        $proyekBerjalan = Proyek::where('tanggal_selesai', '>=', Carbon::today())->count();
        $proyekSelesai = Proyek::where('tanggal_selesai', '<', Carbon::today())->count();

        $chartData = $this->getChartData();

        return [
            Stat::make('Total Proyek', $totalProyek)
                ->description('Jumlah seluruh proyek')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart($chartData['total']),
            Stat::make('Proyek Berjalan', $proyekBerjalan)
                ->description('Proyek yang masih aktif')
                ->descriptionIcon('heroicon-m-play')
                ->color('success')
                ->chart($chartData['berjalan']),
            Stat::make('Proyek Selesai', $proyekSelesai)
                ->description('Proyek yang sudah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('danger')
                ->chart($chartData['selesai']),
        ];
    }

    private function getChartData(): array
    {
        $data = collect(range(1, 12))->map(function ($month) {
            $date = Carbon::create(null, $month, 1);
            $total = Proyek::whereMonth('tanggal_mulai', $month)->count();
            $berjalan = Proyek::whereMonth('tanggal_mulai', $month)
                ->where('tanggal_selesai', '>=', $date)
                ->count();
            $selesai = Proyek::whereMonth('tanggal_mulai', $month)
                ->where('tanggal_selesai', '<', $date)
                ->count();

            return [
                'total' => $total,
                'berjalan' => $berjalan,
                'selesai' => $selesai,
            ];
        });

        return [
            'total' => $data->pluck('total')->toArray(),
            'berjalan' => $data->pluck('berjalan')->toArray(),
            'selesai' => $data->pluck('selesai')->toArray(),
        ];
    }
}
