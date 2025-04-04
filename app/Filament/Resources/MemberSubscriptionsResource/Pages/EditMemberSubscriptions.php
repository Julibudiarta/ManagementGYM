<?php

namespace App\Filament\Resources\MemberSubscriptionsResource\Pages;

use App\Filament\Resources\MemberSubscriptionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberSubscriptions extends EditRecord
{
    protected static string $resource = MemberSubscriptionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
