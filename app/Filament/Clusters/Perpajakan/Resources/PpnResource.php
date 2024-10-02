<?php

namespace App\Filament\Clusters\Perpajakan\Resources;

use Closure;
use Carbon\Carbon;
use App\Models\Ppn;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Perpajakan;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Perpajakan\Resources\PpnResource\Pages;
use App\Filament\Clusters\Perpajakan\Resources\PpnResource\RelationManagers;

class PpnResource extends Resource
{
    protected static ?string $model = Ppn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Perpajakan::class;

    protected static ?int $navigationSort = 4;

    public static function getTitle(): string
    {
        return __('ppn.ppn');
    }

    public static function getLabel(): string
    {
        return __('navigation.ppn');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('ppn.ppn'))
                            ->description(__('ppn.deskripsi_ppn'))
                            ->schema([
                                Forms\Components\Select::make('tahun')
                                    ->label(__('ppn.tahun'))
                                    ->options(function () {
                                        $tahunSekarang = intval(date('Y')) + 5;
                                        $tahunMulai = $tahunSekarang - 15;
                                        $options = [];
                                        for ($tahun = $tahunSekarang; $tahun >= $tahunMulai; $tahun--) {
                                            $options[$tahun] = $tahun;
                                        }
                                        return $options;
                                    })
                                    ->default(date('Y'))
                                    ->native(false)
                                    ->required()
                                    ->searchable()
                                    ->rules([
                                        function (Forms\Get $get) {
                                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                                $exists = Ppn::where('tahun', $value)
                                                    ->where('bulan', $get('bulan'))
                                                    ->exists();
                                                if ($exists) {
                                                    $fail(__('ppn.tahun_bulan_sudah_ada'));
                                                }
                                            };
                                        },
                                    ]),
                                Forms\Components\Select::make('bulan')
                                    ->label(__('ppn.bulan'))
                                    ->options(function () {
                                        $bulan = [];
                                        for ($i = 1; $i <= 12; $i++) {
                                            $bulan[$i] = Carbon::create()->month($i)->locale('id')->monthName;
                                        }
                                        return $bulan;
                                    })
                                    ->native(false)
                                    ->required()
                                    ->rules([
                                        function (Forms\Get $get) {
                                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                                $exists = Ppn::where('bulan', $value)
                                                    ->where('tahun', $get('tahun'))
                                                    ->exists();
                                                if ($exists) {
                                                    $fail(__('ppn.tahun_bulan_sudah_ada'));
                                                }
                                            };
                                        },
                                    ]),
                                Forms\Components\TextInput::make('nomor')
                                    ->label(__('ppn.nomor'))
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal')
                                    ->label(__('ppn.tanggal'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->date('d F Y')
                                    ->native(false)
                                    ->required(),
                            ])->columns(),
                    ])
                    ->columnSpan(3),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('ppn.lampiran'))
                            ->description(__('ppn.deskripsi_lampiran'))
                            ->schema([
                                CuratorPicker::make('file_ppn_id')
                                    ->label(__('ppn.file_ppn'))
                                    ->constrained(true)
                                    ->required(),
                            ]),
                    ]),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        Tables\Columns\TextColumn::configureUsing(function (Tables\Columns\TextColumn $column): void {
            $column
                ->toggleable()
                ->sortable()
                ->searchable();
        });

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')
                    ->label(__('ppn.tahun'))
                    ->sortable(false),
                Tables\Columns\TextColumn::make('bulan')
                    ->label(__('ppn.bulan'))
                    ->getStateUsing(function ($record) {
                        return Carbon::create(1, $record->bulan, 1)->locale(app()->getLocale())->monthName;
                    }),
                Tables\Columns\TextColumn::make('nomor')
                    ->label(__('ppn.nomor')),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label(__('ppn.tanggal'))
                    ->date('d F Y')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale(app()->getLocale())->isoFormat('D MMMM Y');
                    }),
            ])
            ->groups([
                Tables\Grouping\Group::make('tahun')
                    ->label(__('ppn.tahun'))
                    ->collapsible()
                    ->orderQueryUsing(fn(Builder $query) => $query->orderByDesc('tahun')),
            ])
            ->defaultGroup('tahun')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPpns::route('/'),
            'create' => Pages\CreatePpn::route('/create'),
            'edit' => Pages\EditPpn::route('/{record}/edit'),
        ];
    }
}
