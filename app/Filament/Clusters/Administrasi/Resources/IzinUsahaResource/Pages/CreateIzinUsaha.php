<?php

namespace App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource;

class CreateIzinUsaha extends CreateRecord
{
    protected static string $resource = IzinUsahaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $currentUser = Auth::user();

        $notif = Notification::make()
            ->title(__('izin_usaha.izin_usaha'))
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body($currentUser->name . ' ' . __('izin_usaha.berhasil_dibuat'));

        User::query()->cursor()->each(function ($user) use ($notif) {
            $notif->sendToDatabase($user);
        });

        return $notif;
    }
}
