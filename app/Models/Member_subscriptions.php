<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member_subscriptions extends Model
{
    protected $fillable = [
        'member_id',
        'subscription_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function Members(){
        return $this->belongsTo(Member::class);
    }
    public function Subscriptions(){
        return $this->belongsTo(Membership_type::class);
    }
}
