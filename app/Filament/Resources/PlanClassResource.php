<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanClassResource\Pages;
use App\Filament\Resources\PlanClassResource\RelationManagers;
use App\Models\PlanClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanClassResource extends Resource
{
    protected static ?string $model = PlanClass::class;

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
                Forms\Components\Select::make('type')
                    ->options([
                        'regular' => 'Regular',
                        'premium' => 'Premium',
                    ])  
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->default(60),
                Forms\Components\TextInput::make('max_visitor')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('price')
                //     ->numeric()
                //     ->prefix('$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Stack::make([
                Panel::make([
                  View::make('filament.pages.table.PlanClass'),
                    TextColumn::make('max_visitor')
                        ->label('Max Visitor')
                        ->icon('heroicon-o-user-group')
                        ->alignment('left'),
        
                    TextColumn::make('type')
                        ->label('Duration')
                        ->icon('heroicon-o-user-group')
                        ->alignment('left'),
        
                    TextColumn::make('duration')
                        ->label('Duration')
                        ->icon('heroicon-o-clock')
                        ->alignment('left'),
        
                    TextColumn::make('description')
                        ->label('Description')
                        ->icon('heroicon-o-information-circle')
                        ->alignment('left'),
                ])
            ]),
        ])
        ->recordUrl(null)
        ->recordAction(null)
        ->contentGrid([
            'md' => 1, // Jumlah kolom untuk layar medium
            'xl' => 3, // Jumlah kolom untuk layar besar
        ])
        // ->actions([ // Tambahkan actions di sini
        //     ActionGroup::make([
        //         ViewAction::make()
        //             ->label('View Details'),
        //         EditAction::make()
        //             ->label('Edit'),
        //         DeleteAction::make()
        //             ->label('Delete'),
        //     ])
        //     ->icon('heroicon-m-ellipsis-horizontal')
        //     ->tooltip('Actions'),
        // ])
            ->filters([
                //
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
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
            'index' => Pages\ListPlanClasses::route('/'),
            'view' =>pages\ViewPlanClass::route('/{record}/view'),
            'create' => Pages\CreatePlanClass::route('/create'),
            'edit' => Pages\EditPlanClass::route('/{record}/edit'),
        ];
    }
}
