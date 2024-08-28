<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Peralatan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PeralatanResource\Pages;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use App\Filament\Resources\PeralatanResource\RelationManagers\StatusesRelationManager;

class PeralatanResource extends Resource
{
    protected static ?string $model = Peralatan::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    public static function getLabel(): string
    {
        return __('navigation.peralatan');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.teknis');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('peralatan.peralatan'))
                        ->description(__('peralatan.keterangan_peralatan'))
                        ->collapsible()
                        ->schema([
                            Forms\Components\TextInput::make('nama')
                                ->label(__('peralatan.nama'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('jumlah')
                                ->label(__('peralatan.jumlah'))
                                ->integer()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('kapasitas')
                                ->label(__('peralatan.kapasitas'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('satuan')
                                ->label(__('peralatan.satuan'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('merk')
                                ->label(__('peralatan.merk'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tahun_pembuatan')
                                ->label(__('peralatan.tahun_pembuatan'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('lokasi')
                                ->label(__('peralatan.lokasi'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('keterangan')
                                ->label(__('peralatan.keterangan'))
                                ->nullable()
                                ->rows(3)
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ])->columns(),
                ])->columnSpan(2),
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('peralatan.status'))
                        ->description(__('peralatan.keterangan_status'))
                        ->collapsible()
                        ->schema([
                            Forms\Components\Select::make('kondisi')
                                ->native(false)
                                ->label(__('peralatan.kondisi'))
                                ->options([
                                    'Baik' => 'Baik',
                                    'Rusak' => 'Rusak',
                                ])
                                ->required(),
                            Forms\Components\Select::make('kepemilikan')
                                ->native(false)
                                ->label(__('peralatan.kepemilikan'))
                                ->options([
                                    'Milik Sendiri' => 'Milik Sendiri',
                                    'Sewa Jangka Pendek' => 'Sewa Jangka Pendek',
                                    'Sewa Jangka Panjang' => 'Sewa Jangka Panjang',
                                ])
                                ->required(),
                        ]),
                    Forms\Components\Section::make(__('peralatan.attachment'))
                        ->description(__('peralatan.keterangan_attachment'))
                        ->collapsible()
                        ->schema([
                            CuratorPicker::make('media_id')
                                ->label(__('peralatan.attachment'))
                                ->constrained(true)
                                ->nullable(),
                        ]),
                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media_id')
                    ->label(__('peralatan.image'))
                    ->size(50),
                Tables\Columns\TextColumn::make('nama')
                    ->label(__('peralatan.nama'))
                    ->weight('bold')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label(__('peralatan.jumlah'))
                    ->alignCenter()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label(__('peralatan.kapasitas'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label(__('peralatan.satuan'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('merk')
                    ->label(__('peralatan.merk'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_pembuatan')
                    ->label(__('peralatan.tahun_pembuatan'))
                    ->alignCenter()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kondisi')
                    ->label(__('peralatan.kondisi'))
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => $state == 'Baik' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('kepemilikan')
                    ->label(__('peralatan.kepemilikan'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->label(__('peralatan.lokasi'))
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('statuses.status')
                    ->label(__('peralatan.status'))
                    ->badge()
                    ->color(fn($state) => $state == 'Tersedia' ? 'success' : 'danger')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
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
            StatusesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeralatans::route('/'),
            'create' => Pages\CreatePeralatan::route('/create'),
            'edit' => Pages\EditPeralatan::route('/{record}/edit'),
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
