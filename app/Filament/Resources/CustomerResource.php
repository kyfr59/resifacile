<?php

namespace App\Filament\Resources;

use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\PaymentMethodsRelationManager;
use App\Helpers\Accounting;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Clients';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('compagny')
                    ->label('Société')
                    ->columnSpanFull(),
                TextEntry::make('first_name')->label('Prénom'),
                TextEntry::make('last_name')->label('Nom'),
                TextEntry::make('email'),
                TextEntry::make('phone')->label('Téléphone'),
                Fieldset::make('Abonnement')
                    ->relationship('subscription')
                    ->schema([
                        Grid::make()->schema([
                            TextEntry::make('designation')
                                ->label('Désignation'),
                            TextEntry::make('price')
                                ->label('Prix')
                                ->formatStateUsing(fn (string $state): string => Accounting::priceFormat(Accounting::addTax($state))),
                            TextEntry::make('status')
                                ->label('Statut')
                                ->badge()
                                ->color(fn (SubscriptionStatus $state): string => match ($state) {
                                    SubscriptionStatus::TRIAL => 'gray',
                                    SubscriptionStatus::CANCELED, SubscriptionStatus::CANCEL_REQUEST => 'danger',
                                    SubscriptionStatus::RECURRING => 'success',
                                    SubscriptionStatus::LATE_PAYMENT => 'warning',
                                }),
                                RepeatableEntry::make('transactions')
                                    ->label('Transactions')
                                    ->schema([
                                        Grid::make()->schema([
                                            TextEntry::make('transaction_id')
                                                ->label('Transaction ID'),
                                            TextEntry::make('amount')
                                                ->label('Montant')
                                                ->formatStateUsing(fn (string $state): string => Accounting::priceFormat(Accounting::addTax($state))),
                                            TextEntry::make('status')
                                                ->label('Statut')
                                                ->badge()
                                                ->color(fn (TransactionStatus $state): string => match ($state) {
                                                    TransactionStatus::CAPTURED, TransactionStatus::SUCCEEDED => 'success',
                                                    TransactionStatus::BLOCKED, TransactionStatus::AUTHENTICATION_FAILED, TransactionStatus::DENIED, TransactionStatus::REFUSED => 'warning',
                                                    TransactionStatus::CHARGED_BACK => 'error',
                                                    default => 'gray'
                                                }),
                                        ])->columns(3),
                                    ])->columnSpanFull(),
                        ])->columns(3),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compagny')
                    ->label('Société')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->icon('heroicon-m-device-phone-mobile')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_professional')
                    ->label('Professionnel')
                    ->boolean(),
                Tables\Columns\IconColumn::make('accept_gcs')
                    ->label('Validation CGV')
                    ->boolean(),
                Tables\Columns\IconColumn::make('accept_start_service')
                    ->label('Renonce au délai de rétractation')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
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
            OrdersRelationManager::class,
            PaymentMethodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
