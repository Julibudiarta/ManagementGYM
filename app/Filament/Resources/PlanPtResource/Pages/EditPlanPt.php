<?php

namespace App\Filament\Resources\PlanPtResource\Pages;

use App\Filament\Resources\PlanPtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanPt extends EditRecord
{
    protected static string $resource = PlanPtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
