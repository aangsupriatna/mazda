<?php

namespace App\Filament\Clusters\Administrasi\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pemilik;
use App\Models\Regency;
use App\Models\District;
use App\Models\Province;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Administrasi;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Administrasi\Resources\PemilikResource\Pages;
use App\Models\Village;

class PemilikResource extends Resource
{
    protected static ?string $model = Pemilik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Administrasi::class;

    protected static ?int $navigationSort = 4;

    public static function getLabel(): string
    {
        return __('navigation.pemilik');
    }

    public static function getNavigationLabel(): string
    {
        return __('pemilik.pemilik');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('pemilik.pemilik'))
                            ->description(__('pemilik.deskripsi'))
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label(__('pemilik.nama'))
                                    ->columnSpanFull()
                                    ->required(),
                                Forms\Components\Select::make('jenis_kepemilikan')
                                    ->label(__('pemilik.jenis_kepemilikan'))
                                    ->options([
                                        'Individu WNA' => 'Individu WNA',
                                        'Individu WNI' => 'Individu WNI',
                                        'Perusahaan Nasional' => 'Perusahaan Nasional',
                                        'Perusahaan Asing' => 'Perusahaan Asing',
                                        'Pemerintah' => 'Pemerintah',
                                    ])
                                    ->native(false)
                                    ->required(),
                                Forms\Components\TextInput::make('kewarganegaraan')
                                    ->label(__('pemilik.kewarganegaraan'))
                                    ->required(),
                                Forms\Components\TextInput::make('nik_paspor')
                                    ->label(__('pemilik.nik_paspor'))
                                    ->required(),
                                Forms\Components\TextInput::make('npwp')
                                    ->label(__('pemilik.npwp'))
                                    ->mask('99.999.999.9-999.999')
                                    ->placeholder('XX.XXX.XXX.X-XXX.XXX')
                                    ->maxLength(20)
                                    ->required(),
                                Forms\Components\TextInput::make('saham')
                                    ->label(__('pemilik.saham'))
                                    ->integer()
                                    ->required(),
                                Forms\Components\Radio::make('tipe_saham')
                                    ->label(__('pemilik.tipe_saham'))
                                    ->options([
                                        'Persen' => 'Persen',
                                        'Lembar' => 'Lembar',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Forms\Components\TextInput::make('alamat')
                                    ->label(__('pemilik.alamat'))
                                    ->required(),
                                Forms\Components\TextInput::make('kabupaten_kota')
                                    ->label(__('pemilik.kabupaten_kota'))
                                    ->required(),
                                Forms\Components\TextInput::make('provinsi')
                                    ->label(__('pemilik.provinsi'))
                                    ->required(),
                                Forms\Components\TextInput::make('negara')
                                    ->label(__('pemilik.negara'))
                                    ->required(),
                            ])->columns(),
                    ])->columnSpan(['lg' => 3]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('pemilik.lampiran'))
                            ->description(__('pemilik.deskripsi_lampiran'))
                            ->collapsible()
                            ->schema([
                                CuratorPicker::make('file_ktp_id')
                                    ->label(__('pemilik.file_ktp'))
                                    ->constrained(true)
                                    ->required(),
                                CuratorPicker::make('file_npwp_id')
                                    ->label(__('pemilik.file_npwp'))
                                    ->constrained(true)
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        // $defaultPagination = Tampilan::first()->records_per_page;
        return $table
            // ->defaultPaginationPageOption($defaultPagination->value)
            ->columns([
                TextColumn::make('nama')
                    ->label(__('pemilik.nama'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('nik_paspor')
                    ->label(__('pemilik.nik_paspor'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('npwp')
                    ->label(__('pemilik.npwp'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('saham')
                    ->badge()
                    ->alignCenter()
                    ->color('success')
                    ->label(__('pemilik.saham'))
                    ->formatStateUsing(function ($state, $record) {
                        return $record->tipe_saham === 'Persen' ? $state . '%' : $state . ' Lembar';
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
            'index' => Pages\ListPemiliks::route('/'),
            'create' => Pages\CreatePemilik::route('/create'),
            'edit' => Pages\EditPemilik::route('/{record}/edit'),
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
