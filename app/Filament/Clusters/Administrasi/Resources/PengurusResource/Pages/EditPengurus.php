<?php

namespace App\Filament\Clusters\Administrasi\Resources\PengurusResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\PengurusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengurus extends EditRecord
{
    protected static string $resource = PengurusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
