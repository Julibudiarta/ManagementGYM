<?php

namespace App\Filament\Resources\PlanClassResource\Pages;

use App\Filament\Resources\PlanClassResource;
use App\Models\planClass;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class ListPlanClasses extends ListRecords
{
    protected static string $resource = PlanClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function destroy($id)
    {
        $planClass = planClass::findOrFail($id);
        $planClass->delete();
        if($planClass){
            Notification::make()
            ->title('Delete Success')
            ->success()
            ->send(); 
    
            return Redirect::route('filament.admin.resources.plan-classes.index');
        }else{
            Notification::make()
            ->title('Delete Error')
            ->danger()
            ->send(); 
    
            return Redirect::route('filament.admin.resources.plan-classes.index');  
        }  
 
    }
    protected function getTableContentGrid(): ?array
    {
        return [
            'md' => 2,
            'lg' => 3,
        ];
    }
}
