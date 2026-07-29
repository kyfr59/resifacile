<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;

use App\Filament\Resources\WebhookCallResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListWebhookCalls extends ListRecords
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Pas de bouton "Créer" — les appels sont générés automatiquement
        ];
    }
}
