<?php

namespace App\Filament\Resources\TenagaAhliResource\Pages;

use App\Filament\Resources\TenagaAhliResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenagaAhli extends EditRecord
{
    protected static string $resource = TenagaAhliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
