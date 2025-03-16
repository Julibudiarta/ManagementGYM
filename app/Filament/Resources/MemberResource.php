<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use App\Models\Membership_type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Member';
    }
    public static function getModelLabel(): string
    {
        return 'Member';
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Member Details')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->unique()->required(),
                    TextInput::make('phone')->unique()->required(),
                    DatePicker::make('birthdate')->required(),
                    TextInput::make('address')->required(),
                    Select::make('gender')->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])->required(),
                    FileUpload::make('photo')->image()->nullable(),
                    TextInput::make('identification')->numeric()->nullable(),
                        // Select input for Membership Type
                    Forms\Components\Select::make('membership_type_id')
                        ->label('Membership Type')
                        ->options(fn () => Membership_type::pluck('name', 'id')->toArray())
                        ->required()
                        ->searchable(),
                    ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('email')->sortable()->searchable(),
            TextColumn::make('phone')->sortable()->searchable(),
            TextColumn::make('birthdate')->date()->sortable(),
            TextColumn::make('address')->limit(30),
            BadgeColumn::make('gender'),
            ImageColumn::make('photo')->circular(),
            TextColumn::make('identification')->sortable(),
            TextColumn::make('Membership.Subscriptions.name')
            ->label('Membership')
            ->sortable()
            ->searchable(),

        // Tambahkan kolom status dari tabel relasi
            BadgeColumn::make('Membership.status')
                ->label('Status')
                ->sortable()
                ->colors([
                    'success' => 'active',
                    'warning' => 'pending',
                    'danger' => 'inactive',
                ]),
            TextColumn::make('Membership.start_date')
                ->label('Start Date')
                ->sortable()
                ->date(),
            TextColumn::make('Membership.end_date')
                ->label('End Date')
                ->sortable()
                ->date(),

        ])
        ->filters([
         Filter::make('gender')->query(fn ($query, $value) => $query->where('gender', $value)),
        ])
        ->actions([
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
