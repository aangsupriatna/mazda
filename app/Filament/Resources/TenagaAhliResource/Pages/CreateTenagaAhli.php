<?php

namespace App\Filament\Resources\TenagaAhliResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TenagaAhliResource;

class CreateTenagaAhli extends CreateRecord
{
    protected static string $resource = TenagaAhliResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $currentUser = Auth::user();

        $notif = Notification::make()
            ->title(__('tenaga_ahli.tenaga_ahli'))
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body($currentUser->name . ' ' . __('tenaga_ahli.berhasil_dibuat'));

        User::query()->cursor()->each(function ($user) use ($notif) {
            $notif->sendToDatabase($user);
        });

        return $notif;
    }
}
