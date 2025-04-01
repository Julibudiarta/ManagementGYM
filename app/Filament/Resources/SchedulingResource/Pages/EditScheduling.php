<?php

namespace App\Filament\Resources\SchedulingResource\Pages;

use App\Filament\Resources\SchedulingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScheduling extends EditRecord
{
    protected static string $resource = SchedulingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
