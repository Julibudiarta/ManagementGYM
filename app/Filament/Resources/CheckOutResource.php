<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckOutResource\Pages;
use App\Filament\Resources\CheckOutResource\RelationManagers;
use App\Models\CheckIn;
use App\Models\Member;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckOutResource extends Resource
{
    protected static ?string $model = CheckIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Check-Out';
    }
    public static function getModelLabel(): string
    {
        return 'Check-Out';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(
            CheckIn::whereNull('check_out_at')
        )
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Nama Member')
                    ->sortable()
                    ->searchable(),

                    BadgeColumn::make('member.activeMembership.status')
                    ->label('Status Membership')
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'danger' => 'inactive',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_in_at')
                    ->label('Check-in')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Action::make('check_Out')
                    ->label('Check-Out')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (CheckIn $record) => $record->update(['check_out_at' => now()]))

                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCheckOuts::route('/'),
        ];
    }
}
