<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Resources\MembershipResource\RelationManagers;
use App\Models\Membership_type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\DecimalColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership_type::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Membership Type';
    }
    public static function getModelLabel(): string
    {
        return 'Membership Type';
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Membership Type Name'),
            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->maxLength(10)
                ->label('Price')
                ->hint('Price per membership type'),
            Forms\Components\TextInput::make('duration')
                ->required()
                ->numeric()
                ->label('Duration (Days)')
                ->hint('Duration in days'),
            Forms\Components\Textarea::make('description')
                ->nullable()
                ->maxLength(65535)
                ->label('Description'),
            Forms\Components\Toggle::make('is_active')
                ->label('Is Active?')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Membership Type'),
                TextColumn::make('price')
                    ->sortable()
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => 'Rp.' . number_format($state, 2)),
                TextColumn::make('duration')
                    ->sortable()
                    ->label('Duration (Days)'),
                BooleanColumn::make('is_active')
                    ->sortable()
                    ->label('Active'),
            ])
            ->filters([
                // Optionally, add filters here (e.g., for active/inactive memberships)
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }
}
