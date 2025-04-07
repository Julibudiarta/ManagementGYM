<?php

namespace App\Filament\Resources\PtSessionResource\Pages;

use App\Filament\Resources\PtSessionResource;
use App\Models\PlanPt;
use App\Models\PtSession;
use App\Models\Transactions;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Redirect;
use function Laravel\Prompts\confirm;
use Filament\Notifications\Actions\Action;

class CreatePtSession extends CreateRecord
{
    protected static string $resource = PtSessionResource::class;

    protected function getCreateFormAction(): CreateAction
    {
        return CreateAction::make()
            ->label('Simpan')
            ->modalHeading('Konfirmasi & Pembayaran')
            ->modalSubheading('Periksa kembali sebelum menyimpan.')
            ->modalSubmitActionLabel('Bayar & Simpan')

            ->form([
                TextInput::make('amount')
                    ->label('Jumlah Pembayaran')
                    ->prefix('Rp ')
                    ->numeric()
                    ->required()
                    ->default(fn (CreatePtSession $livewire) => $livewire->data['amount'] ?? null)
                    ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                    ->readonly(),

                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer Bank',
                        'ewallet' => 'E-Wallet',
                    ])
                    ->required(),
                ])

            ->using(function (array $data, CreatePtSession $livewire) {
                // Gabungkan form utama dan form modal
                $formData = [
                    ...$livewire->form->getState(), // Form utama
                    'amount' => $data['amount'],
                    'payment_method' => $data['payment_method'],
                ];

                $sql = PtSession::create($formData);
                if($sql){
                    $planType = PlanPt::find($livewire->data['plan_id']);
                    $startDate = Carbon::parse($livewire->data['start_time']);
                    $endDate = $startDate->copy()->addDays((int) $planType->duration);
                    $sql->update(['end_time' => $endDate]);

                    #create Transaction
                    Transactions::create([
                        'user_id' => $livewire->data['admin_id'],
                        'member_id' => $livewire->data['member_id'],
                        'total_amount'=>$data['amount'],
                        'payment_method'=> $data['payment_method'],
                        'created_at' => now(),    
                    ]);
                }
                Redirect::route('filament.admin.resources.pt-sessions.index');
                Notification::make()
                    ->title('Langganan berhasil dibuat!')
                    ->success()
                    ->send();
            });
    }

    // protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    // {

    //     // Create the member first
    //     $SQL = PtSession::create($data);
    //     if(isset($data['start_time'])){
    //         $planType = PlanPt::find($data['plan_id']);
    //         $startDate = Carbon::parse($data['start_time']);
    //         $endDate = $startDate->copy()->addDays((int) $planType->duration);
    //         $SQL->update(['end_time' => $endDate]);

    //         #create Transaction

    //     }
    //     return $SQL;
    // }
}
