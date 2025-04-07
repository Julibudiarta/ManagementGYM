<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtSession extends Model
{
    protected $fillable = [
        'plan_id',
        'member_id',
        'start_time',
        'end_time',
        'trainer_id',
        'admin_id',
        'description',
    ];

    public function plan()
    {
        return $this->belongsTo(PlanPt::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
