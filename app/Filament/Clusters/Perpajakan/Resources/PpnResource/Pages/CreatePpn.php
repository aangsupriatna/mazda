<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PpnResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PpnResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePpn extends CreateRecord
{
    protected static string $resource = PpnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
