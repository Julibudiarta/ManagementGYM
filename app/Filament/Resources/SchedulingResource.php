<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchedulingResource\Pages;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Carbon;

class SchedulingResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Scheduling';
    }
    public static function getModelLabel(): string
    {
        return 'Scheduling';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tambahkan schema form jika diperlukan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(
            Member::whereHas('Membership', function ($query) {
                $query->where('status', 'active'); // Cek status di tabel pivot
            })
        )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Member')
                    ->sortable()
                    ->searchable(),

                    BadgeColumn::make('Membership.status')
                    ->label('Status Membership')
                    ->sortable()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'danger' => 'inactive',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('lastCheckIn.check_in_at')
                    ->label('Last Check-in')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Action::make('check_in')
                    ->label('Check-in')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Member $record) => $record->checkIns()->create(['check_in_at' => now()]))
                    ->visible(fn (Member $record) => $record->Membership()->where('status', 'active')->exists()) // Cek status di tabel pivot

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
            'index' => Pages\ListSchedulings::route('/'),
            // 'create' => Pages\CreateScheduling::route('/create'),
            // 'edit' => Pages\EditScheduling::route('/{record}/edit'),
        ];
    }
}
