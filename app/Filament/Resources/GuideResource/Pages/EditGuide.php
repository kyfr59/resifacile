<?php

namespace App\Filament\Resources\GuideResource\Pages;


use Filament\Actions\DeleteAction;
use App\Filament\Resources\GuideResource;
use Filament\Resources\Pages\EditRecord;

class EditGuide extends EditRecord
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
