<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Helpers\Accounting;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Factures';

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
                TextColumn::make('number')
                    ->label('Numéro de facture')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('path'),
                TextColumn::make('transaction.amount')
                    ->label('Montant')
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat($state)),
                TextColumn::make('transaction.transaction_id')
                    ->label('Numéro de transaction'),
                TextColumn::make('transaction.transactionable.customer')
                    ->formatStateUsing(fn (object $state): string => '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">'.$state->name.'</a>')
                    ->label('Client')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('transaction.transactionable', callback: function ($query) use ($search) {
                                $query->whereHas('customer', function ($query) use ($search) {
                                    return $query
                                        ->where('first_name', 'ilike', "%{$search}%")
                                        ->orWhere('last_name', 'ilike', "%{$search}%")
                                        ->orWhere('email', 'ilike', "%{$search}%");
                                });
                            });
                    })
                    ->html(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
        ];
    }
}
