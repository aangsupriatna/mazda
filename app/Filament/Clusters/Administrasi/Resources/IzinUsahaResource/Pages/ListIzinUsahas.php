<?php

namespace App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIzinUsahas extends ListRecords
{
    protected static string $resource = IzinUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
