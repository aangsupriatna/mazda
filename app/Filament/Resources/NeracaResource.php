<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Neraca;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\NeracaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeracaResource extends Resource
{
    protected static ?string $model = Neraca::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return __('neraca.neraca');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.keuangan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun')
                    ->label(__('neraca.tahun'))
                    ->options(function () {
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
                Forms\Components\TextInput::make('kekayaan_bersih')
                    ->label(__('neraca.kekayaan_bersih'))
                    ->prefix('Rp. ')
                    ->mask(RawJs::make('$money($input)', ['money' => 'formatMoney']))
                    ->stripCharacters(',')
                    ->required(),
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('auditor')
                            ->label(__('neraca.auditor'))
                            ->required(),
                        Forms\Components\TextInput::make('nomor')
                            ->label(__('neraca.nomor'))
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label(__('neraca.tanggal'))
                            ->native(false)
                            ->required(),
                    ]),
                Forms\Components\Textarea::make('kesimpulan')
                    ->label(__('neraca.kesimpulan'))
                    ->columnSpanFull()
                    ->rows(3)
                    ->required(),
                Forms\Components\FileUpload::make('lampiran')
                    ->label(__('neraca.lampiran'))
                    ->disk('public')
                    ->directory('neraca')
                    ->downloadable()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')
                    ->formatStateUsing(fn(string $state): string => Carbon::create($state, 1, 1)->format('Y'))
                    ->label(__('neraca.tahun'))
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auditor')
                    ->label(__('neraca.auditor'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy('tahun', 'asc');
                    }),
                Tables\Columns\TextColumn::make('nomor')
                    ->label(__('neraca.nomor'))
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->label(__('neraca.tanggal'))
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kekayaan_bersih')
                    ->label(__('neraca.kekayaan_bersih'))
                    ->money('IDR', locale: 'id')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kesimpulan')
                    ->label(__('neraca.kesimpulan'))
                    ->searchable()
                    ->limit(100)
                    ->toggleable()
                    ->sortable()
                    ->wrap(),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Infolists\Components\TextEntry::make('tahun')
                    ->label(__('neraca.tahun'))
                    ->formatStateUsing(fn(string $state): string => Carbon::create($state, 1, 1)->format('Y')),
                Infolists\Components\TextEntry::make('auditor')
                    ->label(__('neraca.auditor')),
                Infolists\Components\TextEntry::make('nomor')
                    ->label(__('neraca.nomor')),
                Infolists\Components\TextEntry::make('tanggal')
                    ->label(__('neraca.tanggal'))
                    ->date('d F Y'),
                Infolists\Components\TextEntry::make('kekayaan_bersih')
                    ->label(__('neraca.kekayaan_bersih'))
                    ->money('IDR', locale: 'id'),
                Infolists\Components\TextEntry::make('kesimpulan')
                    ->label(__('neraca.kesimpulan'))
                    ->alignJustify(),
                Infolists\Components\TextEntry::make('lampiran')
                    ->label(__('neraca.lampiran'))
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
                    ->icon('heroicon-o-paper-clip')
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNeracas::route('/'),
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
