<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Transactions;
use App\Models\Transactions_item;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class IndexReport extends ListRecords
{
    protected static string $resource = ReportResource::class;
    protected static string $view = 'filament.pages.report.report';

    public function getViewData(): array
    {
        return [
            'transactions' => Transactions::all(),
            ''
        ];
    }
}
