<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Perusahaan;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Awcodes\Curator\CuratorPlugin;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\SetCurrentPerusahaan;
use App\Filament\Pages\Tenancy\EditPerusahaan;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Tenancy\RegisterPerusahaan;
use App\Http\Middleware\FilamentDynamicAppearance;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => 'rgb(103, 76, 196)',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->tenant(Perusahaan::class, ownershipRelationship: 'perusahaan')
            ->tenantRegistration(RegisterPerusahaan::class)
            ->tenantProfile(EditPerusahaan::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('3s')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('320px')
            ->maxContentWidth('full')
            ->plugins([
                FilamentShieldPlugin::make(),
                CuratorPlugin::make()
                    ->label('Media')
                    ->pluralLabel('Media')
                    ->navigationIcon('heroicon-o-photo')
                    ->navigationGroup('Content')
                    ->navigationSort(3)
                    ->defaultListView('list')
                    ->registerNavigation(true)
                    ->navigationCountBadge(),
            ])
            ->profile(EditProfile::class)
            ->theme('filament-panels::theme')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->tenantMiddleware([
                SetCurrentPerusahaan::class,
                FilamentDynamicAppearance::class,
            ], isPersistent: true);
    }
}
