<?php

namespace App\Filament\Clusters\Administrasi\Resources\PemilikResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Administrasi\Resources\PemilikResource;

class CreatePemilik extends CreateRecord
{
    protected static string $resource = PemilikResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $currentUser = Auth::user();

        $notif = Notification::make()
            ->title(__('pemilik.pemilik'))
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body($currentUser->name . ' ' . __('pemilik.berhasil_dibuat'));

        User::query()->cursor()->each(function ($user) use ($notif) {
            $notif->sendToDatabase($user);
        });

        return $notif;
    }
}
