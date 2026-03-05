<?php

namespace App\Filament\Resources\SendingResource\Pages;

use App\Filament\Resources\SendingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSending extends EditRecord
{
    protected static string $resource = SendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
