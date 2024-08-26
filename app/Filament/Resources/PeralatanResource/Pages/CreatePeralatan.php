<?php

namespace App\Filament\Resources\PeralatanResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PeralatanResource;

class CreatePeralatan extends CreateRecord
{
    protected static string $resource = PeralatanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $currentUser = Auth::user();

        $notif = Notification::make()
            ->title(__('peralatan.peralatan'))
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body($currentUser->name . ' ' . __('peralatan.berhasil_dibuat'));

        User::query()->cursor()->each(function ($user) use ($notif) {
            $notif->sendToDatabase($user);
        });

        return $notif;
    }
}
