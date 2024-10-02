<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getTitle(): string
    {
        return __('pengaturan.pengguna');
    }

    public static function getLabel(): string
    {
        return __('navigation.pengguna');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.pengaturan');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'primary' : 'gray';
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('user.nama'))
                    ->prefixIcon('heroicon-o-user')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label(__('user.email'))
                    ->prefixIcon('heroicon-o-at-symbol')
                    ->required()
                    ->email(),
                Forms\Components\TextInput::make('password')
                    ->label(__('user.password'))
                    ->prefixIcon('heroicon-o-lock-open')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\Textarea::make('biography')
                    ->label(__('user.biography'))
                    ->columnSpanFull()
                    ->nullable(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label(__('user.avatar'))
                    ->default(fn ($record) => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($record->email))) . '?d=mp')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user.nama'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('user.email'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('user.created_at'))
                    ->dateTime(),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
