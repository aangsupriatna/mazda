<?php

namespace App\Listeners;

use App\Events\TampilanEvent;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TampilanListener
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TampilanEvent $event): void
    {
        Notification::make()
            ->title('Tampilan telah disimpan')
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body('Tampilan ' . $event->tampilan->nama . ' telah berhasil disimpan.')
            ->sendToDatabase(auth()->user());
    }
}
