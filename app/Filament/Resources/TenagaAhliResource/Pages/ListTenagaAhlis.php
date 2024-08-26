<?php

namespace App\Filament\Resources\TenagaAhliResource\Pages;

use App\Filament\Resources\TenagaAhliResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenagaAhlis extends ListRecords
{
    protected static string $resource = TenagaAhliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
