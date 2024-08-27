<?php

namespace App\Filament\Resources\ProyekResource\Pages;

use App\Filament\Resources\ProyekResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProyek extends ViewRecord
{
    protected static string $resource = ProyekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\CreateAction::make()
                ->label(__('proyek.kembali'))
                ->color('gray')
                ->url(ProyekResource::getUrl('index')),
        ];
    }
}
