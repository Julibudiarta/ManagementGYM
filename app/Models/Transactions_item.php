<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions_item extends Model
{
    protected $fillable = [
        'transaction_id', 
        'product_id',
        'qty',
        'price_product', 
        'subtotal'
    ];

    public function transactions(){
        return $this->belongsTo(Transactions::class);
    }

    public function product(){
        return $this->belongsTo(Productss::class);
    }
}
