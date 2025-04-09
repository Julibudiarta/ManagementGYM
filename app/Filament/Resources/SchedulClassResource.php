<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchedulClassResource\Pages;
use App\Filament\Resources\SchedulClassResource\RelationManagers;
use App\Models\SchedulClass;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedulClassResource extends Resource
{
    protected static ?string $model = SchedulClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Scheduling Class';
    }
    public static function getModelLabel(): string
    {
        return 'Scheduling Class';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan','name')
                    ->required(),
                // Forms\Components\DatePicker::make('date')
                //     ->required()
                //     ->reactive(),
                Forms\Components\Select::make('day')
                    ->options([
                        'sunday'    => 'Sunday',
                        'monday'    => 'Monday',
                        'tuesday'   => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday'  => 'Thursday',
                        'friday'    => 'Friday',
                        'saturday'  => 'Saturday',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\TimePicker::make('time_at')
                    ->label('Start at')
                    ->seconds(false)
                    ->format('H:i')
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('instructor_id')
                    ->label('Instructor')
                    ->required()
                    ->reactive()
                    ->relationship('instructor','name')
                    ->disabled(function (callable $get) {
                        $date = $get('day');
                        $time = $get('time_at');
                    
                        return !$date || !$time;
                    })
                    ->options(function (callable $get) {
                        $date = $get('day');
                        $time = $get('time_at');
                 
                        return user::AvailableInstructor($date, $time)
                                   ->pluck('name', 'id');
                    })
                    ->searchable(),
                // Forms\Components\TextInput::make('class_type')
                //     ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instructor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_at'),
                // Tables\Columns\TextColumn::make('class_type')
                //     ->searchable(),
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
            'index' => Pages\ListSchedulClasses::route('/'),
            'create' => Pages\CreateSchedulClass::route('/create'),
            'edit' => Pages\EditSchedulClass::route('/{record}/edit'),
        ];
    }
}
