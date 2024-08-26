<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeralatanResource\Pages;
use App\Models\Peralatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Section::make(__('peralatan.peralatan'))
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label(__('peralatan.nama'))
                            ->required()
                            ->maxLength(255),
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
                        Forms\Components\TextInput::make('lokasi')
                            ->label(__('peralatan.lokasi'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->native(false)
                            ->label(__('peralatan.status'))
                            ->options([
                                'Tersedia' => 'Tersedia',
                                'Dipinjam' => 'Dipinjam',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label(__('peralatan.attachment'))
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('peralatan')
                            ->columnSpanFull(),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('attachment')
                    ->label(__('peralatan.image'))
                    ->square()
                    ->size(50),
                Tables\Columns\TextColumn::make('nama')
                    ->label(__('peralatan.nama'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label(__('peralatan.jumlah'))
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label(__('peralatan.kapasitas')),
                Tables\Columns\TextColumn::make('satuan')
                    ->label(__('peralatan.satuan')),
                Tables\Columns\TextColumn::make('merk')
                    ->label(__('peralatan.merk')),
                Tables\Columns\TextColumn::make('tahun_pembuatan')
                    ->label(__('peralatan.tahun_pembuatan'))
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('kondisi')
                    ->label(__('peralatan.kondisi'))
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => $state == 'Baik' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('kepemilikan')
                    ->label(__('peralatan.kepemilikan')),
                Tables\Columns\TextColumn::make('lokasi')
                    ->label(__('peralatan.lokasi')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('peralatan.status'))
                    ->badge()
                    ->color(fn($state) => $state == 'Tersedia' ? 'success' : 'danger'),
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
