<?php

namespace App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\Pages;

use Filament\Actions;
use App\Imports\IzinUsahaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource;

class ListIzinUsahas extends ListRecords
{
    protected static string $resource = IzinUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('import')
                ->label('Import Izin Usaha')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->disk('public')
                        ->directory('temp')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Proses import Excel
                    $file = Storage::path('public' . '/' . $data['file']);
                    Excel::import(new IzinUsahaImport, $file);
                    unlink($file);

                    // Tampilkan notifikasi setelah import
                    \Filament\Notifications\Notification::make()
                        ->title('Import Berhasil')
                        ->success()
                        ->send();
                }),
        ];
    }
}
