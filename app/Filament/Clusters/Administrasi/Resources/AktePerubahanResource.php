<?php

namespace App\Filament\Clusters\Administrasi\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AktePerubahan;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Filament\Clusters\Administrasi;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Component;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Administrasi\Resources\AktePerubahanResource\Pages;

class AktePerubahanResource extends Resource
{
    protected static ?string $model = AktePerubahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Administrasi::class;

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('navigation.akte_perubahan');
    }

    public static function getNavigationLabel(): string
    {
        return __('akte_perubahan.akte_perubahan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        static::getNamaNotarisSection(),
                        static::getAktePerubahanSection(),
                        static::getPengesahanAktePerubahanSection(),
                    ])->columnSpan(3),
                Forms\Components\Group::make()
                    ->schema([
                        static::getLampiranAkteSection(),
                    ]),
            ])->columns(4);
    }

    public static function getNamaNotarisSection(): Component
    {
        return Forms\Components\Section::make(__('akte_perubahan.nama_notaris'))
            ->description(__('akte_perubahan.deskripsi_nama_notaris'))
            ->schema([
                Forms\Components\TextInput::make('nama_notaris')
                    ->label(__('akte_perubahan.nama_notaris'))
                    ->maxLength(255)
                    ->required(),
            ])
            ->collapsible()
            ->columnSpanFull();
    }

    public static function getAktePerubahanSection(): Component
    {
        return Forms\Components\Section::make(__('akte_perubahan.akte_perubahan'))
            ->description(__('akte_perubahan.deskripsi_akte'))
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('nomor')
                            ->label(__('akte_perubahan.nomor'))
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label(__('akte_perubahan.tanggal'))
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->required(),
                    ])->columns(),
            ])
            ->collapsible()
            ->columnSpanFull();
    }

    public static function getPengesahanAktePerubahanSection(): Component
    {
        return Forms\Components\Section::make(__('akte_perubahan.pengesahan_akte_perubahan'))
            ->description(__('akte_perubahan.deskripsi_pengesahan'))
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('nomor_pengesahan')
                            ->label(__('akte_perubahan.nomor_pengesahan'))
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_pengesahan')
                            ->label(__('akte_perubahan.tanggal_pengesahan'))
                            ->suffixIcon('heroicon-o-calendar')
                            ->native(false)
                            ->required(),
                    ])->columns(),
            ])
            ->collapsible()
            ->columnSpanFull();
    }

    public static function getLampiranAkteSection(): Component
    {
        return Forms\Components\Section::make(__('akte_perubahan.lampiran_akte'))
            ->description(__('akte_perubahan.deskripsi_lampiran'))
            ->schema([
                CuratorPicker::make('file_akte_id')
                    ->label(__('akte_perubahan.file_akte'))
                    ->orderColumn('order')
                    ->typeColumn('type')
                    ->typeValue('document')
                    ->constrained(true)
                    ->required(),
                CuratorPicker::make('file_pengesahan_id')
                    ->label(__('akte_perubahan.file_pengesahan'))
                    ->orderColumn('order')
                    ->typeColumn('type')
                    ->typeValue('document')
                    ->constrained(true)
                    ->required(),
            ])
            ->collapsible()
            ->columnSpanFull();
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('profile_perusahaan.saved'));
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('nomor')
                            ->label(__('akte_perubahan.nomor')),
                        Infolists\Components\TextEntry::make('tanggal')
                            ->label(__('akte_perubahan.tanggal')),
                        Infolists\Components\TextEntry::make('nama_notaris')
                            ->label(__('akte_perubahan.nama_notaris')),
                        Infolists\Components\TextEntry::make('file_akte')
                            ->label(__('akte_perubahan.file_akte'))
                            ->url(function ($state) {
                                return is_array($state) ? Storage::url($state['file_akte'] ?? '') : Storage::url($state);
                            })
                            ->formatStateUsing(fn($state) => basename($state)),
                    ])->columns(),
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('nomor_pengesahan')
                            ->label(__('akte_perubahan.nomor_pengesahan')),
                        Infolists\Components\TextEntry::make('tanggal_pengesahan')
                            ->label(__('akte_perubahan.tanggal_pengesahan')),
                        Infolists\Components\TextEntry::make('file_pengesahan')
                            ->label(__('akte_perubahan.file_pengesahan'))
                            ->formatStateUsing(fn($state) => basename($state)),
                    ])->columns(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->label(__('akte_perubahan.nomor'))
                    ->badge()
                    ->color(fn($record) => $record->is_latest ? 'success' : '')
                    ->alignCenter()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tanggal')
                    ->label(__('akte_perubahan.tanggal'))
                    ->alignCenter()
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->color(fn($record) => $record->is_latest ? 'success' : '')
                    ->dateTime('d M Y'),
                TextColumn::make('nama_notaris')
                    ->label(__('akte_perubahan.nama_notaris'))
                    ->searchable()
                    ->sortable()
                    ->color(fn($record) => $record->is_latest ? 'success' : '')
                    ->toggleable(),
                TextColumn::make('nomor_pengesahan')
                    ->label(__('akte_perubahan.nomor_pengesahan'))
                    ->badge()
                    ->color(fn($record) => $record->is_latest ? 'success' : '')
                    ->alignCenter()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tanggal_pengesahan')
                    ->label(__('akte_perubahan.tanggal_pengesahan'))
                    ->alignCenter()
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->color(fn($record) => $record->is_latest ? 'success' : '')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()
                //     ->button(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAktePerubahans::route('/'),
            'create' => Pages\CreateAktePerubahan::route('/create'),
            'edit' => Pages\EditAktePerubahan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
