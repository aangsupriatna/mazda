<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\RekeningKoran;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RekeningKoranResource\Pages;
use App\Filament\Resources\RekeningKoranResource\RelationManagers;

class RekeningKoranResource extends Resource
{
    protected static ?string $model = RekeningKoran::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('rekening_koran.rekening_koran');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.keuangan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_rekening')
                    ->label(__('rekening_koran.nama_rekening'))
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('nomor_rekening')
                    ->label(__('rekening_koran.nomor_rekening'))
                    ->required(),
                Forms\Components\TextInput::make('bank')
                    ->label(__('rekening_koran.bank'))
                    ->required(),
                Forms\Components\Select::make('bulan')
                    ->label(__('rekening_koran.bulan'))
                    ->options(function () {
                        $bulan = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $bulan[$i] = Carbon::create()->month($i)->locale('id')->monthName;
                        }
                        return $bulan;
                    })
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('tahun')
                    ->label(__('rekening_koran.tahun'))
                    ->options(function() {
                        $tahunSekarang = intval(date('Y'));
                        $tahunMulai = $tahunSekarang - 10;
                        $options = [];
                        for ($tahun = $tahunSekarang; $tahun >= $tahunMulai; $tahun--) {
                            $options[$tahun] = $tahun;
                        }
                        return $options;
                    })
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('mata_uang')
                    ->label(__('rekening_koran.mata_uang'))
                    ->options([
                        'IDR' => 'IDR',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'SGD' => 'SGD',
                        'YEN' => 'YEN',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->label(__('rekening_koran.jumlah'))
                    ->prefix('Rp. ')
                    ->mask(RawJs::make('$money($input)', ['money' => 'formatMoney']))
                    ->stripCharacters(',')
                    ->required(),
                Forms\Components\FileUpload::make('lampiran')
                    ->label(__('rekening_koran.lampiran'))
                    ->disk('public')
                    ->directory('rekening_koran')
                    ->downloadable()
                    ->multiple()
                    ->columnSpanFull()
                    ->nullable(),
            ])
            ->columns();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_rekening')
                    ->label(__('rekening_koran.nama_rekening'))
                    ->copyable()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->label(__('rekening_koran.nomor_rekening'))
                    ->badge()
                    ->copyable()
                    ->alignCenter()
                    ->color('warning')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank')
                    ->label(__('rekening_koran.bank'))
                    ->copyable()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bulan')
                    ->formatStateUsing(fn (string $state): string => Carbon::create(1, $state, 1)->format('F'))
                    ->label(__('rekening_koran.bulan'))
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->formatStateUsing(fn (string $state): string => Carbon::create($state, 1, 1)->format('Y'))
                    ->label(__('rekening_koran.tahun'))
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mata_uang')
                ->label(__('rekening_koran.mata_uang'))
                    ->money('IDR', locale: 'id')
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->money('IDR', locale: 'id')
                    ->label(__('rekening_koran.jumlah'))
                    ->copyable()
                    ->searchable()
                    ->toggleable()
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
            ])
            ->groups([
                Tables\Grouping\Group::make('tahun')
                    ->label(__('rekening_koran.tahun'))
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (RekeningKoran $record): string => "{$record->tahun}")
                    ->collapsible(),
            ])
            ->defaultGroup('tahun');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                Infolists\Components\TextEntry::make('nama_rekening')
                    ->label(__('rekening_koran.nama_rekening')),
                Infolists\Components\TextEntry::make('nomor_rekening')
                    ->label(__('rekening_koran.nomor_rekening')),
                Infolists\Components\TextEntry::make('bank')
                    ->label(__('rekening_koran.bank')),
                Infolists\Components\TextEntry::make('bulan')
                    ->label(__('rekening_koran.bulan'))
                    ->formatStateUsing(fn (string $state): string => Carbon::create(1, $state, 1)->format('F')),
                Infolists\Components\TextEntry::make('tahun')
                    ->label(__('rekening_koran.tahun'))
                    ->formatStateUsing(fn (string $state): string => Carbon::create($state, 1, 1)->format('Y')),
                Infolists\Components\TextEntry::make('mata_uang')
                    ->label(__('rekening_koran.mata_uang')),
                Infolists\Components\TextEntry::make('jumlah')
                    ->label(__('rekening_koran.jumlah'))
                    ->formatStateUsing(fn (float $state): string => 'Rp. ' . number_format($state, 0, ',', '.')),
                Infolists\Components\TextEntry::make('lampiran')
                    ->label(__('rekening_koran.lampiran'))
                    ->formatStateUsing(function ($state): string {
                        if (is_string($state)) {
                            return '<a href="' . $state . '" target="_blank">' . basename($state) . '</a>';
                        } elseif (is_array($state)) {
                            return collect($state)->map(function ($file) {
                                return '<a href="' . $file->getUrl() . '" target="_blank">' . $file->getFilename() . '</a>';
                            })->implode(', ');
                        }
                        return '';
                    })
                    ->html()
                    ->icon('heroicon-o-paper-clip'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRekeningKorans::route('/'),
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
