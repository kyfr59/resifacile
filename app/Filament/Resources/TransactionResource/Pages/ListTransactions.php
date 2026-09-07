<?php

namespace App\Filament\Resources\TransactionResource\Pages;


use Filament\Actions\CreateAction;
use App\Filament\Resources\TransactionResource;
use App\Filament\Widgets\TransactionsCreatedDailyChart;
use App\Filament\Widgets\TransactionsFutureDailyChart;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }
}
