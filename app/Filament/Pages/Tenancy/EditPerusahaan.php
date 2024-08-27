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
use Awcodes\Curator\PathGenerators\DatePathGenerator;

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
                                            ->label(__('perusahaan.pilih_logo'))
                                            ->constrained(true)
                                            ->size('xs'),
                                    ])->columnSpan(4),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('perusahaan.nama'))
                                            ->required(),
                                    ])->columnSpan(8),
                            ])->columns(12),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label(__('perusahaan.email'))
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('no_telepon')
                                    ->label(__('perusahaan.nomor_telepon'))
                                    ->tel()
                                    ->required(),
                            ])->columns(),
                        Forms\Components\Textarea::make('alamat')
                            ->label(__('perusahaan.alamat'))
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
