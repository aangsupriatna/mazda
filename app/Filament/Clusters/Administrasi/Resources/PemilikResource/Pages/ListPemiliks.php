<?php

namespace App\Filament\Clusters\Administrasi\Resources\PemilikResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\PemilikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemiliks extends ListRecords
{
    protected static string $resource = PemilikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
