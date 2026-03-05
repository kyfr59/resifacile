<?php

namespace App\Filament\Resources\SendingResource\Pages;

use App\Filament\Resources\SendingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSendings extends ListRecords
{
    protected static string $resource = SendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
