<?php

use App\Filament\Resources\TransactionResource\Pages\PosTransaction;
use App\Filament\Resources\ReportResource\Pages\IndexReport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/transactions/store', [PosTransaction::class, 'store'])->name('transactions.store');

Route::post('/filament/report/incomeExpense/download', [IndexReport::class, 'downloadIncomeExpenseReport'])->name('filament.report.incomeExpense.download');
