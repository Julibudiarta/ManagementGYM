<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productss extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'name',
        'description',
        'purchase_price',
        'sele_price',
        'stok',
        'barcode',
        'category_id',
    ];
    
    public function Category(){
        return $this->belongsTo(Categoryy::class);
    }

    public function TransactionsItem(){
        return $this->hasMany(Transactions_item::class);
    }

    public function Transactions(){
        return $this->belongsToMany(Transactions::class, 'transactions_items', 'product_id', 'transaction_id');
    }
}
