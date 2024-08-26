<?php

namespace App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource\Pages;

use App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAktePerubahan extends EditRecord
{
    protected static string $resource = AktePerubahanResource::class;

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
