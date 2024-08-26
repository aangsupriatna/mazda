<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Form;
use App\Models\Perusahaan;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;

class RegisterPerusahaan extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register perusahaan';
    }

    public function form(Form $form): Form
    {
        // dd(Auth::user()->roles->pluck('name'));

        $existingTenants = Perusahaan::pluck('name', 'id');

        if ($existingTenants->isNotEmpty() && !Auth::user()->hasRole('super_admin')) {
            return $form
                ->schema([
                    Select::make('perusahaan_id')
                        ->label('Pilih Perusahaan')
                        ->options($existingTenants)
                        ->required(),
                ]);
        } else {
            return $form
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Nama Perusahaan Baru'),
                ]);
        }
    }

    protected function handleRegistration(array $data): Perusahaan
    {
        $user = Auth::user();

        if (isset($data['perusahaan_id'])) {
            $perusahaan = Perusahaan::findOrFail($data['perusahaan_id']);
            $perusahaan->users()->syncWithoutDetaching($user->id);
            return $perusahaan;
        } else {
            $data['slug'] = Str::slug($data['name']);
            $perusahaan = Perusahaan::create($data);
            $perusahaan->users()->attach($user);
            return $perusahaan;
        }
    }
}
