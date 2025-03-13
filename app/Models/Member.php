<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'photo',
        'identification',
    ];
    public function Transactions(){
        return $this->hasMany(Transactions::class);
    }
    public function memberType(){
        return $this->belongsTo(Membership_type::class);
    }

    public function Membership(){
        return $this->belongsToMany(Member_subscriptions::class);
    }
}
