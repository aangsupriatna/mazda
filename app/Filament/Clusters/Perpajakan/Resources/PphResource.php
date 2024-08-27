<?php

namespace App\Filament\Clusters\Perpajakan\Resources;

use Closure;
use Carbon\Carbon;
use App\Models\Pph;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Perpajakan;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Perpajakan\Resources\PphResource\Pages;
use App\Filament\Clusters\Perpajakan\Resources\PphResource\RelationManagers;

class PphResource extends Resource
{
    protected static ?string $model = Pph::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Perpajakan::class;

    protected static ?int $navigationSort = 5;

    public static function getTitle(): string
    {
        return __('pph.pph');
    }

    public static function getLabel(): string
    {
        return __('navigation.pph');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('pph.deskripsi_pph'))
                        ->description(__('pph.deskripsi_pph'))
                        ->schema([
                            Forms\Components\Select::make('tahun')
                                ->label(__('pph.tahun'))
                                ->options(function () {
                                    $tahunSekarang = intval(date('Y'));
                                    $tahunMulai = $tahunSekarang - 10;
                                    $options = [];
                                    for ($tahun = $tahunSekarang; $tahun >= $tahunMulai; $tahun--) {
                                        $options[$tahun] = $tahun;
                                    }
                                    return $options;
                                })
                                ->default(date('Y'))
                                ->columnSpanFull()
                                ->native(false)
                                ->required()
                                ->rules([
                                    function (Forms\Get $get) {
                                        return function (string $attribute, $value, Closure $fail) use ($get) {
                                            $exists = Pph::where('tahun', $value)
                                                ->where('bulan', $get('bulan'))
                                                ->exists();
                                            if ($exists) {
                                                $fail(__('pph.tahun_bulan_sudah_ada'));
                                            }
                                        };
                                    },
                                ]),
                            Forms\Components\Select::make('bulan')
                                ->label(__('pph.bulan'))
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
                                            $exists = Pph::where('bulan', $value)
                                                ->where('tahun', $get('tahun'))
                                                ->exists();
                                            if ($exists) {
                                                $fail(__('pph.tahun_bulan_sudah_ada'));
                                            }
                                        };
                                    },
                                ]),
                            Forms\Components\TextInput::make('nomor')
                                ->label(__('pph.nomor'))
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal')
                                ->label(__('pph.tanggal'))
                                ->suffixIcon('heroicon-o-calendar')
                                ->displayFormat(function () {
                                    return match (app()->getLocale()) {
                                        'id' => 'd F Y',
                                        default => 'F d, Y',
                                    };
                                })
                                ->native(false)
                                ->required(),
                        ]),
                ])->columnSpan(3),
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('pph.deskripsi_lampiran'))
                        ->description(__('pph.deskripsi_lampiran'))
                        ->schema([
                            CuratorPicker::make('file_pph_id')
                                ->label(__('pph.file_pph'))
                                ->constrained(true)
                                ->required(),
                        ]),
                ])->columnSpan(1),
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
                    ->label(__('pph.tahun'))
                    ->sortable(false),
                Tables\Columns\TextColumn::make('bulan')
                    ->label(__('pph.bulan'))
                    ->getStateUsing(function ($record) {
                        return Carbon::create(1, $record->bulan, 1)->locale(app()->getLocale())->monthName;
                    }),
                Tables\Columns\TextColumn::make('nomor')
                    ->label(__('pph.nomor')),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label(__('pph.tanggal'))
                    ->date('d F Y')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale(app()->getLocale())->isoFormat('D MMMM Y');
                    }),
            ])
            ->groups([
                Tables\Grouping\Group::make('tahun')
                    ->label(__('pph.tahun'))
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
            'index' => Pages\ListPphs::route('/'),
            'create' => Pages\CreatePph::route('/create'),
            'edit' => Pages\EditPph::route('/{record}/edit'),
        ];
    }
}
