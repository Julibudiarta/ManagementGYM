<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class planClass extends Model
{
    
    protected $fillable = [
        'name',
        'type',
        'duration',
        'max_visitor',
        'description',
        'price',
    ];
    protected $dates = ['deleted_at'];
}
