<?php

namespace App\Filament\Clusters\Perpajakan\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SptTahunan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Perpajakan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use App\Filament\Clusters\Perpajakan\Resources\SptTahunanResource\Pages;
use App\Filament\Clusters\Perpajakan\Resources\SptTahunanResource\RelationManagers;

class SptTahunanResource extends Resource
{
    protected static ?string $model = SptTahunan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Perpajakan::class;

    protected static ?int $navigationSort = 3;

    public static function getTitle(): string
    {
        return __('spt_tahunan.spt_tahunan');
    }

    public static function getLabel(): string
    {
        return __('navigation.spt_tahunan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Select::make('tahun')
                        ->label(__('spt_tahunan.tahun'))
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
                        ->unique(ignoreRecord: true)
                        ->required(),
                    Forms\Components\TextInput::make('nomor')
                        ->label(__('spt_tahunan.nomor'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('tanggal')
                        ->label(__('spt_tahunan.tanggal'))
                        ->required()
                        ->suffixIcon('heroicon-o-calendar')
                        ->native(false)
                        ->columnSpanFull(),
                ])
                    ->columnSpan(3),
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('spt_tahunan.lampiran'))
                        ->schema([
                            CuratorPicker::make('file_lampiran_id')
                                ->label(__('spt_tahunan.lampiran_spt_tahunan'))
                                ->constrained(true)
                                ->required(),
                        ]),
                ]),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')
                    ->label(__('spt_tahunan.tahun'))
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nomor')
                    ->label(__('spt_tahunan.nomor'))
                    ->alignCenter()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label(__('spt_tahunan.tanggal'))
                    ->date('d F Y')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                CuratorColumn::make('file_lampiran_id')
                    ->label(__('spt_tahunan.lampiran'))
                    ->size(40)
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


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSptTahunans::route('/'),
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
