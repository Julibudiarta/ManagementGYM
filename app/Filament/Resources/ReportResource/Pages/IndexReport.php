<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Transactions;
use App\Models\Transactions_item;
use PDF;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class IndexReport extends ListRecords
{
    protected static string $resource = ReportResource::class;
    protected static string $view = 'filament.pages.report.index';

    public function downloadIncomeExpenseReport(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transactions::query();

        // Filter jika tanggal mulai dan akhir diisi
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) { 
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) { 
            $query->where('created_at', '<=', $endDate);
        }
    
        $transactions = $query->get();

        $reportDate = now()->format('F j, Y');
        $pdf = PDF::loadView('filament.pages.report.report', compact('transactions', 'reportDate'));
        return $pdf->download('Income Report.pdf');

    }
    public function getViewData(): array
    {
        return [
            'transactions' => Transactions::all(),
            ''
        ];
    }
}
