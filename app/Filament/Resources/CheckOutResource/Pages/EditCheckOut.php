<?php

namespace App\Filament\Resources\CheckOutResource\Pages;

use App\Filament\Resources\CheckOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCheckOut extends EditRecord
{
    protected static string $resource = CheckOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
