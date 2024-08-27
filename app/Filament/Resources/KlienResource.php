<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Klien;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KlienResource\Pages;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Tables\CuratorColumn;

class KlienResource extends Resource
{
    protected static ?string $model = Klien::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('navigation.klien');
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
                    Forms\Components\TextInput::make('nama')
                        ->label(__('klien.nama'))
                        ->unique(Klien::class, 'nama', ignoreRecord: true)
                        ->required(),
                    Forms\Components\TextInput::make('alias')
                        ->label(__('klien.alias'))
                        ->required(),
                    Forms\Components\TextInput::make('nomor_telepon')
                        ->label(__('klien.nomor_telepon'))
                        ->tel(),
                    Forms\Components\TextInput::make('website')
                        ->label(__('klien.website'))
                        ->nullable(),
                    Forms\Components\Textarea::make('alamat')
                        ->label(__('klien.alamat'))
                        ->columnSpanFull()
                        ->rows(3),
                ])
                    ->columns(2)
                    ->columnSpan(3),
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('klien.logo'))
                        ->schema([
                            CuratorPicker::make('logo_id')
                                ->label(__('klien.logo'))
                                ->constrained(true)
                                ->required(),
                        ]),
                ]),
            ])->columns(4);

        // ->schema([
        //     Forms\Components\TextInput::make('nama')
        //         ->label(__('klien.nama'))
        //         ->unique(Klien::class, 'nama', ignoreRecord: true)
        //         ->required(),
        //     Forms\Components\TextInput::make('alias')
        //         ->label(__('klien.alias'))
        //         ->required(),
        //     Forms\Components\TextInput::make('nomor_telepon')
        //         ->label(__('klien.nomor_telepon'))
        //         ->tel(),
        //     Forms\Components\TextInput::make('website')
        //         ->label(__('klien.website'))
        //         ->nullable(),
        //     Forms\Components\Textarea::make('alamat')
        //         ->label(__('klien.alamat'))
        //         ->columnSpanFull()
        //         ->rows(3),
        //     CuratorPicker::make('logo_id')
        //         ->label(__('klien.logo'))
        //         ->columnSpanFull(),

        // ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                CuratorColumn::make('logo_id')
                    ->label(__('klien.logo'))
                    ->toggleable()
                    ->size(25),
                Tables\Columns\TextColumn::make('alias')
                    ->label(__('klien.alias'))
                    ->wrap()
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->description(fn(Klien $record): string => $record->nama),
                Tables\Columns\TextColumn::make('alamat')
                    ->label(__('klien.alamat'))
                    ->wrap()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label(__('klien.nomor_telepon'))
                    ->url(fn($record) => 'tel:' . $record->nomor_telepon, true)
                    ->searchable()
                    ->toggleable()
                    ->color('success')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('website')
                    ->label(__('klien.website'))
                    ->url(fn(Klien $record): string => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : "http://{$record->website}") : '#')
                    ->openUrlInNewTab()
                    ->color('danger')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-globe-alt')
                    ->badge()
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('nama')
                    ->label(__('klien.nama'))
                    ->formatStateUsing(function ($state, $record) {
                        $logoUrl = $record->logo ? Storage::disk('public')->url($record->logo->path) : null;
                        $logoHtml = $logoUrl
                            ? '<img src="' . $logoUrl . '" alt="Logo" class="inline-block px-2 mr-2 w-12 h-12 rounded-full">'
                            : '';
                        return new \Illuminate\Support\HtmlString($logoHtml . e($state));
                    })
                    ->html(),
                Infolists\Components\TextEntry::make('alamat')
                    ->label(__('klien.alamat')),
                Infolists\Components\TextEntry::make('alias')
                    ->label(__('klien.alias')),
                Infolists\Components\TextEntry::make('nomor_telepon')
                    ->label(__('klien.nomor_telepon'))
                    ->color('success')
                    ->badge(),
                Infolists\Components\TextEntry::make('website')
                    ->label(__('klien.website'))
                    ->color('danger')
                    ->badge(),
            ])
            ->columns(1)
            ->inlineLabel();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKliens::route('/'),
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
