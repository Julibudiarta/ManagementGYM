<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Member;
use App\Models\Member_subscriptions;
use App\Models\Membership_type;
use App\Models\MembershipType;
use App\Models\MemberSubscription;
use App\Models\Transactions;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    /**
     * Handle the creation of a record.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Create the member first
        $member = Member::create($data);

        // Create subscription if membership_type_id is provided
        if (isset($data['membership_type_id'])) {
            $membershipType = Membership_type::find($data['membership_type_id']);

            // Check if the membership type exists
            if ($membershipType) {
                $startDate = Carbon::now();
                $endDate = $startDate->copy()->addDays((int) $membershipType->duration);

                // Create a member subscription
                Member_subscriptions::create([
                    'member_id' => $member->id,
                    'membership_id' => $membershipType->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                // Simpan transaksi pembayaran
                Transactions::create([
                    'user_id' => Auth()->user()->id,
                    'member_id' => $member->id,
                    'total_amount'=>$membershipType->price,
                    'payment_method'=> 'cash',
                    'created_at' => now(),    
                ]);
            } else {
                // Handle the case where the membership type doesn't exist
                throw new \Exception("Membership type not found.");
            }
        }

        return $member;  // Return the member model instance
    }
}
