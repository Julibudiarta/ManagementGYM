<?php

namespace App\Filament\Resources\PlanClassResource\Pages;

use App\Filament\Resources\PlanClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanClass extends ViewRecord
{
    protected static string $resource = PlanClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
