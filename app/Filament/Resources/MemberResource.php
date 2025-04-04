<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use App\Models\Membership_type;
use DB;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
use Str;
use Filament\Infolists\Components\View as InfolistView;

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
                    // FileUpload::make('photo')->image()->nullable(),
                    TextInput::make('identification')->numeric()->nullable(),
                        // Select input for Membership Type
                    Select::make('membership_type_id')
                        ->label('Membership Type')
                        ->options(fn () => Membership_type::pluck('name', 'id')->toArray())
                        ->required()
                        ->searchable()
                        ->afterStateUpdated(fn ($set, $get) => 
                            $set('amount', Membership_type::find($get('membership_type_id'))?->price ?? 0)
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
            // ImageColumn::make('photo')->circular(),
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
                    'danger' => 'expired',
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
                        ->default(fn ($record) => $record->activeMembership?->membershipType?->price ?? 0)
                        ->prefix('Rp ')
                        ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                        ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                        ->readonly(),
                ])
                ->action(function (array $data, Member $record) {
                    DB::transaction(function () use ($data, $record) {
                        // Update status paket ke "active"
                        $record->activeMembership()->update([
                            'status' => 'active',
                            'end_date' => now()->addDays($record->activeMembership?->membershipType?->duration ?? 30), // Contoh: Paket berlaku 1 bulan
                        ]);

                        // Simpan transaksi pembayaran
                        $record->Transactions()->create([
                            'user_id' => Auth()->user()->id,
                            'member_id' => $record->id,
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
                    $record->Membership()->update(['membership_id' => $data['membershipTypeId']]);

                    DB::transaction(function () use ($data, $record) {
                        $record->activeMembership()->update([
                            'status' => 'active',
                            'end_date' => now()->addDays($record->activeMembership?->membershipType?->duration ?? 0), // opsional bisa dirubah dengan di tambah sisa hari dari paket sebelumnya?
                        ]);
                        $record->Transactions()->create([
                            'user_id' => Auth()->user()->id,
                            'member_id' => $record->id,
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
            Tables\Actions\ViewAction::make()
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfolistView::make('filament.pages.DetailView.member')
            ->columnSpanFull()
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
            'view' => Pages\ViewMembers::route('/{record}'),
        ];
    }
}
