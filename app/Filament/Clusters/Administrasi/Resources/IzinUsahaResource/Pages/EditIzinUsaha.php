<?php

namespace App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIzinUsaha extends EditRecord
{
    protected static string $resource = IzinUsahaResource::class;

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
