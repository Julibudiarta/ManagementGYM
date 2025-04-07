<?php

namespace App\Filament\Resources\PtSessionResource\Pages;

use App\Filament\Resources\PtSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPtSessions extends ListRecords
{
    protected static string $resource = PtSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
