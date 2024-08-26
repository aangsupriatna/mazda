<?php

namespace App\Filament\Clusters\Administrasi\Resources\PemilikResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\PemilikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemilik extends EditRecord
{
    protected static string $resource = PemilikResource::class;

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
