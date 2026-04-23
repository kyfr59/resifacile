<?php

namespace App\Filament\Resources;

use App\Enums\SendingStatus;
use App\Filament\Resources\SendingResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use App\Models\Sending;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use App\Infolists\Components\Document;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class SendingResource extends Resource
{
    protected static ?string $model = Sending::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Courriers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (SendingStatus $state) => match ($state) {
                        SendingStatus::WAITING => 'gray',
                        SendingStatus::SENDED => 'warning',
                        SendingStatus::ACCEPTED => 'info',
                        SendingStatus::PROCESSED => 'success',
                    })
                    ->label('Statut')
                    ->sortable(),

                TextColumn::make('maileva_sending_id')
                    ->label('ID Maileva')
                    ->copyable()
                    ->copyMessage('ID Maileva copié')
                    ->default('-'),

                TextColumn::make('customer')
                    ->formatStateUsing(fn (object $state): string =>
                        '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">' . $state->name . '</a>'
                    )
                    ->label('Client')
                    ->alignCenter()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('customer', function ($query) use ($search) {
                            return $query
                                ->where('first_name', 'ilike', "%{$search}%")
                                ->orWhere('last_name', 'ilike', "%{$search}%")
                                ->orWhere('email', 'ilike', "%{$search}%");
                        });
                    })
                    ->html(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Créé le')
                    ->sortable(),

                TextColumn::make('executed_at')
                    ->dateTime()
                    ->label('Envoyé le')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('status')
                    ->label('Statut'),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime(),
                TextEntry::make('customer.name')
                    ->label('Client'),
                RepeatableEntry::make('data.requests')
                    ->schema([
                        Document::make('documentData')->label('Document')
                    ])
                ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSendings::route('/'),
            'view' => Pages\ViewSending::route('/{record}'),
        ];
    }
}