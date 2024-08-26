<?php

namespace App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAktePerubahans extends ListRecords
{
    protected static string $resource = AktePerubahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
