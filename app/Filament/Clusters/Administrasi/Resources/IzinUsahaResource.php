<?php

namespace App\Filament\Clusters\Administrasi\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\IzinUsaha;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Administrasi;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\Pages;
use App\Filament\Clusters\Administrasi\Resources\IzinUsahaResource\RelationManagers;

class IzinUsahaResource extends Resource
{
    protected static ?string $model = IzinUsaha::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Administrasi::class;

    protected static ?int $navigationSort = 7;

    public static function getLabel(): string
    {
        return __('navigation.izin_usaha');
    }

    public static function getNavigationLabel(): string
    {
        return __('izin_usaha.izin_usaha');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Izin Usaha')
                            ->description(__('izin_usaha.deskripsi_izin_usaha'))
                            ->schema([
                                Forms\Components\Select::make('jenis_izin')
                                    ->options([
                                        'NIB' => 'NIB',
                                        'SIUJK' => 'SIUJK',
                                        'SIUP' => 'SIUP',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('nomor_izin')
                                    ->label(__('izin_usaha.nomor_izin'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('pemberi_izin')
                                    ->label(__('izin_usaha.pemberi_izin'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('kualifikasi_izin')
                                    ->label(__('izin_usaha.kualifikasi_izin'))
                                    ->native(false)
                                    ->options([
                                        'Kecil' => 'Kecil',
                                        'Non Kecil' => 'Non Kecil',
                                    ])
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_izin')
                                    ->label(__('izin_usaha.tanggal_izin'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_kadaluarsa')
                                    ->label(__('izin_usaha.tanggal_kadaluarsa'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false)
                                    ->required(),
                            ])->columns(),
                        Forms\Components\Section::make('Kualifikasi Usaha')
                            ->description(__('izin_usaha.deskripsi_kualifikasi_usaha'))
                            ->schema([
                                Forms\Components\Repeater::make('kualifikasi_usaha')
                                    ->label(__('izin_usaha.kualifikasi_usaha'))
                                    ->collapsible()
                                    ->collapsed(fn($state): bool => !empty($state))
                                    ->itemLabel(
                                        fn(array $state): ?string => ($state['kode'] ?? '') . ' - ' . ($state['nama_kualifikasi'] ?? '')
                                    )
                                    ->defaultItems(false)
                                    ->schema([
                                        Forms\Components\TextInput::make('kode'),
                                        Forms\Components\TextInput::make('nama_kualifikasi'),
                                    ]),
                            ]),
                    ])->columnSpan(3),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('izin_usaha.lampiran'))
                            ->description(__('izin_usaha.deskripsi_lampiran'))
                            ->schema([
                                CuratorPicker::make('file_izin_id')
                                    ->label(__('izin_usaha.file_izin'))
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_izin')
                    ->label(__('izin_usaha.jenis_izin'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_izin')
                    ->label(__('izin_usaha.nomor_izin'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('pemberi_izin')
                    ->label(__('izin_usaha.pemberi_izin'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('kualifikasi_izin')
                    ->label(__('izin_usaha.kualifikasi_izin'))
                    ->badge()
                    ->color(fn($state) => $state === 'Kecil' ? 'warning' : 'success')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_izin')
                    ->label(__('izin_usaha.tanggal_izin'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kadaluarsa')
                    ->label(__('izin_usaha.tanggal_kadaluarsa'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('izin_usaha.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('izin_usaha.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIzinUsahas::route('/'),
            'create' => Pages\CreateIzinUsaha::route('/create'),
            'edit' => Pages\EditIzinUsaha::route('/{record}/edit'),
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
