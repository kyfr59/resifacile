<?php

namespace App\Filament\Resources\GuideResource\Pages;


use Filament\Actions\CreateAction;
use App\Filament\Resources\GuideResource;
use Filament\Resources\Pages\ListRecords;

class ListGuides extends ListRecords
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
