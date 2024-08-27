<?php

namespace App\Providers;

use App\Events\TampilanEvent;
use Filament\Facades\Filament;
use App\Listeners\TampilanListener;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;

class CustomServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Filament::serving(function () {
        //     FilamentView::registerRenderHook(
        //         PanelsRenderHook::SIDEBAR_NAV_END,
        //         fn (): View => view('filament.components.sidebar-banner'),
        //     );
        // });

        // Event::listen(TampilanEvent::class, TampilanListener::class);
    }
}
