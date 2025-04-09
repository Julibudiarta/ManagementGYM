<?php

namespace App\Filament\Resources\PlanClassResource\Pages;

use App\Filament\Resources\PlanClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanClass extends EditRecord
{
    protected static string $resource = PlanClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
