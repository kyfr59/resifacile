<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Helpers\Accounting;
use App\Infolists\Components\Address;
use App\Infolists\Components\Document;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Moyens de paiement';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('data')
                    ->label('Marque')
                    ->formatStateUsing(fn (object $state): string => $state->payment_product ?? $state->brand),
                TextEntry::make('data')
                    ->label('Numéro')
                    ->formatStateUsing(fn (object $state): string => $state->pan),
                TextEntry::make('data')
                    ->label('Date d\'expiration')
                    ->formatStateUsing(fn (object $state): string => $state->card_expiry_month . ' / ' . $state->card_expiry_year),
                TextEntry::make('customer')
                    ->label('Client')
                    ->formatStateUsing(fn (object $state): string => '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">'.$state->name.'</a>')
                    ->html(),
                TextEntry::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y'),
                TextEntry::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y'),
                ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextEntry::make('data.name')
                    ->label('Site Expéditeur'),
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
                    ->label('Marque')
                    ->formatStateUsing(fn (object $state): string => $state->payment_product ?? $state->brand)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data')
                    ->label('Numéro')
                    ->formatStateUsing(fn (object $state): string => $state->pan)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data')
                    ->label('Date d\'expiration')
                    ->formatStateUsing(fn (object $state): string => $state->card_expiry_month . ' / ' . $state->card_expiry_year)
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'view' => Pages\ViewPaymentMethod::route('/{record}'),
        ];
    }
}
