<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PphResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PphResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePph extends CreateRecord
{
    protected static string $resource = PphResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
