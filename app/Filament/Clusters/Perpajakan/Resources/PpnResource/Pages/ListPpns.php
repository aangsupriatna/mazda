<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PpnResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PpnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPpns extends ListRecords
{
    protected static string $resource = PpnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
