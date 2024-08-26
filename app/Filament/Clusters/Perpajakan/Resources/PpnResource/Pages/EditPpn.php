<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PpnResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PpnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpn extends EditRecord
{
    protected static string $resource = PpnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
