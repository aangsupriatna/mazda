<?php

namespace App\Filament\Resources\PeralatanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'statuses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('peminjam')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tanggal_peminjaman')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->suffixIcon('heroicon-m-calendar')
                    ->nullable(),
                Forms\Components\DatePicker::make('tanggal_pengembalian')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->suffixIcon('heroicon-m-calendar')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('peminjam')
            ->columns([
                Tables\Columns\TextColumn::make('peminjam')
                    ->label(__('peralatan.peminjam'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('peralatan.status'))
                    ->badge()
                    ->alignCenter()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_peminjaman')
                    ->label(__('peralatan.tanggal_peminjaman'))
                    ->date('d M Y')
                    ->alignCenter()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pengembalian')
                    ->label(__('peralatan.tanggal_pengembalian'))
                    ->date('d M Y')
                    ->alignCenter()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
