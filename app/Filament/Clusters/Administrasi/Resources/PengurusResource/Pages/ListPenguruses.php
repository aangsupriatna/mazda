<?php

namespace App\Filament\Clusters\Administrasi\Resources\PengurusResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\PengurusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenguruses extends ListRecords
{
    protected static string $resource = PengurusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
