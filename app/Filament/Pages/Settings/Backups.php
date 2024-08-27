<?php

namespace App\Filament\Pages\Settings;
use Illuminate\Contracts\Support\Htmlable;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = null;

    public function getHeading(): string | Htmlable
    {
        return __('navigation.pencadangan_aplikasi');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.pengaturan');
    }
}
