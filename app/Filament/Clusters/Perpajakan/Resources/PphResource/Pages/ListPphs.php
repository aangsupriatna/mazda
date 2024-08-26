<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PphResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PphResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPphs extends ListRecords
{
    protected static string $resource = PphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
