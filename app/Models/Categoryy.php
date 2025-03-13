<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoryy extends Model
{

    protected $table = 'category';
    protected $fillable = ['name', 'description'];

    public function products(){
        return $this->hasMany(Productss::class);
    }
}
