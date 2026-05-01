<?php

namespace App\Filament\Resources\SendingResource\Pages;

use App\Filament\Resources\SendingResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\SendingStatus;
use Filament\Infolists\Components\Grid;
use App\Filament\Resources\SendingResource\Pages;

class ViewSending extends ViewRecord
{
    protected static string $resource = SendingResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make('4')->schema([
                    TextEntry::make('id')
                        ->label('ID'),

                    TextEntry::make('status')
                        ->label('Statut')
                        ->badge()
                        ->formatStateUsing(fn ($state): string => $state->value)
                        ->color(fn (SendingStatus $state): string => match ($state) {
                            SendingStatus::WAITING => 'gray',
                            SendingStatus::SENDED => 'warning',
                            SendingStatus::ACCEPTED => 'info',
                            SendingStatus::PROCESSED => 'success',
                        }),
                ]),

                Section::make('Horodatage')
                    ->schema([
                        TextEntry::make('waiting_at')
                            ->label('Créé le')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('sended_at')
                            ->label('Envoyé le')
                            ->dateTime('d/m/Y H:i:s')
                            ->hidden(fn ($record) => blank($record?->sended_at)),
                        TextEntry::make('accepted_at')
                            ->label('Accepté le')
                            ->dateTime('d/m/Y H:i:s')
                             ->hidden(fn ($record) => blank($record?->accepted_at)),
                        TextEntry::make('processed_at')
                            ->label('Traité le')
                            ->dateTime('d/m/Y H:i:s')
                            ->hidden(fn ($record) => blank($record?->processed_at)),
                    ])
                    ->columns(4),
            ]);
    }
}