<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'total_amount',
        'payment_method'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function transactionsItems(){
        return $this->hasMany(Transactions_item::class);
    }
    public function Products(){
        return $this->belongsToMany(Product::class, 'transactions_items', 'transaction_id', 'product_id');
    }
}
