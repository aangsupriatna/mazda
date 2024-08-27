<?php

namespace App\Providers;

use App\Models\Proyek;
use App\Observers\ProyekObserver;
use Filament\Livewire\Notifications;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Config;
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
        // observer proyek
        Proyek::observe(ProyekObserver::class);

        // language switch
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['id', 'en'])
                ->flags([
                    'id' => asset('images/flags/id.svg'),
                    'en' => asset('images/flags/us.svg'),
                ]);
        });

        // notifications alignment
        Notifications::alignment(Alignment::Center);

        // set user timezone
        $this->setUserTimezone();
    }

    // set user timezone
    protected function setUserTimezone()
    {
        if (Auth::check()) {
            $userTampilan = Auth::user()->tampilan;
            if ($userTampilan && $userTampilan->timezone) {
                Config::set('app.timezone', $userTampilan->timezone);
                date_default_timezone_set($userTampilan->timezone);
            }
        }
    }
}
