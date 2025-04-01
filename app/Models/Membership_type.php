<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership_type extends Model
{
    protected $fillable=[
        'name',
        'description',
        'price',
        'duration',
        'is_active',
    ];
    public function members(){
        return $this->hasMany(Member::class,'membership_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Member_subscriptions::class);
    }
}
