<?php

namespace App\Filament\Resources\MemberSubscriptionsResource\Pages;

use App\Filament\Resources\MemberSubscriptionsResource;
use App\Models\Member_subscriptions;
use App\Models\Membership_type;
use App\Models\Transactions;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberSubscriptions extends CreateRecord
{
    protected static string $resource = MemberSubscriptionsResource::class;

    /**
     * Handle the creation of a record.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
      

        if (isset($data['membership_id'])) {
            $membershipType = Membership_type::find($data['membership_id']);

            // Check if the membership type exists
            if ($membershipType) {
                $startDate = Carbon::parse($data['start_date']);
                $endDate = $startDate->copy()->addDays((int) $membershipType->duration);

                // Create a member subscription
               $Membership = Member_subscriptions::create([
                    'member_id' => $data['member_id'],
                    'membership_id' => $membershipType->id,
                    'start_date' => $data['start_date'],
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                // Simpan transaksi pembayaran
                Transactions::create([
                    'user_id' => $data['admin_id'],
                    'member_id' => $data['member_id'],
                    'total_amount'=>$membershipType->price,
                    'payment_method'=> 'cash',
                    'created_at' => now(),    
                ]);
            } else {
                // Handle the case where the membership type doesn't exist
                throw new \Exception("Membership type not found.");
            }
        }

        return $Membership;

    }

}
