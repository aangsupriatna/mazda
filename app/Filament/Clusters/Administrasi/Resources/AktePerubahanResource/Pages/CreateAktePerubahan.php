<?php

namespace App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource;

class CreateAktePerubahan extends CreateRecord
{
    protected static string $resource = AktePerubahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $currentUser = Auth::user();

        $notif = Notification::make()
            ->title(__('akte_perubahan.akte_perubahan'))
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body($currentUser->name . ' ' . __('akte_perubahan.berhasil_dibuat'));

        User::query()->cursor()->each(function ($user) use ($notif) {
            $notif->sendToDatabase($user);
        });

        return $notif;
    }
}
