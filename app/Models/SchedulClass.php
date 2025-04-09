<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulClass extends Model
{
    protected $table = 'schedule_classes';
    protected $fillable = [
        'plan_id',
        'instructor_id',
        'date',
        'time_at',
        'class_type',
        'description',
    ];

    public function plan()
    {
        return $this->belongsTo(PlanClass::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

}
