<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductssResource\Pages;
use App\Models\Productss;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductssResource extends Resource
{
    protected static ?string $model = Productss::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->nullable(),
                TextInput::make('purchase_price')
                    ->numeric()
                    ->required(),
                TextInput::make('sele_price')
                    ->numeric()
                    ->required(),
                TextInput::make('stok')
                    ->numeric()
                    ->required(),
                TextInput::make('barcode')
                    ->maxLength(255)
                    ->nullable(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('Category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('purchase_price')->sortable(),
                TextColumn::make('sele_price')->sortable(),
                TextColumn::make('stok')->sortable(),
                TextColumn::make('barcode')->sortable(),
                TextColumn::make('Category.name')->label('Category')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}