<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms;
use Filament\Forms\Form;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\EditTenantProfile;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class EditPerusahaan extends EditTenantProfile
{
    protected static string $view = 'filament.pages.tenancy.edit-perusahaan';

    public ?array $data = [];

    #[Locked]
    public $tenant = null;

    public static function getLabel(): string
    {
        return 'Profile perusahaan';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        CuratorPicker::make('logo_id')
                                            ->label('Choose Logo')
                                            ->size('xs'),
                                    ])->columnSpan(4),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])->columnSpan(8),
                            ])->columns(12),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('no_telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required(),
                            ])->columns(),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required(),
                    ]),
            ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $this->dispatch('perusahaanUpdated');
        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        $user = Auth::user();

        return Notification::make()
            ->title('Update Berhasil')
            ->icon('heroicon-o-information-circle')
            ->success()
            ->body('Profile perusahaan ' . $this->tenant->name . ' berhasil diubah oleh ' . $user->name)
            ->sendToDatabase($user);
    }
}
