<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Productss;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class PosTransaction extends ListRecords
{
    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.pages.pos';

    public function getViewData(): array
    {
        return [
            'products' => Productss::all(),
        ];
    }
}
