<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Perusahaan;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Settings\Backups;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\SetCurrentPerusahaan;
use App\Filament\Pages\Tenancy\EditPerusahaan;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Tenancy\RegisterPerusahaan;
use App\Http\Middleware\FilamentDynamicAppearance;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

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
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Awcodes\Curator\CuratorPlugin::make()
                    ->label('Media')
                    ->pluralLabel('Media')
                    ->navigationIcon('heroicon-o-photo')
                    ->navigationGroup('Content')
                    ->navigationSort(3)
                    ->registerNavigation(true)
                    ->navigationCountBadge(),
                \ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin::make()
                    ->usingPage(Backups::class),
                \Awcodes\FilamentGravatar\GravatarPlugin::make()
                    ->size(200)
                    ->rating('pg'),
                \Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setIcon('heroicon-o-user')
                    ->setNavigationGroup('My Profile')
                    ->setSort(10)
                    ->shouldRegisterNavigation(true)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),
                GlobalSearchModalPlugin::make(),
            ])
            ->theme('filament-panels::theme')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->tenantMiddleware([
                SetCurrentPerusahaan::class,
                FilamentDynamicAppearance::class,
            ], isPersistent: true)
            ->defaultAvatarProvider(\Awcodes\FilamentGravatar\GravatarProvider::class)
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('perusahaan.profile_perusahaan'))
                    ->url(function (): ?string {
                        $tenant = Filament::getTenant();
                        return $tenant ? EditPerusahaan::getUrl(['tenant' => $tenant]) : null;
                    })
                    ->visible(fn(): bool => Filament::getTenant() !== null)
                    ->icon('heroicon-o-building-office'),
                MenuItem::make()
                    ->label(__('user.profile'))
                    ->url(function (): ?string {
                        $tenant = Filament::getTenant();
                        return $tenant ? EditProfilePage::getUrl(['tenant' => $tenant]) : null;
                    })
                    ->visible(fn(): bool => Filament::getTenant() !== null)
                    ->icon('heroicon-o-user'),
            ])
            ->spa()
            ->globalSearchKeyBindings(['mod+k']);
    }
}
