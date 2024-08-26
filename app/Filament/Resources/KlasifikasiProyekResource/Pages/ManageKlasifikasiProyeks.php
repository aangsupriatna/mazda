<?php

namespace App\Filament\Resources\KlasifikasiProyekResource\Pages;

use App\Filament\Resources\KlasifikasiProyekResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKlasifikasiProyeks extends ManageRecords
{
    protected static string $resource = KlasifikasiProyekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
