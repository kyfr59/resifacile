<?php

namespace App\Filament\Resources;

use App\Enums\CampaignStatus;
use App\Filament\Resources\CampaignResource\Pages;
use App\Infolists\Components\Address;
use App\Infolists\Components\Document;
use App\Models\Campaign;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationGroup = 'Courriers';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Campagnes';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('data.name')
                    ->label('Site Expéditeur'),
                TextEntry::make('data')
                    ->label('Référence d\'envoi')
                    ->formatStateUsing(fn (object $state): string => $state->track_id ?? ''),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y'),
                TextEntry::make('executed_at')
                    ->label('Levée le')
                    ->dateTime('d/m/Y'),
                RepeatableEntry::make('data.requests')
                    ->label('Courriers')
                    ->schema([
                        Address::make('senders.0.paper_address')
                            ->label('Expéditeur')
                            ->columnSpan(1),
                        Address::make('recipients.0.paper_address')
                            ->label('Destinataire')
                            ->columnSpan(1),
                        Document::make('documentData')
                            ->label('Documents')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->grid(2)
                    ->columnSpanFull(),
            ]);
    }

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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data')
                    ->label('Référence d\'envoi')
                    ->formatStateUsing(fn (object $state): string => $state->track_id ?? '')
                    ->description(fn (Campaign $record): string => $record->data->name ?? '')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (CampaignStatus $state): string => match ($state) {
                        CampaignStatus::DRAFT => 'gray',
                        CampaignStatus::PENDING_EXECUTION => 'warning',
                        CampaignStatus::EXECUTED => 'success',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Client'),
                TextColumn::make('order.number')
                    ->label('Commande'),
                TextColumn::make('executed_at')
                    ->label('Levée le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'view' => Pages\ViewCampaign::route('/{record}'),
        ];
    }
}
