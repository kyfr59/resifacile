<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;


use Filament\Actions\DeleteAction;
use App\Filament\Resources\WebhookCallResource;
use Filament\Resources\Pages\EditRecord;

class EditWebhookCall extends EditRecord
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
