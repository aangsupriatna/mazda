<?php

namespace App\Providers;

use Filament\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(CustomServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['id','en'])
                ->flags([
                    'id' => asset('images/flags/id.svg'),
                    'en' => asset('images/flags/us.svg'),
                ]);
        });
        Notifications::alignment(Alignment::Center);
    }
}
