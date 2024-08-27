<?php

namespace App\Filament\Resources\TenagaAhliResource\Pages;

use App\Filament\Resources\TenagaAhliResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenagaAhli extends ViewRecord
{
    protected static string $resource = TenagaAhliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\CreateAction::make()
                ->label(__('tenaga_ahli.kembali'))
                ->color('gray')
                ->url(TenagaAhliResource::getUrl('index')),
        ];
    }
}
