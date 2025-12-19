<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PostageType;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\CampaignsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\TransactionsRelationManager;
use App\Helpers\Accounting;
use App\Models\Order;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Commandes';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make('4')->schema([
                    TextEntry::make('number')
                        ->label('Numéro de commande'),
                    TextEntry::make('amount')
                        ->label('Montant')
                        ->formatStateUsing(fn (string $state): string => Accounting::priceFormat(Accounting::addTax($state))),
                    TextEntry::make('postage')
                        ->label('Type')
                        ->badge()
                        ->formatStateUsing(fn (PostageType $state): string => $state->label())
                        ->color(fn (PostageType $state): string => match ($state) {
                            PostageType::REGISTERED_LETTER => 'success',
                            PostageType::FOLLOWED_LETTER => 'warning',
                            PostageType::DOWNLOADABLE_LETTER => 'gray',
                        }),
                    TextEntry::make('status')
                        ->label('Statut')
                        ->badge()
                        ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                        ->color(fn (OrderStatus $state): string => match ($state) {
                            OrderStatus::PAID => 'success',
                            OrderStatus::UNPAID => 'danger',
                        }),
                ]),
                Fieldset::make('Client')
                    ->schema([
                        TextEntry::make('customer.first_name')->label('Prénom'),
                        TextEntry::make('customer.last_name')->label('Nom'),
                        TextEntry::make('customer.email'),
                        TextEntry::make('customer.phone')->label('Téléphone'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Numéro de commande')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer')
                    ->formatStateUsing(fn (object $state): string => '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">'.$state->name.'</a>')
                    ->label('Client')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('customer', function ($query) use ($search) {
                                return $query
                                    ->where('first_name', 'ilike', "%{$search}%")
                                    ->orWhere('last_name', 'ilike', "%{$search}%")
                                    ->orWhere('email', 'ilike', "%{$search}%");
                            });
                    })
                    ->html(),
                TextColumn::make('customer.email')
                    ->label('Email'),
                TextColumn::make('amount')
                    ->label('Montant')
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat(Accounting::addTax($state))),
                TextColumn::make('postage')
                    ->badge()
                    ->formatStateUsing(fn (PostageType $state): string => $state->label())
                    ->color(fn (PostageType $state): string => match ($state) {
                        PostageType::REGISTERED_LETTER => 'success',
                        PostageType::FOLLOWED_LETTER => 'warning',
                        PostageType::DOWNLOADABLE_LETTER => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::PAID => 'success',
                        OrderStatus::UNPAID => 'danger',
                    })
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
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
