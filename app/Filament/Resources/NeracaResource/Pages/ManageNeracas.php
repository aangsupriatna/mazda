<?php

namespace App\Filament\Resources\NeracaResource\Pages;

use App\Filament\Resources\NeracaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNeracas extends ManageRecords
{
    protected static string $resource = NeracaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
