<?php

namespace App\Filament\Clusters\Administrasi\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pengurus;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Administrasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Administrasi\Resources\PengurusResource\Pages;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class PengurusResource extends Resource
{
    protected static ?string $model = Pengurus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Administrasi::class;

    protected static ?int $navigationSort = 5;

    public static function getLabel(): string
    {
        return __('navigation.pengurus');
    }

    public static function getNavigationLabel(): string
    {
        return __('pengurus.pengurus');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('pengurus.identitas'))
                            ->description(__('pengurus.deskripsi_identitas'))
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label(__('pengurus.nama'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('kewarganegaraan')
                                    ->label(__('pengurus.kewarganegaraan'))
                                    ->required(),
                                Forms\Components\TextInput::make('no_ktp')
                                    ->label(__('pengurus.no_ktp'))
                                    ->required(),
                                Forms\Components\TextInput::make('npwp')
                                    ->label(__('pengurus.npwp'))
                                    ->mask('99.999.999.9-999.999')
                                    ->placeholder('XX.XXX.XXX.X-XXX.XXX')
                                    ->maxLength(20)
                                    ->required(),
                                Forms\Components\TextInput::make('no_bpjs_kesehatan')
                                    ->label(__('pengurus.no_bpjs_kesehatan'))
                                    ->nullable(),
                                Forms\Components\TextInput::make('no_bpjs_ketenagakerjaan')
                                    ->label(__('pengurus.no_bpjs_ketenagakerjaan'))
                                    ->nullable(),
                                Forms\Components\Toggle::make('orang_asli_papua')
                                    ->label(__('pengurus.orang_asli_papua'))
                                    ->required(),
                            ])->columns(),

                        Forms\Components\Section::make(__('pengurus.detail_kepengurusan'))
                            ->description(__('pengurus.deskripsi_kepengurusan'))
                            ->collapsible()
                            ->schema([
                                Forms\Components\Select::make('jenis_kepengurusan')
                                    ->label(__('pengurus.jenis_kepengurusan'))
                                    ->required()
                                    ->options([
                                        'Individu WNI' => 'Individu WNI',
                                        'Individu WNA' => 'Individu WNA',
                                    ]),
                                Forms\Components\TextInput::make('jabatan')
                                    ->label(__('pengurus.jabatan'))
                                    ->required(),
                                Forms\Components\DatePicker::make('menjabat_sampai')
                                    ->label(__('pengurus.menjabat_sampai'))
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label(__('pengurus.status'))
                                    ->options([
                                        'Aktif' => 'Aktif',
                                        'Non-Aktif' => 'Non-Aktif',
                                    ])
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Toggle::make('masih_bekerja')
                                    ->label(__('pengurus.masih_bekerja'))
                                    ->required(),
                            ])->columns(),

                        Forms\Components\Section::make(__('pengurus.domisili'))
                            ->description(__('pengurus.deskripsi_domisili'))
                            ->collapsible()
                            ->schema([
                                Forms\Components\Textarea::make('alamat')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->label(__('pengurus.alamat'))
                                    ->required(),
                                Forms\Components\TextInput::make('kabupaten_kota')
                                    ->label(__('pengurus.kabupaten_kota'))
                                    ->nullable(),
                                Forms\Components\TextInput::make('provinsi')
                                    ->label(__('pengurus.provinsi'))
                                    ->nullable(),
                            ])->columns(),
                    ])
                    ->columnSpan(3),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('pengurus.lampiran'))
                            ->description(__('pengurus.deskripsi_lampiran'))
                            ->schema([
                                CuratorPicker::make('file_ktp_id')
                                    ->label(__('pengurus.file_ktp'))
                                    ->required(),
                                CuratorPicker::make('file_npwp_id')
                                    ->label(__('pengurus.file_npwp'))
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
                Tables\Columns\TextColumn::make('nama')
                    ->label(__('pengurus.nama'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label(__('pengurus.jabatan'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\IconColumn::make('masih_bekerja')
                    ->label(__('pengurus.masih_bekerja'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_ktp')
                    ->label(__('pengurus.no_ktp'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('npwp')
                    ->label(__('pengurus.npwp'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenguruses::route('/'),
            'create' => Pages\CreatePengurus::route('/create'),
            'view' => Pages\ViewPengurus::route('/{record}'),
            'edit' => Pages\EditPengurus::route('/{record}/edit'),
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
