<?php

namespace App\Filament\Clusters\Perpajakan\Resources\SptTahunanResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\SptTahunanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSptTahunans extends ManageRecords
{
    protected static string $resource = SptTahunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
