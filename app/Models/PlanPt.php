<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPt extends Model
{
    protected $fillable = [
        'name', 'duration', 'max_session', 'price',
        'duration_per_minute', 'category', 'description', 'loyalty_poin'
    ];
}
