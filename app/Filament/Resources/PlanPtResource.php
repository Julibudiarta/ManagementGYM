<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanPtResource\Pages;
use App\Filament\Resources\PlanPtResource\RelationManagers;
use App\Models\PlanPt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanPtResource extends Resource
{
    protected static ?string $model = PlanPt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Plan';
    }
    public static function getModelLabel(): string
    {
        return 'Plan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('max_session')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('duration_per_minute')
                    ->required()
                    ->numeric()
                    ->default(60),
                Forms\Components\Select::make('category')
                    ->required()
                    ->default('default')
                    ->options([
                        'default' => 'Default',
                        'free' => 'Free - One Per Member',
                        'general' => 'General - Unlimited',
                    ]),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('loyalty_poin')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_session')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_per_minute')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('loyalty_poin')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
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
            'index' => Pages\ListPlanPts::route('/'),
            'create' => Pages\CreatePlanPt::route('/create'),
            'edit' => Pages\EditPlanPt::route('/{record}/edit'),
        ];
    }
}
