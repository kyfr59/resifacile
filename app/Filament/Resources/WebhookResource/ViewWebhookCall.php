<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;

use App\Filament\Resources\WebhookCallResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewWebhookCall extends ViewRecord
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('retry')
                ->label('Réessayer')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn () => ! empty($this->record->exception) || ($this->record->response_status_code >= 400))
                ->action(function () {
                }),
        ];
    }
}