<?php

namespace App\Filament\Resources\PtSessionResource\Pages;

use App\Filament\Resources\PtSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPtSession extends EditRecord
{
    protected static string $resource = PtSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
