<?php

namespace App\Filament\Resources\SchedulClassResource\Pages;

use App\Filament\Resources\SchedulClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedulClasses extends ListRecords
{
    protected static string $resource = SchedulClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
