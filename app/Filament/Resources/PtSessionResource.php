<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PtSessionResource\Pages;
use App\Filament\Resources\PtSessionResource\RelationManagers;
use App\Models\PlanPt;
use App\Models\PtSession;
use Filament\Forms;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PtSessionResource extends Resource
{
    protected static ?string $model = PtSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'PT Session';
    }
    public static function getModelLabel(): string
    {
        return 'PT Session';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan','name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $get) => 
                        $set('amount', PlanPt::find($get('plan_id'))?->price ?? 20000)
                    )
                    ->required(),
                Forms\Components\Select::make('member_id')
                ->relationship('member','name')
                    ->required(),
                Forms\Components\DatePicker::make('start_time')
                    ->required(),
                Forms\Components\Select::make('trainer_id')
                    ->relationship('trainer','name')
                    ->required(),
                Forms\Components\Select::make('admin_id')
                ->relationship('admin','name')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trainer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
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
            'index' => Pages\ListPtSessions::route('/'),
            'create' => Pages\CreatePtSession::route('/create'),
            'edit' => Pages\EditPtSession::route('/{record}/edit'),
        ];
    }
}
