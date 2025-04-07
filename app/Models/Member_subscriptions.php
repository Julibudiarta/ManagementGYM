<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Member_subscriptions extends Model
{
    protected $fillable = [
        'member_id',
        'membership_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function Members(){
        return $this->belongsTo(Member::class,'member_id');
    }
    public function Subscriptions(){
        return $this->belongsTo(Membership_type::class,'membership_id');
    }

    public function membershipType()
    {
        return $this->belongsTo(Membership_type::class,'membership_id');
    }

    protected static function booted()
    {
        static::retrieved(function ($model) {
            if ($model->end_date && Carbon::now()->greaterThan($model->end_date)) {
                $model->update(['status' => 'expired']);
            }
        });
    }
    public function Admin(){
        return $this->belongsTo(User::class);
    }
    public function Transactions(){
        return $this->hasMany(Transactions::class,'member_id');
    }
}
