<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;


use Filament\Actions\Action;
use App\Filament\Resources\WebhookCallResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWebhookCall extends ViewRecord
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('retry')
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