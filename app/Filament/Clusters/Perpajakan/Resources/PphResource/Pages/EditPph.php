<?php

namespace App\Filament\Clusters\Perpajakan\Resources\PphResource\Pages;

use App\Filament\Clusters\Perpajakan\Resources\PphResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPph extends EditRecord
{
    protected static string $resource = PphResource::class;

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
