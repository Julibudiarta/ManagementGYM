<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberSubscriptionsResource\Pages;
use App\Filament\Resources\MemberSubscriptionsResource\RelationManagers;
use App\Models\member;
use App\Models\Member_subscriptions;
use App\Models\Membership_type;
use App\Models\User;
use DB;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberSubscriptionsResource extends Resource
{
    protected static ?string $model = Member_subscriptions::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return 'Membership';
    }
    public static function getModelLabel(): string
    {
        return 'Membership';
    }
    

    public static function form(Form $form): Form
    {
        $memberId = request()->get('member_id');
        return $form
            ->schema([
                Select::make('member_id')
                ->label('Member')
                ->options(member::pluck('name', 'id'))
                ->default($memberId)
                ->searchable()
                ->columnSpanFull()
                ->required(),

                Select::make('membership_id')
                ->label('Membership Type')
                ->options(fn () => Membership_type::pluck('name', 'id')->toArray())
                ->required()
                ->searchable()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($set, $get) => 
                    $set('amount', Membership_type::find($get('membership_id'))?->price ?? 0)
                ),
    
            DatePicker::make('start_date')
                ->label('Start Date')
                ->required(),
    
            Select::make('admin_id')
                ->label('Admin')
                ->options(User::pluck('name', 'id'))
                ->searchable()
                ->required(),
    
            
                TextInput::make('amount')
                ->label('Jumlah Pembayaran')
                ->numeric()
                ->required()
                ->prefix('Rp ')
                ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                ->readonly()
                ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                ->label('Created At'),
                TextColumn::make('Subscriptions.name')
                ->label('Plan'),
                TextColumn::make('Members.name')
                ->label('Member'),
                BadgeColumn::make('status')
                ->label('Status')
                ->sortable()
                ->colors([
                    'success' => 'active',
                    'danger' => 'expired',
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('activate_package')
                ->label('Aktifkan Paket')
                ->icon('heroicon-o-currency-dollar')
                ->color('primary')
                ->requiresConfirmation()
                // ->visible(fn (Member $record) => $record->membership?->status !== 'active') // Hanya muncul jika tidak aktif
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->label('Jumlah Pembayaran')
                        ->numeric()
                        ->required()
                        ->default(fn ($record) => $record->membershipType?->price ?? 0)
                        ->prefix('Rp ')
                        ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                        ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                        ->readonly(),
                ])
                ->action(function (array $data, Member_subscriptions $record) {
                    DB::transaction(function () use ($data, $record) {
                        // Update status paket ke "active"
                        $record->update([
                            'status' => 'active',
                            'end_date' => now()->addDays($record->membershipType?->duration ?? 30), // Contoh: Paket berlaku 1 bulan
                        ]);

                        // Simpan transaksi pembayaran
                        $record->Transactions()->create([
                            'user_id' => Auth()->user()->id,
                            'member_id' => $record->member_id,
                            'total_amount'=>$data['amount'],
                            'payment_method'=> 'cash',
                            'created_at' => now(),    
                        ]);
                    });

                    Notification::make()
                        ->title('Paket berhasil diaktifkan!')
                        ->success()
                        ->send();
                }),
            Action::make('changeMembership')
                ->label('Ubah Membership')
                ->form([
                    Select::make('membershipTypeId')
                        ->label('Membership Type')
                        ->options(fn () => Membership_type::pluck('name', 'id')->toArray())
                        ->required()
                        ->searchable()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => 
                            $set('amount', Membership_type::find($get('membershipTypeId'))?->price ?? 0)
                        ),
                        TextInput::make('amount')
                        ->label('Jumlah Pembayaran')
                        ->numeric()
                        ->required()
                        ->prefix('Rp ')
                        ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                        ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                        ->readonly()
                        ->reactive(),
                ])
                ->action(function ($record, array $data) {
                    $record->update(['membership_id' => $data['membershipTypeId']]);

                    DB::transaction(function () use ($data, $record) {
                        $record->update([
                            'status' => 'active',
                            'end_date' => now()->addDays($record->membershipType?->duration ?? 0), // opsional bisa dirubah dengan di tambah sisa hari dari paket sebelumnya?
                        ]);
                        $record->Transactions()->create([
                            'user_id' => Auth()->user()->id,
                            'member_id' => $record->member_id,
                            'total_amount'=>$data['amount'],
                            'payment_method'=> 'cash',
                            'created_at' => now(),    
                        ]);
                    });

                    Notification::make()
                        ->title('Membership berhasil diperbarui!')
                        ->success()
                        ->send();
                })
                // ->icon('heroicon-o-refresh')
                ->color('primary'),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ]),
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
            'index' => Pages\ListMemberSubscriptions::route('/'),
            'create' => Pages\CreateMemberSubscriptions::route('/create'),
            'edit' => Pages\EditMemberSubscriptions::route('/{record}/edit'),
        ];
    }
}
