<?php

use App\Filament\Resources\TransactionResource\Pages\PosTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/transactions/store', [PosTransaction::class, 'store'])->name('transactions.store');
