<?php

namespace App\Filament\Clusters\Administrasi\Resources\PengurusResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\PengurusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPengurus extends ViewRecord
{
    protected static string $resource = PengurusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\CreateAction::make()
                ->label(__('pengurus.kembali'))
                ->color('gray')
                ->url(PengurusResource::getUrl('index')),
        ];
    }
}
